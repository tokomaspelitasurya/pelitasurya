<?php

namespace Corals\Modules\Subscriptions\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Subscriptions\Models\Plan;

class PlanRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Plan::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Plan::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191',
                'price' => 'required',
                'bill_frequency' => 'required',
                'bill_cycle' => 'required',
                'display_order' => 'required',
                'status' => 'required',
                'description' => 'required',
                'features' => 'required',
            ]);

            foreach ($this->get('features', []) as $id => $feature) {
                $rules = array_merge($rules, [
                    "features.{$id}.value" => 'required'
                ]);
            }
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'code' => 'required|unique:plans',
            ]);
        }

        if ($this->isUpdate()) {
            $plan = $this->route('plan');

            $rules = array_merge($rules, [
                'code' => 'required|unique:plans,code,' . $plan->id,
            ]);
        }

        return $rules;
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isUpdate() || $this->isStore()) {
            $data = $this->all();

            $data['recommended'] = \Arr::get($data, 'recommended', false);

            $data['free_plan'] = \Arr::get($data, 'free_plan', false);
            $data['is_visible'] = \Arr::get($data, 'is_visible', false);

            if (isset($data['code'])) {
                $data['code'] = \Str::slug($data['code']);
            }
            if ($this->filled('features')) {
                $data['features'] = array_map(function ($values) {
                    if (isset($values['value'])) {
                        $values['value'] = !is_array($values['value']) ?: join(',', $values['value']);
                    }
                    return $values;
                }, $data['features']);
            }


            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        $attributes = array_keys($this->rules());

        $handled_attributes = [];

        foreach ($attributes as $attribute) {
            if (\Str::contains($attribute, 'features')) {
                $handled_attributes[$attribute] = 'feature value';
            } else {
                $handled_attributes[$attribute] = $attribute;
            }
        }

        return $handled_attributes;
    }

    public function messages()
    {
        $attributes = $this->attributes();

        $messages = [];

        foreach ($attributes as $attribute) {
            if (\Str::contains($attribute, 'feature value')) {
                $messages[$attribute . '.required'] = trans('Subscriptions::labels.plan.feature_required');
            }
        }

        return $messages;
    }
}

<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Traits\DownloadableRequest;
use Illuminate\Support\Facades\Validator;

class ProductRequest extends BaseRequest
{
    use DownloadableRequest;

    public function __construct()
    {
        Validator::extend("unique_with_global", function ($attribute, $value, $parameters) {
            $global_options = $this->get('global_options', []);
            return (!array_intersect($value, $global_options));
        });

        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Product::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Product::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191',
                'caption' => 'required',
                'status' => 'required',
                'type' => 'required',
                'inventory' => 'required_if:type,simple',
                'regular_price' => 'required_if:type,simple',
                'code' => 'required_if:type,simple',
                'variation_options' => 'required_if:type,variable|unique_with_global',
                'categories' => 'required',
                'shipping.width' => 'required_with_all:shipping.enabled,code',
                'shipping.height' => 'required_with_all:shipping.enabled,code',
                'shipping.length' => 'required_with_all:shipping.enabled,code',
                'shipping.weight' => 'required_with_all:shipping.enabled,code',
            ]);

            if ($this->input('type') == 'simple' && in_array($this->input('type'), ['finite', 'bucket'])) {
                $rules['inventory_value'] = 'required';
            }

            if ($this->input('price_per_classification')) {
                foreach (\Settings::get('customer_classifications', []) as $key => $value) {
                    $classification_rules['classification_price.' . $key] = 'nullable|min:0|not_in:0';
                }
                $rules = array_merge($rules, $classification_rules);
            }
        }

        if ($this->isStore()) {
            $rules = $this->downloadableStoreRules($rules);

            $rules = array_merge($rules, [
                'slug' => 'max:191|unique:marketplace_products,slug'
            ]);
        }

        if ($this->isUpdate()) {
            $product = $this->route('product');

            $rules = $this->downloadableUpdateRules($rules, $product);

            $rules = array_merge($rules, [
                'slug' => 'max:191|unique:marketplace_products,slug,' . $product->id,
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        if ($this->isStore() || $this->isUpdate()) {
            $attributes = [
                'shipping.enabled' => 'shipping enabled',
                'shipping.width' => 'width',
                'shipping.height' => 'height',
                'shipping.length' => 'length',
                'shipping.weight' => 'weight',
                'code' => 'SKU code'
            ];

            foreach (\Settings::get('customer_classifications', []) as $key => $value) {
                $attributes['classification_price.' . $key] = $key;
            }

            $attributes = $this->downloadableAttributes($attributes);
        }

        return $attributes;
    }

    public function messages()
    {
        $messages['unique_with_global'] = trans('Marketplace::labels.product.option_cannot_global');
        return $this->downloadableMessages($messages);
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidatorInstance()
    {
        if ($this->isStore() || $this->isUpdate()) {
            $data = $this->all();

            if (isset($data['slug'])) {
                $data['slug'] = \Str::slug($data['slug']);
            }

            $data['is_featured'] = \Arr::get($data, 'is_featured', false);

            $data['properties'] = \Arr::get($data, 'properties', []);

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }
}

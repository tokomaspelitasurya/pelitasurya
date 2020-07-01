<?php

namespace Corals\Modules\Utility\Http\Requests\Webhook;

use Corals\Foundation\Http\Requests\BaseRequest;

class WebhookRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return user()->hasPermissionTo('Administrations::admin.utility') || isSuperUser();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}

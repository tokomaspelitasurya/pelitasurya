<?php

namespace Corals\Modules\Marketplace\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\Marketplace\Models\SKU;

class CheckoutRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

//        return user()->hasPermissionTo('Marketplace::cart.access');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $step = request()->route('step');

        $rules = [];

        if ($step == "billing-shipping-address") {
            $rules = array_merge($rules, [
                'billing_address.address_1' => 'required',
                'billing_address.city' => 'required',
                'billing_address.state' => 'required',
                'billing_address.country' => 'required',
                'billing_address.zip' => 'required',
            ]);
            if (\ShoppingCart::get('default')->getAttribute('enable_shipping')) {
                $rules = array_merge($rules, [
                    'shipping_address.address_1' => 'required',
                    'shipping_address.city' => 'required',
                    'shipping_address.state' => 'required',
                    'shipping_address.country' => 'required',
                    'shipping_address.zip' => 'required',
                ]);
            }

        } else if ($step == "shipping-method") {


            $cart_instances = \ShoppingCart::getInstances();

            foreach ($cart_instances as $instance) {


                $cart = \ShoppingCart::setInstance($instance);

                $shippable_items = \Shipping::getShippableItems($cart->getItems());
                if ($shippable_items) {

                    $rules = array_merge($rules, [
                        'selected_shipping_methods.' . $instance => 'required',
                    ]);
                }
            }

        } else if ($step == "select-payment") {
            $rules = array_merge($rules, [
                'checkoutToken' => 'required',
                'gateway' => 'required',
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];
        $product = request()->route('product');

        foreach ($product->properties ?? [] as $property) {
            $attributes = array_merge($attributes, [
                "properties.$property" => $property,
            ]);
        }

        foreach ($this->shipping_address ?? [] as $key => $value) {
            $attributes = array_merge($attributes, [
                "shipping_address.$key" => str_replace('_', ' ', \Str::title($key)),
            ]);
        }

        foreach ($this->billing_address ?? [] as $key => $value) {
            $attributes = array_merge($attributes, [
                "billing_address.$key" => str_replace('_', ' ', \Str::title($key)),
            ]);
        }

        return $attributes;
    }

    public function messages()
    {
        return [
            'checkoutToken.required' => trans('Marketplace::labels.checkout.please_enter_payment'),
            'selected_shipping_methods.*' => trans('Marketplace::labels.checkout.please_select_shipping'),
        ];
    }
}

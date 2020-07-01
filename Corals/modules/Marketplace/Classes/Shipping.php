<?php

namespace Corals\Modules\Marketplace\Classes;

use Corals\Modules\Marketplace\Models\Shipping as ShippingModel;
use Corals\Modules\Marketplace\Traits\ShippingTrait;


class Shipping
{
    use ShippingTrait;


    public function getAvailableShippingMethods($shipping_address, $shippable_items, $order_total, $store_id)
    {
        $country = $shipping_address['country'];

        $shipping_roles = ShippingModel::where(function ($query) use ($country) {
            $query->where('country', $country)
                ->orWhereNull('country');
        })->where(function ($query) use ($store_id) {
            $query->where('store_id', $store_id)
                ->orWhereNull('store_id');
        })->orderBy('exclusive', 'DESC')
            ->orderBy('priority', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        if ($shipping_roles->isEmpty()) {
            return [];
        }


        $applied_methods = [];
        $available_rates = [];
        $continue_shipping_scan = true;

        $total_weight = 0;
        $total_quantity = 0;

        foreach ($shippable_items as $shippable_item) {
            $total_quantity += $shippable_item->qty;
            $weight = $shippable_item->id->shipping['weight'] ?? $shippable_item->id->product->shipping['weight'];
            $total_weight += $shippable_item->qty * $weight;
        }

        //\Logger("Total Weight: " . $total_weight);
        //\Logger("Total Quantity: " . $total_quantity);
        //\Logger("Total Order: " . $order_total);


        foreach ($shipping_roles as $shipping_role) {
            try {
                if (!$continue_shipping_scan) {
                    continue;
                }
                if ($shipping_role->min_order_total) {
                    if ($order_total <= $shipping_role->min_order_total) {
                        continue;
                    }
                }

                if ($shipping_role->max_total_weight) {
                    if ($total_weight <= $shipping_role->max_total_weight) {
                        continue;
                    }
                }

                if ($shipping_role->min_total_quantity) {
                    if ($total_quantity < $shipping_role->min_total_quantity) {
                        continue;
                    }
                }

                if (!$this->isShippingMethodSupported($shipping_role->shipping_method)) {
                    continue;
                }

                if (in_array($shipping_role->name, $applied_methods)) {
                    continue;
                }

                $shipping_method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_role->shipping_method);

                $shipping_method->initialize($shipping_role->toArray());
                $shipping_method_rates = $shipping_method->getAvailableShipping($shipping_address, $shippable_items);
                if ($shipping_role->exclusive && (count($shipping_method_rates) > 0)) {
                    $available_rates = $shipping_method_rates;
                    $continue_shipping_scan = false;

                } else {
                    $available_rates = array_merge($shipping_method_rates, $available_rates);
                }

                $applied_methods[] = $shipping_role->name;
            } catch (\Exception $exception) {
                report($exception);
            }
        }
        return $available_rates;

    }


    public function getProviderName($selected_shipping)
    {
        list($shipping_method, $shipping_object,$shipping_rule_id) = explode('|', $selected_shipping);
        return $shipping_method;
    }

    public function track($order)
    {
        $provider = @$order['shipping']['shipping_provider'];
        if (!$provider) {
            return [];
        }
        $shipping_method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $provider);
        $shipping_method->initialize();
        return $shipping_method->track($order);

    }

    public function createShippingTransaction($selected_shipping)
    {
        $shipping_method = $selected_shipping->getProperty('shipping_method');

        $shipping_method = \App::make('Corals\\Modules\\Marketplace\\Classes\\Shippings\\' . $shipping_method);
        $shipping_method->initialize();

        $result = $shipping_method->createShippingTransaction($selected_shipping);
        return $result;
    }

    public function getShippingMethods()
    {
        $supported_shipping_methods = \Settings::get('supported_shipping_methods', []);
        return $supported_shipping_methods;
    }

    public function setShippingMethods($supported_shipping_methods)
    {
        \Settings::set('supported_shipping_methods', json_encode($supported_shipping_methods));

    }


    public function isShippingMethodSupported($shipping_methods)
    {
        return array_key_exists($shipping_methods, $this->getShippingMethods());
    }
}

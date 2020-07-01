<?php

namespace Corals\Modules\Marketplace\Classes\Shippings;

use Corals\Modules\Marketplace\Contracts\ShippingContract;

/**
 * Class Fixed.
 */
class Free implements ShippingContract
{


    public $rate;
    public $description;
    public $name;
    public $rule_id;

    /**
     * Fixed constructor.
     *
     * @param $code
     * @param $value
     * @param array $options
     */
    public function __construct($options = [])
    {


    }

    public function providerName()
    {
        return "Free";
    }

    /**
     * Fixed constructor.
     *
     * @param $shipping_role
     * @param $value
     * @param array $options
     */
    public function initialize($options = [])
    {
        $this->name = $options['name'] ?? '';
        $this->rule_id = $options['id'] ?? '';
        $this->rate = $options['rate'] ?? '';
        $this->description = $options['description'] ?? '';
    }

    /**
     * Gets the shipping Rates.
     *
     * @param $throwErrors boolean this allows us to capture errors in our code if we wish,
     * that way we can spit out why the coupon has failed
     *
     * @return array
     */
    public function getAvailableShipping($to_address, $shippable_items, $user = null)
    {

        $available_rates[$this->providerName() . '|' . $this->name . '|' . $this->rule_id] = [
            'provider' => $this->name,
            'description' => $this->description,
            'service' => '',
            'currency' => \Payments::admin_currency_code(),
            'amount' => '0.0',
            'shipping_rule_id' => $this->rule_id,
            'estimated_days' => ''
        ];

        return $available_rates;

    }


    public function createShippingTransaction($shipping_reference)
    {

        $shipping = [];

        $shipping['status'] = 'pending';
        $shipping['label_url'] = '';
        $shipping['tracking_number'] = '';

        return $shipping;
    }


    public function track($order)
    {
        try {
            $tracking_status = [];
            return $tracking_status;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

}

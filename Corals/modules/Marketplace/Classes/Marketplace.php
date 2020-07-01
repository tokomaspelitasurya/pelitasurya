<?php

namespace Corals\Modules\Marketplace\Classes;

use Carbon\Carbon;
use Corals\Foundation\Facades\CoralsForm;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\AttributeOption;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Payment\Payment;
use Corals\User\Models\User;
use Illuminate\Support\Arr;
use Money\Currencies\ISOCurrencies;
use Money\Currency;

class Marketplace
{
    public $gateway;

    /**
     * Marketplace constructor.
     * @param null $gateway_key
     * @param array $params
     * @throws \Exception
     */
    function __construct($gateway_key = null, array $params = [])
    {
        if ($gateway_key) {
            $gateway = Payment::create($gateway_key);

            $config = config('payment_' . strtolower($gateway_key));

            if (!$config) {
                throw new \Exception(trans('Marketplace::exception.misc.invalid_gateway'));
            }

            $this->gateway = $gateway;

            $this->gateway->setAuthentication();

            foreach ($params as $key => $value) {
                $this->gateway->setParameter($key, $value);
            }
        }

    }

    /**
     * @param Product $product
     * @return bool
     * @throws \Exception
     */
    public function createProduct(Product $product)
    {
        $prod_integration_id = $this->gateway->getGatewayIntegrationId($product);
        if ($prod_integration_id) {
            $message = trans('Marketplace::exception.misc.product_code_exist', ['arg' => $prod_integration_id]);
            throw new \Exception($message);
        }
        $parameters = $this->gateway->prepareProductParameters($product);

        $request = $this->gateway->createProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Product was created successful
            $product->setGatewayStatus($this->gateway->getName(), 'CREATED', null, $data['id']);

            return true;
        } else {
            // Create Product failed
            $message = trans('Marketplace::exception.misc.create_gateway', ['message' => $response->getMessage()]);

            $product->setGatewayStatus($this->gateway->getName(), 'FAILED', $message, null);

            throw new \Exception($message);
        }
    }

    /**
     * @param Product $product
     * @return mixed
     * @throws \Exception
     */
    public function updateProduct(Product $product)
    {

        $parameters = $this->gateway->prepareProductParameters($product);
        $request = $this->gateway->updateProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Product was updated successful
            $product->setGatewayStatus($this->gateway->getName(), 'UPDATED', null, $data['id']);

            return true;
        } else {
            // update Product failed
            $message = trans('Marketplace::exception.misc.update_gateway', ['message' => $response->getMessage()]);

            $product->setGatewayStatus($this->gateway->getName(), 'FAILED', $message);

            throw new \Exception($message);
        }
    }

    /**
     * @param Product $product
     * @param array $extra_params
     * @return bool
     * @throws \Exception
     */
    public function deleteProduct(Product $product, $extra_params = [])
    {
        $parameters = ['id' => $product->code];

        $request = $this->gateway->deleteProduct($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            //"Gateway deleteProduct was successful.\n";
            return true;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.delete_product', ['message' => $response->getMessage()]));
        }
    }

    /**
     * @param SKU $sku
     * @return mixed
     * @throws \Exception
     */
    public function createSKU(SKU $sku)
    {
        $parameters = $this->gateway->prepareSKUParameters($sku);

        $request = $this->gateway->createSKU($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // SKU was created successful
            $sku->setGatewayStatus($this->gateway->getName(), 'CREATED', null, $data['id']);

            return true;
        } else {
            // Create SKU failed
            $message = trans('Marketplace::exception.misc.create_gateway_sku', ['message' => $response->getMessage()]);

            $sku->update(['gateway_status' => 'failed', 'gateway_message' => $message]);
            $sku->setGatewayStatus($this->gateway->getName(), 'FAILED', $message, null);

            throw new \Exception($message);
        }
    }

    /**
     * @param SKU $sku
     * @return bool
     * @throws \Exception
     */
    public function updateSKU(SKU $sku)
    {
        $parameters = $this->gateway->prepareSKUParameters($sku);
        $request = $this->gateway->updateSKU($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $data = $response->getData();
            // SKU was updated successful
            $sku->setGatewayStatus($this->gateway->getName(), 'UPDATED', null, $data['id']);

            return true;
        } else {
            // update SKU failed
            $message = trans('Marketplace::exception.misc.update_gateway_sku', ['message' => $response->getMessage()]);

            $sku->setGatewayStatus($this->gateway->getName(), 'UPDATED', $message);

            throw new \Exception($message);
        }
    }

    /**
     * @param SKU $sku
     * @param array $extra_params
     * @return bool
     * @throws \Exception
     */
    public function deleteSKU(SKU $sku, $extra_params = [])
    {
        $parameters = ['id' => $sku->code];

        $request = $this->gateway->deleteSKU($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            //"Gateway deleteSKU was successful.\n";
            return true;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.delete_sku', ['message' => $response->getMessage()]));
        }
    }


    /**
     * @param User $user
     * @param $cartItems
     * @param $shipping_rate
     * @return mixed
     * @throws \Exception
     */
    public function createOrder(User $user, $cartItems, $shipping_rate)
    {
        $parameters = $this->gateway->prepareOrderParameters(null, $user, $cartItems, $shipping_rate);

        $request = $this->gateway->createOrder($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            // Order was created successful

            $order_id = $data['id'];
            return $order_id;
        } else {
            // Create Order failed
            $message = trans('Marketplace::exception.misc.create_order_failed', ['message' => $response->getMessage()]);

            throw new \Exception($message);
        }
    }

    /**
     * @param Order $order
     * @param User $user
     * @param $cart_items
     * @return bool
     * @throws \Exception
     */
    public function updateOrder(Order $order, User $user, $cart_items)
    {
        $parameters = $this->gateway->prepareOrderParameters($order, $user, $cart_items);

        $request = $this->gateway->updateOrder($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();
            $order = Order::where('code', $data['id'])->first();

            if (!$order) {
                throw new \Exception(trans('Marketplace::exception.misc.invalid_order_code', ['data' => $data['id']]));
            }
            $order->items()->delete();

            $order->update([
                'amount' => ($data['amount'] / 100),
                'currency' => $data['currency'],
                'status' => $data['status'],
                'shipping_methods' => $data['shipping_methods'],

            ]);

            $this->createOrderItems($data, $order);


            return true;
        } else {
            // update Order failed
            $message = trans('Marketplace::exception.misc.update_gateway_order_failed', ['message' => $response->getMessage()]);

            throw new \Exception($message);
        }
    }

    /**
     * @param $data
     * @param $order
     */
    protected function createOrderItems($data, $order)
    {

        $items = [];

        foreach ($data['items'] as $item) {
            $items[] = [
                'amount' => ($item['amount'] / 100),
                'quantity' => $item['quantity'],
                'description' => $item['description'],
                'sku_code' => $item['parent'],
                'type' => $item['type'],
            ];
        }

        $order->items()->createMany($items);
    }


    /**
     * @param Order $order
     * @param User $user
     * @param $checkoutDetails
     * @return bool
     * @throws \Exception
     */
    public function payOrders($orders, User $user, $checkoutDetails)
    {
        foreach ($orders as $order) {
            if (isset($order->billing['payment_status']) && ($order->billing['payment_status'] == 'paid')) {
                throw new \Exception(trans('Marketplace::exception.misc.order_already_paid'));
            }
        }


        \Actions::do_action('pre_marketplace_pay_order', $this->gateway, $orders, $user, $checkoutDetails);

        $parameters = $this->gateway->prepareCreateMultiOrderChargeParameters($orders, $user, $checkoutDetails);

        $request = $this->gateway->createCharge($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $payment_reference = $response->getChargeReference();
            \Actions::do_action('post_marketplace_pay_orders', $this->gateway, $orders, $user, $checkoutDetails);
            return $payment_reference;

        } else {
            // pay Order failed
            $message = 'pay Gateway Order Failed. ' . $response->getMessage();
            throw new \Exception($message);
        }


    }


    public function refundOrder(Order $order, $amount)
    {
        $parameters = $this->gateway->prepareCreateRefundParameters($order, $amount);

        $request = $this->gateway->refund($parameters);

        $response = $request->send();
        if ($response->isSuccessful()) {
            $refund_reference = $response->getTransactionReference();

            \Actions::do_action('post_marketplace_refund_order', $this->gateway, $order, $amount);

            return $refund_reference;

        } else {
            // refund Order failed
            $message = 'refund Gateway Order Failed. ' . $response->getMessage();
            throw new \Exception($message);
        }
    }


    public function setTransactions($invoice, $order)
    {
        $commission = 0;

        $storeOwner = $order->store->user;

        $vendor_require_subscription = \Settings::get('marketplace_general_vendor_require_subscription', false);

        if ($vendor_require_subscription) {
            $marketplaceSubscriptionProduct = \Settings::get('marketplace_general_subscription_product', '');
            $marketplaceCommisionFeatureId = \Settings::get('marketplace_general_commission_feature', '');

            if ($marketplaceSubscriptionProduct && $marketplaceCommisionFeatureId) {
                $storeSubscription = $storeOwner->currentSubscription($marketplaceSubscriptionProduct);
                $subscriptionCommision = $storeSubscription->plan->features()->where('feature_id', $marketplaceCommisionFeatureId)->first();
                $commission = $subscriptionCommision->pivot->value;
            }
        } else {
            $fixed_commission_percentage = \Settings::get('marketplace_general_fixed_commission_percentage', 0);
            if ($fixed_commission_percentage) {
                $commission = $fixed_commission_percentage;
            }
        }

        $order_amount_system_currency = \Payments::currency_convert($order->amount, $order->currency, \Payments::admin_currency_code(), false);;

        $transactions = [];
        if ($order->billing['payment_status'] == 'paid') {
            $transaction_status = 'completed';
        } else {
            $transaction_status = 'pending';

        }
        if ($commission) {
            $commissionAmount = ($order_amount_system_currency * $commission) / 100;

            $iso_currencies = new ISOCurrencies();
            $currency = new Currency($order->currency);

            if ($currency) {
                $decimals = $iso_currencies->subunitFor($currency);
                $commissionAmount = number_format((float)$commissionAmount, $decimals, '.', '');
            }

            if ($commissionAmount) {
                $transactions[] = [
                    'invoice_id' => $invoice->id,
                    'amount' => -1 * $commissionAmount,
                    'sourcable_id' => $order->id,
                    'sourcable_type' => get_class($order),
                    'transaction_date' => Carbon::now(),
                    'status' => $transaction_status,
                    'type' => 'commision',
                    'notes' => 'Commision Fee for order# ' . $order->id,
                ];
            }
        }

        $applied_shipping_item = $order->items()->where('type', 'Shipping')->first();

        if ($applied_shipping_item && $applied_shipping_item->amount > 0) {

            $shipping_rule = \Corals\Modules\Marketplace\Models\Shipping::findOrFail($applied_shipping_item->getProperty('shipping_rule_id'));

            if (!$shipping_rule->store_id) {
                $transactions[] = [
                    'invoice_id' => $invoice->id,
                    'amount' => -1 * $applied_shipping_item->amount,
                    'sourcable_id' => $order->id,
                    'sourcable_type' => get_class($order),
                    'transaction_date' => Carbon::now(),
                    'status' => $transaction_status,
                    'type' => 'shipping',
                    'notes' => 'Shipping Fee for order# ' . $order->id,
                ];
            }
        }

        $transactions[] = [
            'invoice_id' => $invoice->id,
            'paid_currency' => $order->currency,
            'paid_amount' => $order->amount,
            'reference' => $order->billing['payment_reference'],
            'amount' => $order_amount_system_currency,
            'sourcable_id' => $order->id,
            'status' => $transaction_status,
            'sourcable_type' => get_class($order),
            'transaction_date' => Carbon::now(),
            'type' => 'order_revenue',
            'notes' => 'Revenue from order# ' . $order->id,
        ];

        $storeOwner->transactions()->createMany($transactions);
    }

    /**
     * @return string
     */
    public function createOrderNumber()
    {
        // Get the last created order
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $number = 0;
        // We get here if there is no order at all
        // If there is no number set it to 0, which will be 1 at the end.
        if ($lastOrder) {
            $number = substr($lastOrder->order_number, 4);
        }

        // If we have ORD-000001 in the database then we only want the number
        // So the substr returns this 000001

        // Add the string in front and higher up the number.
        // the %05d part makes sure that there are always 6 numbers in the string.
        // so it adds the missing zero's when needed.

        return 'ORD-' . sprintf('%06d', intval($number) + 1);
    }

    public function getCategoriesRootList($objects = true)
    {
        $categories = Category::query();

        $categories = $categories->where(function ($parentQuery) {
            $parentQuery->whereNull('parent_id')
                ->orWhere('parent_id', 0);
        });

        if ($objects) {
            return $categories->get();
        } else {
            return $categories->pluck('name', 'id');
        }
    }

    /**
     * @param array $selected
     * @param array $excluded
     * @return array|mixed
     */
    public function getCategoriesList($selected = [], $excluded = [])
    {
        $categories = Category::query();

        $categoriesResult = [];

        $excluded = Arr::wrap($excluded);

        if (!empty($excluded)) {
            $categories->whereNotIn('id', $excluded);
        }

        $categories = $categories->where(function ($parentQuery) {
            $parentQuery->whereNull('parent_id')
                ->orWhere('parent_id', 0);
        })->get();

        foreach ($categories as $category) {
            $categoriesResult = $this->buildTree($categoriesResult, $category);
        }

        $selected = Arr::wrap($selected);

        foreach ($selected as $id) {
            $this->setTreeSelectedOptions($categoriesResult, $id);
        }

        return $categoriesResult;
    }

    /**
     * @param $options
     * @param $id
     */
    protected function setTreeSelectedOptions(&$options, $id)
    {
        foreach ($options as $index => $option) {
            if ($options[$index]['id'] == $id) {
                $options[$index]['selected'] = "true";
                return;
            }
            if (!empty($options[$index]['inc'])) {
                $this->setTreeSelectedOptions($options[$index]['inc'], $id);
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getAttributesList()
    {
        $attributes = Attribute::all()->pluck('label', 'id');
        return $attributes;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getBrandsList()
    {
        $brands = Brand::all()->pluck('name', 'id');
        return $brands;
    }

    /**
     * @param $categories
     * @param $category
     * @param bool $isAChild
     * @return mixed
     */
    protected function appendCategory($categories, $category, $isAChild = false)
    {
        if ($category->hasChildren()) {
            $categories[$category->name] = [];
            foreach ($category->children as $child) {
                $categories[$category->name] = $this->appendCategory($categories[$category->name], $child, true);
            }
        } elseif ($isAChild || $category->isRoot()) {
            $categories[$category->id] = $category->name;
        }

        return $categories;
    }

    /**
     * @param $categories
     * @param $category
     * @param bool $isAChild
     * @return mixed
     */
    protected function buildTree($categories, $category)
    {
        $categories[$category->id] = ['id' => $category->id, 'text' => $category->name, 'inc' => []];

        if ($category->hasChildren()) {
            foreach ($category->children as $child) {
                $categories[$category->id]['inc'] = $this->buildTree($categories[$category->id]['inc'], $child);
            }
        }

        if (empty($categories[$category->id]['inc'])) {
            unset($categories[$category->id]['inc']);
        }

        return array_values($categories);
    }

    /**
     * @param bool $objects
     * @param null $status
     * @return mixed
     */
    public function getTagsList($objects = false, $status = null)
    {
        $tags = Tag::whereNotNull('id');

        if ($status) {
            $tags = $tags->where('status', $status);
        }

        if ($objects) {
            $tags = $tags->get();
        } else {
            $tags = $tags->pluck('name', 'id');
        }

        return $tags;
    }

    /**
     * @param Order $order
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function createPaymentToken($amount, $currency, $params)
    {


        $description = "Payment for Vendors";

        $parameters = $this->gateway->preparePaymentTokenParameters($amount, $currency, $description, $params);
        $request = $this->gateway->purchase($parameters);
        $response = $request->send();


        if ($response->isSuccessful()) {
            $token = $response->getPaymentTokenReference();
            return $token;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.gateway_create_payment', ['data' => $response->getDataText()]));
        }
    }


    /**
     * @param Order $order
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function checkPaymentToken($params)
    {


        $parameters = $this->gateway->prepareCheckPaymentTokenParameters($params);

        if ($this->gateway->getConfig('require_token_confirm')) {

            $request = $this->gateway->confirmPaymentToken($parameters);
        } else {
            $request = $this->gateway->checkPaymentToken($parameters);
        }

        $response = $request->send();

        if ($response->isSuccessful()) {
            $token = $response->getPaymentTokenReference();
            return $token;
        } else {
            throw new \Exception(trans('Marketplace::exception.misc.gateway_check_payment_token', ['data' => $response->getDataText()]));
        }
    }


    /**
     * @param Order $order
     */
    public function deductFromInventory(Order $order)
    {
        try {
            foreach ($order->items as $order_item) {
                if ($order_item->type == "Product") {
                    $sku = SKU::where('code', $order_item->sku_code)->first();
                    if ($sku && $sku->inventory == "finite") {
                        $sku->inventory_value = $sku->inventory_value - 1;
                        $sku->save();
                    }
                }
            }
        } catch (\Exception $exception) {
            log_exception($exception, 'Inventory', 'Deduct');
        }
    }

    /**
     * @param Order $order
     * @param User $user
     */
    public function addContentAccess(Order $order, User $user)
    {
        try {
            $posts = [];
            foreach ($order->items as $order_item) {
                if ($order_item->type == "Product") {
                    $sku = SKU::where('code', $order_item->sku_code)->first();
                    if ($sku) {
                        $product_posts = $sku->product->posts;
                        if (count($product_posts)) {
                            foreach ($product_posts as $product_post) {
                                $posts[] = [
                                    'content_id' => $product_post->id,
                                    'postable_id' => $user->id,
                                    'postable_type' => User::class,
                                    'sourcable_id' => $order->id,
                                    'sourcable_type' => Order::class
                                ];
                            }
                        }
                    }
                }
            }
            $user->posts()->sync($posts, false);
        } catch (\Exception $exception) {
            log_exception($exception, 'Inventory', 'Deduct');
        }
    }

    /**
     * @param $field
     * @param null $sku
     * @param array $attributes
     * @return string
     */
    public function renderAttribute($field, $sku = null, $attributes = [])
    {
        $value = null;

        $asFilter = \Arr::pull($attributes, 'as_filter', false);

        if ($sku) {
            $options = $sku->options()->where('attribute_id', $field->id)->get();
            if ($options->count() > 1) {
                // in case of multiple type
                $value = AttributeOption::whereIn('id', $options->pluck('number_value')->toArray())
                    ->pluck('id')->toArray();
            } elseif ($option = $options->first()) {
                $value = optional($option)->value;
            }
        }

        $input = '';

        switch ($field->type) {
            case 'label':
                unset($attributes['class']);
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $attributes);
                break;
            case 'number':
            case 'date':
            case 'text':
            case 'textarea':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $asFilter ? false : $field->required, $value, $attributes);
                break;
            case 'checkbox':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $value, 1, $attributes);
                break;
            case 'radio':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $asFilter ? false : $field->required, $field->options->pluck('option_display', 'id')->toArray(), $value, $attributes);
                break;
            case 'select':
                $input = CoralsForm::{$field->type}('options[' . $field->id . ']', $field->label, $field->options->pluck('option_display', 'id')->toArray(), $asFilter ? false : $field->required, $value, $attributes, 'select2');
                break;
            case 'multi_values':
                $attributes = array_merge(['class' => 'select2-normal', 'multiple' => true], $attributes);
                $input = CoralsForm::select('options[' . $field->id . '][]', $field->label, $field->options->pluck('option_display', 'id')->toArray(), $asFilter ? false : $field->required, $value, $attributes, 'select2');
                break;
        }

        return $input;
    }


}

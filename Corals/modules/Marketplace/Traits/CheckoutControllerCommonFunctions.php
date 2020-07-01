<?php

namespace Corals\Modules\Marketplace\Traits;

use Corals\Modules\Marketplace\Classes\Coupons\Advanced;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\Http\Requests\CheckoutRequest;
use Corals\Modules\Marketplace\Models\Coupon;
use Corals\Modules\Marketplace\Models\Order;
use Corals\Modules\Marketplace\Services\CheckoutService;
use Corals\Modules\Payment\Payment;
use Corals\User\Models\User;
use Illuminate\Http\Request;

trait CheckoutControllerCommonFunctions
{
    /**
     * CartController constructor.
     */
    protected $shipping;

    /**
     * @param $step
     * @param Request $request
     * @return array|bool|string
     * @throws \Throwable
     */
    public function checkoutStep($step, Request $request)
    {
        try {
            switch ($step) {
                case 'checkout-method':
                    return view('Marketplace::checkout.partials.checkout_method')->render();

                case 'cart-details':
                    foreach (\ShoppingCart::getAllInstanceItems() as $item) {
                        if (!$item->id->isAvailable($item->qty)) {
                            \ShoppingCart::removeItem($item->getHash());
                        }
                    }

                    $coupon_code = \ShoppingCart::get('default')->getAttribute('coupon_code');

                    return view('Marketplace::checkout.partials.cart_items')->with(compact('coupon_code'))->render();
                case 'billing-shipping-address':
                    $enable_shipping = \ShoppingCart::get('default')->getAttribute('enable_shipping');

                    $billing_address = \ShoppingCart::get('default')->getAttribute('billing_address') ?? [];

                    if (!$billing_address) {
                        if (user() && user()->address('billing')) {
                            $billing_address = user()->address('billing');
                        }
                    }

                    $shipping_address = \ShoppingCart::get('default')->getAttribute('shipping_address') ?? [];

                    if (!$shipping_address) {
                        if (user() && user()->address('shipping')) {
                            $shipping_address = user()->address('shipping');
                        }
                    }

                    if (user()) {
                        $shipping_address['first_name'] = user()->name;
                        $shipping_address['last_name'] = user()->last_name;
                        $shipping_address['email'] = user()->email;

                        $billing_address['first_name'] = user()->name;
                        $billing_address['last_name'] = user()->last_name;
                        $billing_address['email'] = user()->email;
                    }

                    return view('Marketplace::checkout.partials.address')->with(compact('shipping_address', 'enable_shipping', 'billing_address'))->render();
                case 'select-payment':
                    $gateway = null;
                    $gateway_name = $request->get('gateway_name');
                    $billing = [];
                    $enable_shipping = \ShoppingCart::get('default')->getAttribute('enable_shipping');
                    $billing_address = \ShoppingCart::get('default')->getAttribute('billing_address');

                    $cart_instances = \ShoppingCart::getInstances();

                    $user = user();

                    $orders = [];

                    foreach ($cart_instances as $instance) {
                        $cart = \ShoppingCart::setInstance($instance);

                        $cart_items_count = $cart->count();

                        if ($cart_items_count > 0) {
                            $coupon_code = $cart->getAttribute('coupon_code');

                            $billing['billing_address'] = $billing_address;

                            $order = null;

                            if ($cart->getAttribute('order_id')) {
                                $order = Order::find($cart->getAttribute('order_id'));
                            }

                            if ($order) {
                                $order->items()->delete();

                                $order->update([
                                    'amount' => \Payments::currency_convert($cart->total(false)),
                                    'billing' => $billing,
                                    'currency' => \Payments::session_currency(),
                                    'status' => 'pending',
                                ]);
                            } else {
                                $order = Order::create([
                                    'amount' => \Payments::currency_convert($cart->total(false)),
                                    'currency' => \Payments::session_currency(),
                                    'order_number' => \Marketplace::createOrderNumber(),
                                    'billing' => $billing,
                                    'status' => 'pending',
                                    'store_id' => $instance,
                                    'user_id' => $user ? $user->id : null,
                                ]);

                                $cart->setAttribute('order_id', $order->id);
                            }

                            $items = [];

                            foreach ($cart->getItems() as $item) {
                                $items[] = [
                                    'amount' => \Payments::currency_convert($item->price),
                                    'quantity' => $item->qty,
                                    'description' => $item->id->product->name . ' - ' . $item->id->code,
                                    'sku_code' => $item->id->code,
                                    'type' => 'Product',
                                    'item_options' => ['product_options' => $item->product_options]
                                ];
                            }

                            if ($enable_shipping && $cart->getAttribute('selected_shipping_method')) {
                                $shipping_rates = $cart->getAttribute('shipping_rates');
                                $selected_shipping_method = $cart->getAttribute('selected_shipping_method');
                                $selected_shipping = $shipping_rates[$selected_shipping_method];
                                $shipping_methods = $selected_shipping['shipping_class'];
                                $shipping_description = $selected_shipping['provider'] . ' - ' . $selected_shipping['service'] . ' Shipping';

                                $shipping_properties = ['shipping_rule_id' => $selected_shipping['shipping_rule_id'], 'shipping_method' => $shipping_methods];


                                $items[] = [
                                    'amount' => \Payments::currency_convert($selected_shipping['amount'], $selected_shipping['currency']),
                                    'quantity' => 1,
                                    'description' => $shipping_description,
                                    'sku_code' => '',
                                    'type' => 'Shipping',
                                    'properties' => $shipping_properties,
                                ];

                                if ($cart->getAttribute('shipping_description')) {

                                    $cart->removeFee($cart->getAttribute('shipping_description'));
                                }
                                $cart->addFee($shipping_description, $selected_shipping['amount']);
                                $cart->setAttribute('shipping_description', $shipping_description);

                                $order->amount = $cart->total(false);
                                $order->save();
                            }

                            $order_tax = $cart->taxTotal(false);

                            if ($order_tax) {
                                $items[] = [
                                    'amount' => \Payments::currency_convert($order_tax),
                                    'quantity' => 1,
                                    'description' => 'Sales Tax',
                                    'sku_code' => 'tax',
                                    'type' => 'Tax',
                                ];
                            }

                            $discount = $cart->totalDiscount(false);

                            if ($discount > 0) {
                                $coupon_code;
                                $items[] = [
                                    'amount' => -1 * $discount,
                                    'quantity' => 1,
                                    'description' => 'Discount Coupon ',
                                    'sku_code' => $coupon_code,
                                    'type' => 'Discount',
                                ];
                            }

                            $order->items()->createMany($items);

                            //save amount again after addons calculations

                            $order->amount = \Payments::currency_convert($cart->total(false));

                            $order->save();

                            $orders[] = $order;
                        }
                    }

                    if (!$gateway_name) {
                        $available_gateways = \Payments::getAvailableGateways();
                        foreach ($available_gateways as $gateway_key => $available_gateway) {
                            $marketplace = new Marketplace($gateway_key);
                            if (!$marketplace->gateway->getConfig('support_marketplace')) {
                                unset($available_gateways[$gateway_key]);
                            }
                        }
                        if (count($available_gateways) == 1) {
                            $gateway_name = key($available_gateways);
                        }
                    }


                    if ($gateway_name) {
                        $marketplace = new Marketplace($gateway_name);
                        $gateway = $marketplace->gateway;
                    }

                    //save amount again after additinal charge calculations
                    $amount = \ShoppingCart::totalAllInstances(false);
                    $can_redeem = false;

                    if (\Settings::get('marketplace_checkout_points_redeem_enable', true)) {

                        $points_needed = \Referral::getPointsNeedforAmount($amount);

                        $available_points_blanace = \Referral::getPointsBalance(user());

                        if ($available_points_blanace > $points_needed) {
                            $can_redeem = true;
                        }
                    }
                    return view('Marketplace::checkout.partials.payment')->with(compact('gateway', 'can_redeem', 'points_needed', 'available_points_blanace', 'amount', 'available_gateways', 'orders'))->render();
                    break;
                case
                'shipping-method':
                    $shipping_address = \ShoppingCart::get('default')->getAttribute('shipping_address');

                    $cart_instances = \ShoppingCart::getInstances();

                    $shipping_methods = [];
                    $shippable_items = [];

                    foreach ($cart_instances as $instance) {
                        $cart = \ShoppingCart::setInstance($instance);

                        if ($cart->getAttribute('shipping_description')) {
                            $cart->removeFee($cart->getAttribute('shipping_description'));
                        }

                        if ($cart->count() > 0) {
                            $cartItems = $cart->getItems();
                            $cartTotal = $cart->total(false);
                            $store_shippable_items = \Shipping::getShippableItems($cartItems);

                            if (count($store_shippable_items) > 0) {

                                $shipping_rates = \Shipping::getAvailableShippingMethods($shipping_address, $store_shippable_items, $cartTotal, $instance);
                                $cart->setAttribute('shipping_rates', $shipping_rates);

                                if (is_array($shipping_rates)) {
                                    foreach ($shipping_rates as $key => $rate) {
                                        $label = $rate['provider'];

                                        if ($rate['service']) {
                                            $label .= " " . $rate['service'];
                                        }

                                        if ($rate['amount']) {
                                            $label .= ' : <span class="text-info">' . \Payments::currency_convert($rate['amount'], $rate['currency'], null, true) . '</span>';
                                        }

                                        if ($rate['estimated_days']) {
                                            $label .= ', Estimated Delivery : <span class="text-info">' . $rate['estimated_days'] . ' Day(s) </span>';
                                        }

                                        if ($rate['description']) {
                                            $label .= '<br><small>' . $rate['description'] . '</small>';
                                        }

                                        $shipping_methods[$instance][$key] = $label;
                                    }
                                }

                                if (!isset($shipping_methods[$instance])) {
                                    $shipping_methods[$instance] = [];
                                }

                                foreach ($store_shippable_items as $store_shippable_item) {
                                    $shippable_items[$instance][$store_shippable_item->id->product->id] = $store_shippable_item->id->product->name;
                                }
                            }
                        }
                    }
                    return view('Marketplace::checkout.partials.shipping_methods')->with(compact('shipping_methods', 'shippable_items'))->render();
                    break;
                case
                'order-review':
                    $cart_instances = \ShoppingCart::getInstances();

                    $orders = [];

                    foreach ($cart_instances as $instance) {
                        $cart = \ShoppingCart::setInstance($instance);

                        if ($cart->getAttribute('order_id')) {
                            $order = Order::find($cart->getAttribute('order_id'));
                            $orders[] = $order;
                        }
                    }

                    return view('Marketplace::checkout.partials.order_review')->with(['orders' => $orders])->render();
                    break;
                default:
                    return false;
            }
        } catch (\Exception $exception) {
            log_exception($exception, 'CheckOutController', 'checkoutStep', null, true);
        }
    }

    /**
     * @param $step
     * @param CheckoutRequest $request
     */
    public function saveCheckoutStep($step, CheckoutRequest $request)
    {
        try {
            switch ($step) {
                case 'cart-details':
                    \ShoppingCart::get('default')->removeAttribute('coupon_code');
                    \ShoppingCart::removeCoupons();

                    if ($request->input('coupon_code')) {
                        $coupon_code = $request->input('coupon_code');
                        $coupon = Coupon::where('code', $coupon_code)->first();
                        if (!$coupon) {
                            throw new \Exception(trans('Marketplace::exception.checkout.invalid_coupon',
                                ['code' => $coupon_code]));
                        }
                        $coupon_class = new Advanced($coupon_code, $coupon, []);

                        $coupon_store_cart = \ShoppingCart::get($coupon->store_id);

                        $coupon_class->validate($coupon_store_cart, true);


                        $coupon_store_cart->addCoupon($coupon_class);
                        \ShoppingCart::get('default')->setAttribute('coupon_code', $coupon_code);
                    }
                    break;
                case 'billing-shipping-address':
                    $shipping_address = $request->input('shipping_address');
                    $billing_address = $request->input('billing_address');

                    if (\Settings::get('marketplace_tax_calculate_tax')) {
                        if ($shipping_address) {
                            $this->calculateCartTax($shipping_address);
                        } elseif ($billing_address) {
                            $this->calculateCartTax($billing_address);
                        }
                    }

                    if ($request->input('save_billing')) {
                        user()->saveAddress($billing_address, 'billing');
                    }
                    if ($request->input('save_shipping')) {
                        user()->saveAddress($shipping_address, 'shipping');
                    }

                    \ShoppingCart::get('default')->setAttribute('billing_address', $billing_address);
                    \ShoppingCart::get('default')->setAttribute('shipping_address', $shipping_address);
                    break;
                case 'select-payment':
                    $checkoutToken = $request->input('checkoutToken');
                    $gateway = $request->input('gateway');
                    \ShoppingCart::get('default')->setAttribute('checkoutToken', $checkoutToken);
                    \ShoppingCart::get('default')->setAttribute('gateway', $gateway);
                    break;
                case 'shipping-method':
                    $selected_shipping_methods = $request->input('selected_shipping_methods');
                    foreach ($selected_shipping_methods as $instance => $selected_shipping_method) {
                        $cart = \ShoppingCart::setInstance($instance);
                        $cart->setAttribute('selected_shipping_method', $selected_shipping_method);
                    }
                    break;
            }

            echo json_encode(['action' => 'nextCheckoutStep', 'lastSuccessStep' => '#' . $step]);
        } catch (\Exception $exception) {
            log_exception($exception, 'CheckOutController', 'saveCheckoutStep', null, true);
        }
    }

    /**
     * @param $gateway_name
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function gatewayPayment($gateway_name)
    {
        try {
            $marketplace = new Marketplace($gateway_name);
            $gateway = $marketplace->gateway;

            //save amount again after additional charge calculations
            $amount = \ShoppingCart::totalAllInstances(false);

            $view = $gateway->getPaymentViewName('marketplace');
            $action = '/checkout/step/select-payment';
            return view($view)->with(compact('gateway', 'action', 'amount'));
        } catch (\Exception $exception) {
            log_exception($exception, 'CartController', 'card', null, true);
        }
    }

    /**
     * @param $gateway
     * @param Request $request
     * @return mixed
     */
    public function gatewayPaymentToken($gateway, Request $request)
    {
        try {

            $params = $request->all();
            $amount = \ShoppingCart::totalAllInstances(false);
            $currency = \Payments::session_currency();
            $marketplace = new Marketplace($gateway);
            $token = $marketplace->createPaymentToken($amount, $currency, $params);
            return $token;
        } catch (\Exception $exception) {
            log_exception($exception, 'CartController', 'generatePaymentToken');
        }
    }


    /**
     * @param $gateway
     * @param Request $request
     * @return false|mixed|string
     */
    public function gatewayCheckPaymentToken($gateway,  Request $request)
    {
        $params = $request->all();

        try {
            $marketplace = new Marketplace($gateway);
            return $marketplace->checkPaymentToken($params);
        } catch (\Exception $exception) {
            log_exception($exception, 'CartController', 'checkPaymentToken');
            return json_encode(['status' => 'error', 'error' => $exception->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|mixed
     */
    public function doCheckout(Request $request)
    {
        $checkoutService = new CheckoutService();

        $checkoutToken = \ShoppingCart::get('default')->getAttribute('checkoutToken');

        $gateway = \ShoppingCart::get('default')->getAttribute('gateway');

        $shipping_address = \ShoppingCart::get('default')->getAttribute('shipping_address');

        $order_ids = $request->get('order_ids');

        $orders = [];

        foreach ($order_ids as $order_id) {
            $order = Order::find($order_id);
            $orders[] = $order;
        }

        $user = user() ?? new User();

        $billing_address = \ShoppingCart::get('default')->getAttribute('billing_address');

        $all_cart_items = \ShoppingCart::getAllInstanceItems();

        try {
            if ((count($all_cart_items) > 0) && $checkoutToken) {
                if ($gateway == "RedeemPoints") {
                    $payment_status = "paid";
                    $order_status = "processing";
                    $payment_name = "Redeem Points";
                    $amount = \ShoppingCart::totalAllInstances(false);
                    $points_needed = \Referral::getPointsNeedforAmount($amount);
                    $payment_reference = $checkoutToken;

                    \Referral::deductFromBalance($user, $points_needed);
                } else {
                    $payment_gateway = Payment::create($gateway);

                    if ($payment_gateway->getConfig('offline_management')) {
                        $payment_status = "pending";
                        $order_status = "pending";
                        $payment_reference = $checkoutToken;
                    } else {
                        $payment_reference = $this->payGatewayOrders($orders, $user, ['token' => $checkoutToken, 'gateway' => $gateway]);
                        $payment_status = "paid";
                        $order_status = "processing";
                    }

                    $payment_name = $payment_gateway->getName();
                }

                foreach ($orders as $order) {
                    $billing = $order->billing;
                    $billing['payment_reference'] = $payment_reference;
                    $billing['gateway'] = $payment_name;
                    $billing['payment_status'] = $payment_status;

                    $order->update([
                        'status' => $order_status,
                        'billing' => $billing,
                    ]);

                    $invoice = $checkoutService->generateOrderInvoice($order, $payment_status, $user, $billing_address);

                    $checkoutService->setOrderShippingDetails($order, $shipping_address);

                    $checkoutService->orderFulfillment($order, $invoice, $user);

                    \ShoppingCart::destroyAllCartInstances();
                }
            }

            flash('Order has been successfully placed')->success();

            return redirectTo($this->urlPrefix . 'checkout/order-success/' . $order->hashed_id);
        } catch (\Exception $exception) {
            log_exception($exception, 'CheckOutController', 'doCheckout');
        }

        return redirectTo($this->urlPrefix . 'checkout');
    }

    /**
     * @param $orders
     * @param User $user
     * @param $checkoutDetails
     * @return bool
     * @throws \Exception
     */
    protected function payGatewayOrders($orders, User $user, $checkoutDetails)
    {
        return $this->payGatewayOrdersSend($orders, $user, $checkoutDetails);
    }

    /**
     * @param $orders
     * @param User $user
     * @param $checkoutDetails
     * @return bool
     * @throws \Exception
     */
    protected function payGatewayOrdersSend($orders, User $user, $checkoutDetails)
    {
        $Marketplace = new Marketplace($checkoutDetails['gateway']);

        $payment_reference = $Marketplace->payOrders($orders, $user, $checkoutDetails);
        return $payment_reference;
    }

    public function calculateCartTax($address)
    {
        $cart_items = \ShoppingCart::getAllInstanceItems();

        foreach ($cart_items as $cart_item) {
            $itemHash = $cart_item->getHash();
            $taxes = \Payments::calculateTax($cart_item->id->product, $address);

            $tax_rate = 0;

            foreach ($taxes as $tax) {
                $tax_rate += $tax['rate'];
            }
            \ShoppingCart::updateItem($itemHash, 'tax', $tax_rate);
        }
    }
}

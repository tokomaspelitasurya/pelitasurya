<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Order;
use Corals\User\Models\User;

class OrderPolicy extends BasePolicy
{
    /**
     * @param User $user
     * @param Order $order
     * @return bool
     */
    public function refund(User $user, Order $order)
    {
        if ($user->cant('Marketplace::order.update_payment_details')) {
            return false;
        }

        $payment_status = $order->billing['payment_status'] ?? '';

        if ($payment_status && $payment_status != 'refunded' && $order->status != 'canceled') {
            return true;
        }

        return false;
    }
}

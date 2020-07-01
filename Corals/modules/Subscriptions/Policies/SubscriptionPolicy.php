<?php

namespace Corals\Modules\Subscriptions\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\User\Models\User;
use Corals\Modules\Subscriptions\Models\Subscription;

class SubscriptionPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.subscription';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Subscriptions::subscription.view')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Subscriptions::subscription.create');
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function update(User $user, Subscription $subscription)
    {
        if ($user->can('Subscriptions::subscription.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Subscription $subscription
     * @return bool
     */
    public function destroy(User $user, Subscription $subscription)
    {
        if ($user->can('Subscriptions::subscription.delete')) {
            return true;
        }
        return false;
    }

}

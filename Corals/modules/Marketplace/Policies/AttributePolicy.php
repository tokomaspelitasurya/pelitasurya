<?php

namespace Corals\Modules\Marketplace\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\User\Models\User;

class AttributePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.marketplace';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('Marketplace::attribute.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('Marketplace::attribute.create');
    }

    /**
     * @param User $user
     * @param Attribute $attribute
     * @return bool
     */
    public function update(User $user, Attribute $attribute)
    {
        return $user->can('Marketplace::attribute.update');
    }

    /**
     * @param User $user
     * @param Attribute $attribute
     * @return bool
     */
    public function destroy(User $user, Attribute $attribute)
    {
        return $user->can('Marketplace::attribute.delete');
    }

}

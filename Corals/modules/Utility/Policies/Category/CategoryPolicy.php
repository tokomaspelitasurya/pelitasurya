<?php

namespace Corals\Modules\Utility\Policies\Category;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\Utility\Models\Category\Category;
use Corals\User\Models\User;

class CategoryPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.utility';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        if ($user->can('Utility::category.view')) {
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
        return $user->can('Utility::category.create');
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function update(User $user, Category $category)
    {
        if ($user->can('Utility::category.update')) {
            return true;
        }
        return false;
    }

    /**
     * @param User $user
     * @param Category $category
     * @return bool
     */
    public function destroy(User $user, Category $category)
    {
        if ($user->can('Utility::category.delete')) {
            return true;
        }
        return false;
    }

}

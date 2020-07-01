<?php

namespace Corals\Foundation\Policies;

class BasePolicy
{
    /**
     * @var array
     */
    protected $skippedAbilities = [];

    /**
     * @var string
     */
    protected $administrationPermission = '';

    /**
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
    {
        if (in_array($ability, $this->skippedAbilities)) {
            return null;
        }

        if ((!empty($this->administrationPermission) && $user->hasPermissionTo($this->administrationPermission)) || isSuperUser($user)) {
            return true;
        }

        return null;
    }

}

<?php

namespace Corals\Modules\Utility\Facades\ListOfValue;

use Illuminate\Support\Facades\Facade;

/**
 * Class ListOfValues
 * @package Corals\Modules\Utility\Facades\ListOfValue
 * @method static getParents($module = null, $objects = false, $status = null)
 * @method static get($parentCode, $status = 'active', $objects = false)
 * @method static getLOVByCode($parentCode, $code, $default = null, $object = false)
 */
class ListOfValues extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \Corals\Modules\Utility\Classes\ListOfValue\ListOfValueManager::class;
    }
}

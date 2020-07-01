<?php

namespace Corals\Modules\Utility\Classes\ListOfValue;

use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Illuminate\Support\Str;

class ListOfValueManager
{
    /**
     * @param null $module
     * @param bool $objects
     * @param null $status
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getParents($module = null, $objects = false, $status = null)
    {
        $listOfValues = ListOfValue::query()->whereNull('parent_id');

        if (!is_null($module)) {
            $listOfValues = $listOfValues->withModule($module);
        }

        if ($status) {
            $listOfValues = $listOfValues->where('status', $status);
        }

        $listOfValues = $listOfValues->orderBy('display_order')->get();

        if ($objects) {
            return $listOfValues;
        }

        $listOfValuesList = [];

        foreach ($listOfValues as $listOfValue) {
            $listOfValuesList [$listOfValue->id] = sprintf("%s %s", Str::limit($listOfValue->value, 30), $listOfValue->module ?: '');
        }

        return $listOfValuesList;
    }

    /**
     * @param $parentCode
     * @param bool $useCode
     * @param string $status
     * @param bool $objects
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get($parentCode, $useCode = true, $status = 'active', $objects = false)
    {
        $listOfValues = ListOfValue::query()
            ->join('utility_list_of_values as parent', 'parent.id', 'utility_list_of_values.parent_id')
            ->where('parent.code', $parentCode)
            ->select('utility_list_of_values.*');

        if ($status) {
            $listOfValues = $listOfValues->where('utility_list_of_values.status', $status);
        }

        $listOfValues = $listOfValues->orderBy('display_order')->get();

        if ($objects) {
            return $listOfValues;
        }

        $listOfValuesList = [];

        $codeColumn = $useCode ? 'code' : 'id';

        foreach ($listOfValues as $listOfValue) {
            $listOfValuesList [$listOfValue->{$codeColumn}] = $listOfValue->label ?? $listOfValue->value;
        }

        return $listOfValuesList;
    }

    /**
     * @param $parentCode
     * @param $code
     * @param string $default
     * @param bool $object
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getLOVByCode($parentCode, $code, $default = null, $object = false)
    {
        $listOfValue = ListOfValue::query()
            ->join('utility_list_of_values as parent', 'parent.id', 'utility_list_of_values.parent_id')
            ->where('parent.code', $parentCode)
            ->where('utility_list_of_values.code', $code)
            ->select('utility_list_of_values.*')->first();

        if ($object) {
            return $listOfValue;
        } else {
            return optional($listOfValue)->value ?? $default;
        }
    }
}

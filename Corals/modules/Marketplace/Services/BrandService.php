<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;

class BrandService extends BaseServiceClass
{
    protected $excludedRequestParams = ['thumbnail', 'clear'];

    public function postStoreUpdate($request, $additionalData = [])
    {
        $brand = $this->model;

        if ($request->has('clear') || $request->hasFile('thumbnail')) {
            $brand->clearMediaCollection($brand->mediaCollectionName);
        }

        if ($request->hasFile('thumbnail') && !$request->has('clear')) {
            $brand->addMedia($request->file('thumbnail'))
                ->withCustomProperties(['root' => 'user_' . user()->hashed_id])
                ->toMediaCollection($brand->mediaCollectionName);
        }
    }
}

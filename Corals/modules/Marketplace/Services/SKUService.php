<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\Traits\DownloadableController;

class SKUService extends BaseServiceClass
{
    use DownloadableController;

    protected $excludedRequestParams = ['options', 'image', 'clear', 'downloads_enabled', 'downloads', 'cleared_downloads'];

    protected function postStoreUpdate($request, $additionalData)
    {
        $sku = $this->model;

        if ($request->has('clear') || $request->hasFile('image')) {
            $sku->clearMediaCollection('marketplace-sku-image');
        }

        if ($request->hasFile('image') && !$request->has('clear')) {
            $sku->addMedia($request->file('image'))->withCustomProperties(['root' => 'user_' . user()->hashed_id])->toMediaCollection('marketplace-sku-image');
        }

        $this->createOptions($request, $sku);

        $this->handleDownloads($request, $sku);
    }

    protected function createOptions($request, $sku)
    {
        $sku->options()->delete();

        $options = [];

        if (isset($request->options)) {
            foreach ($request->options as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $value_option) {
                        $options[] = [
                            'attribute_id' => $key,
                            'value' => $value_option
                        ];
                    }
                } else {
                    $options[] = [
                        'attribute_id' => $key,
                        'value' => $value
                    ];
                }
            }

            $sku->options()->createMany($options);
        }
    }

    /**
     * @param $request
     * @param $model
     * @throws \Exception
     */
    public function destroy($request, $model)
    {
        $sku = $model;

        $gateways = \Payments::getAvailableGateways();

        foreach ($gateways as $gateway => $gateway_title) {

            $Marketplace = new Marketplace($gateway);
            if (!$Marketplace->gateway->getConfig('manage_remote_sku')) {
                continue;
            }
            $Marketplace->deleteSKU($sku);

            $sku->setGatewayStatus($this->gateway->getName(), 'DELETED', null);
        }

        $sku->clearMediaCollection('marketplace-sku-image');

        $sku->delete();
    }
}

<?php

namespace Corals\Modules\Marketplace\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Marketplace\Classes\Marketplace;
use Corals\Modules\Marketplace\Models\Tag;
use Corals\Modules\Marketplace\Traits\DownloadableController;

class ProductService extends BaseServiceClass
{
    use DownloadableController;

    public $sku_attributes = ['regular_price', 'sale_price', 'code', 'inventory', 'inventory_value', 'allowed_quantity'];
    public $skipParameters = ['global_options', 'variation_options', 'create_gateway_product', 'tax_classes', 'categories', 'tags', 'posts', 'private_content_pages', 'downloads_enabled', 'downloads', 'cleared_downloads', 'external', 'price_per_classification'];

    public function getRequestData($request)
    {
        $excludedRequestParams = array_merge($this->skipParameters, $this->sku_attributes);

        if (is_array($request)) {
            $data = \Arr::except($request, $excludedRequestParams);
        } else {
            $data = $request->except($excludedRequestParams);
        }

        $data = \Store::setStoreData($data);

        $data = $this->setShippingData($data);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function setShippingData($data)
    {
        if (!isset($data['shipping']['enabled'])) {
            $data['shipping']['enabled'] = 0;
        }

        return $data;
    }

    protected function postStoreUpdate($request, $additionalData)
    {
        $product = $this->model;

        if ($product->type == "simple") {
            $sku_data = $request->only(array_merge($this->sku_attributes, ['status']));

            if ($product->sku->first()) {
                $product->sku->first()->update($sku_data);
            } else {
                $product->sku()->create($sku_data);
            }
        }

        $attributes = [];

        foreach ($request->get('global_options', []) as $option) {
            $attributes[$option] = [
                'sku_level' => false,
            ];
        }

        if ($product->type == "variable") {
            foreach ($request->get('variation_options', []) as $option) {
                $attributes[$option] = [
                    'sku_level' => true,
                ];
            }
        }

        $product->attributes()->sync($attributes);

        $product->categories()->sync($request->get('categories', []));
        $product->tax_classes()->sync($request->get('tax_classes', []));

        $tags = $this->getTags($request);

        $product->tags()->sync($tags);

        $product->posts()->sync($request->get('posts', []));

        $this->handleDownloads($request, $product);

        $product->indexRecord();
    }

    /**
     * @param $request
     * @return array
     */
    protected function getTags($request)
    {
        $tags = [];

        $requestTags = $request->get('tags', []);

        foreach ($requestTags as $tag) {
            if (is_numeric($tag)) {
                array_push($tags, $tag);
            } else {
                try {
                    $newTag = Tag::create([
                        'name' => $tag,
                        'slug' => \Str::slug($tag)
                    ]);

                    array_push($tags, $newTag->id);
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }

        return $tags;
    }

    /**
     * @param $request
     * @param $model
     * @throws \Exception
     */
    public function destroy($request, $model)
    {
        $product = $model;

        $gateways = \Payments::getAvailableGateways();

        foreach ($gateways as $gateway => $gateway_title) {
            $Marketplace = new Marketplace($gateway);
            if (!$Marketplace->gateway->getConfig('manage_remote_product')) {
                continue;
            }

            $Marketplace->deleteProduct($product);
            $product->setGatewayStatus($this->gateway->getName(), 'DELETED', null);
        }

        $product->clearMediaCollection('product-downloads');
        $product->clearMediaCollection($product->galleryMediaCollection);

        $product->delete();
        $product->unIndexRecord();
    }
}

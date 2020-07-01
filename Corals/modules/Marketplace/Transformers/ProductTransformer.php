<?php

namespace Corals\Modules\Marketplace\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\Marketplace\Models\Product;

class ProductTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('marketplace.models.product.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Product $product
     * @return array
     * @throws \Throwable
     */
    public function transform(Product $product)
    {

        $showUrl = url("{$this->resource_url}/{$product->hashed_id}");


        $productName = $product->name;
        if ($product->is_featured) {
            $productName .= '&nbsp;<i class="fa fa-star text-warning" title="Featured"></i>';
        }
        $transformedArray = [
            'id' => $product->id,
            'checkbox' => $this->generateCheckboxElement($product),
            'image' => '<a href="' . $showUrl . '">' . '<img src="' . $product->image . '" class=" img-responsive" alt="Product Image" style="max-width: 50px;max-height: 50px;"/></a>',
            'name' => '<a href="' . $showUrl . '">' . $productName . '</a>',
            'plain_name' => $productName,
            'price' => $product->price,
            'system_price' => $product->system_price,
            'type' => $product->type == "simple" ? '<i class="fa fa-spoon"></i>' : '<i class="fa fa-sitemap"></i>',
            'brand' => $product->brand ? $product->brand->name : '-',
            'store' => $product->store ? $product->store->name : '-',
            'caption' => $product->caption,
            'shippable' => $product->shipping['enabled'] ? '<i class="fa fa-truck"></i>' : '<i class="fa fa-times"></i>',
            'status' => formatStatusAsLabels($product->status),
            'categories' => formatArrayAsLabels($product->categories->pluck('name'), 'success', '<i class="fa fa-folder-open"></i>'),
            'tags' => generatePopover(formatArrayAsLabels($product->tags->pluck('name'), 'primary', '<i class="fa fa-tags"></i>')),
            'description' => $product->description ? generatePopover($product->description) : '-',
            'gateway_status' => $product->getGatewayStatus(),
            'global_options' => formatArrayAsLabels($product->globalOptions->pluck('label')),
            'variation_options' => formatArrayAsLabels($product->variationOptions->pluck('label'), 'info'),
            'created_at' => format_date($product->created_at),
            'updated_at' => format_date($product->updated_at),
            'action' => $this->actions($product, $product->getGatewayActions($product))
        ];

        return parent::transformResponse($transformedArray);
    }
}

<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Modules\Marketplace\Transformers\SKUTransformer;
use Yajra\DataTables\EloquentDataTable;

class SKUDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.sku.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new SKUTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param SKU $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(SKU $model)
    {
        $product = $this->request->route('product');

        if (!$product) {
            abort(404, 'Not Found!!');
        }

        return $model->newQuery()->where('product_id', $product->id);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'image' => ['width' => '50px', 'title' => trans('Marketplace::attributes.sku.image'), 'orderable' => false, 'searchable' => false],
            'code' => ['title' => trans('Marketplace::attributes.sku.code')],
            'price' => ['title' => trans('Marketplace::attributes.sku.price')],
            'inventory' => ['title' => trans('Marketplace::attributes.sku.inventory')],
            'dt_options' => ['title' => trans('Marketplace::attributes.sku.dt_options'), 'orderable' => false, 'searchable' => false],
            'status' => ['title' => trans('Corals::attributes.status')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}

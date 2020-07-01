<?php

namespace Corals\Modules\Marketplace\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Transformers\BrandTransformer;
use Yajra\DataTables\EloquentDataTable;

class BrandsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('marketplace.models.brand.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new BrandTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Brand $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Brand $model)
    {
        return $model->withCount('products');
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
            'logo' => ['title' => trans('Marketplace::attributes.brand.logo')],
            'name' => ['title' => trans('Marketplace::attributes.brand.name')],
            'slug' => ['title' => trans('Marketplace::attributes.brand.slug')],
            'products_count' => ['title' => trans('Marketplace::attributes.brand.products_count'), 'searchable' => false],
            'status' => ['title' => trans('Corals::attributes.status')],
            'is_featured' => ['title' => trans('Marketplace::attributes.brand.is_featured')],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }

    protected function getBulkActions()
    {
        return [
            'delete' => ['title' => trans('Corals::labels.delete'), 'permission' => 'Marketplace::brand.delete', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'active' => ['title' => '<i class="fa fa-check-circle"></i> ' . trans('Marketplace::status.store.active'), 'permission' => 'Marketplace::brand.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
            'inActive' => ['title' => '<i class="fa fa-check-circle-o"></i> ' . trans('Marketplace::status.store.inactive'), 'permission' => 'Marketplace::brand.update', 'confirmation' => trans('Corals::labels.confirmation.title')],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('marketplace.models.brand.resource_url'));
        return ['resource_url' => $url];
    }
}

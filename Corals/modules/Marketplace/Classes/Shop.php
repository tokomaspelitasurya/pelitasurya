<?php

namespace Corals\Modules\Marketplace\Classes;


use Corals\Foundation\Search\Search;
use Corals\Modules\Marketplace\Models\Attribute;
use Corals\Modules\Marketplace\Models\Brand;
use Corals\Modules\Marketplace\Models\Category;
use Corals\Modules\Marketplace\Models\OrderItem;
use Corals\Modules\Marketplace\Models\Product;
use Corals\Modules\Marketplace\Models\SKU;
use Corals\Settings\Facades\Settings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Shop
{
    public $page_limit;

    public function __construct()
    {
        $this->page_limit = Settings::get('marketplace_appearance_page_limit', 15);
    }

    public function getFeaturedProducts()
    {
        $products = Product::active()->has('activeSKU', '>=', 1)->featured()->get();

        return $products;
    }

    protected function productsPublicBaseQuery($store = null)
    {


        $query = Product::active()->join('marketplace_sku', 'marketplace_sku.product_id', '=', 'marketplace_products.id')
            ->join('marketplace_stores', 'marketplace_products.store_id', '=', 'marketplace_stores.id')
            ->where('marketplace_sku.status', 'active')
            ->where('marketplace_stores.status', 'active')
            ->groupBy('marketplace_sku.product_id', 'marketplace_products.id');

        if ($store) {
            $query->where('marketplace_products.store_id', $store->id);
        }
        return $query;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getProducts(Request $request, $store = null)
    {
        $products = $this->productsPublicBaseQuery($store);

        foreach ($request->all() as $filter => $value) {
            $filterMethod = $filter . 'QueryBuilderFilter';
            if (method_exists($this, $filterMethod) && !empty($value)) {
                $products = $this->{$filterMethod}($products, $value);
            }
        }

        $products = $products->addSelect('marketplace_products.*')->paginate($this->page_limit);

        return $products;
    }

    /**
     * @param Request|null $request
     * @return mixed
     */
    public function getAllActiveProducts(Request $request = null)
    {
        $products = $this->productsPublicBaseQuery();

        return $products->get();
    }

    protected function sortQueryBuilderFilter($products, $sortOption)
    {
        switch ($sortOption) {
            case 'popular':
                break;
            case 'low_high_price':
                $products = $products->addSelect(\DB::raw('min(marketplace_sku.regular_price) as sku_price'))->orderBy('sku_price', 'asc');
                break;
            case 'high_low_price':
                $products = $products->addSelect(\DB::raw('max(marketplace_sku.regular_price) as sku_price'))->orderBy('sku_price', 'desc');
                break;
            case 'average_rating':
                $products = $products->leftJoin('utility_ratings', 'reviewrateable_id', '=', 'marketplace_products.id')
                    ->where('utility_ratings.reviewrateable_type', Product::class)
                    ->orWhereNull('utility_ratings.id')
                    ->addSelect(\DB::raw('ROUND(AVG(rating), 2) as averageReviewRateable'))->orderBy('averageReviewRateable', 'desc');
                break;
            case 'a_z_order':
                $products = $products->orderBy('marketplace_products.name', 'asc');
                break;
            case 'z_a_order':
                $products = $products->orderBy('marketplace_products.name', 'desc');
                break;
        }
        return $products;
    }

    protected function searchQueryBuilderFilter($products, $search_term)
    {
        $search = new Search();

        $config = [
            'title_weight' => \Settings::get('marketplace_search_title_weight'),
            'content_weight' => \Settings::get('marketplace_search_content_weight'),
            'enable_wildcards' => \Settings::get('marketplace_search_enable_wildcards')
        ];

        $products = $search->AddSearchPart($products, $search_term, Product::class, $config);

        return $products;
    }

    protected function featuredQueryBuilderFilter($products, $featured)
    {
        if ($featured) {
            $products = $products->featured();
        }

        return $products;
    }

    private function attributesColumnMapping()
    {
        $attributes = Attribute::query()->whereHas('categories')->get();


        $attributesColumnMapping = [];

        foreach ($attributes as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                case 'text':
                case 'date':
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'textarea':
                    $attributesColumnMapping[$attribute->id]['column'] = 'text_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = 'like';
                    break;
                case 'number':
                case 'select':
                case 'multi_values':
                case 'radio':
                    $attributesColumnMapping[$attribute->id]['column'] = 'number_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
                    break;
                default:
                    $attributesColumnMapping[$attribute->id]['column'] = 'string_value';
                    $attributesColumnMapping[$attribute->id]['operation'] = '=';
            }
        }

        return $attributesColumnMapping;
    }

    protected function optionsQueryBuilderFilter($products, $attributes)
    {
        $attributesColumnMapping = $this->attributesColumnMapping();

        $attributes = array_filter($attributes, function ($value) {
            return !empty($value);
        });

        if (empty($attributes)) {
            return $products;
        }


        foreach ($attributes as $key => $value) {
            $products = $products->join("marketplace_sku_options as attribute_$key", "attribute_$key.sku_id", '=', 'marketplace_sku.id');

            $value = isset($attributesColumnMapping[$key]['operation']) && $attributesColumnMapping[$key]['operation'] == 'like' ? '%' . $value . '%' : $value;
            if (is_array($value)) {
                $products = $products->where("attribute_$key." . $attributesColumnMapping[$key]['column'] ?? 'string_value', $value);
            } else {
                $products = $products->where("attribute_$key." . $attributesColumnMapping[$key]['column'] ?? 'string_value', $attributesColumnMapping[$key]['operation'] ?? '=', $value);
            }
        }

        return $products;
    }

    protected function priceQueryBuilderFilter($products, $price)
    {
        if (!is_array($price)) {
            return $products;
        }

        $minPrice = \Arr::get($price, 'min', 0);
        $maxPrice = \Arr::get($price, 'max', 999999);

        if ($this->getSKUMinPrice() != $minPrice || $this->getSKUMaxPrice() != $maxPrice) {
            $products = $products->whereBetween('marketplace_sku.regular_price', [$minPrice, $maxPrice]);
        }

        return $products;
    }

    protected function categoryQueryBuilderFilter($products, $category, $status = 'active')
    {
        /**
         * check if category is array or a single value
         */
        $queryMethod = 'where';

        if (is_array($category)) {
            $queryMethod = 'whereIn';
        }

        $orQueryMethod = 'or' . ucfirst($queryMethod);

        /**
         * get the related categories
         */
        $categories = Category::{$queryMethod}('marketplace_categories.id', $category)
            ->orWhere(function ($parent) use ($queryMethod, $category) {
                $parent->{$queryMethod}('marketplace_categories.parent_id', $category)
                    ->where('marketplace_categories.parent_id', '<>', 0);
            })->{$orQueryMethod}('marketplace_categories.slug', $category)->pluck('id')->toArray();

        /**
         * add categories query to products query
         */
        $products = $products->join('marketplace_category_product', 'marketplace_category_product.product_id', 'marketplace_products.id')
            ->join('marketplace_categories', 'marketplace_category_product.category_id', 'marketplace_categories.id')
            ->where(function ($query) use ($categories) {
                $query->whereIn('marketplace_categories.id', $categories);
            });

        if (!empty($status)) {
            $products->where('marketplace_categories.status', $status);
        }

        return $products;
    }

    protected function brandQueryBuilderFilter($products, $brand)
    {
        $products = $products
            ->join('marketplace_brands', 'marketplace_brands.id', '=', 'marketplace_products.brand_id')
            ->where(function ($query) use ($brand) {
                $queryMethod = 'where';

                if (is_array($brand)) {
                    $queryMethod = 'whereIn';
                }

                $orQueryMethod = 'or' . ucfirst($queryMethod);

                $query->{$queryMethod}('marketplace_brands.id', $brand)
                    ->{$orQueryMethod}('marketplace_brands.slug', $brand);
            });

        return $products;
    }

    /**
     * @param $category_id
     * @param bool $count
     * @return mixed
     */
    public function getCategoryAvailableProducts($category_id, $count = false, $exclude = [], $limit = null)
    {
        if ($store = \Store::getStore()) {
            $products = $store->products();
        } else {
            $products = Product::has('activeSKU', '>=', 1)
                ->where('marketplace_products.status', 'active');
        }

        $products->has('activeSKU', '>=', 1)
            ->where('marketplace_products.status', 'active');

        if ($exclude) {
            $products->whereNotIn('marketplace_products.id', $exclude);
        }

        $products = $this->categoryQueryBuilderFilter($products, $category_id);

        if ($count) {
            $products = $products->count();
        } else {
            $limit = $limit ?? $this->page_limit;
            $products = $products->select('marketplace_products.*')->paginate($limit);
        }

        return $products;
    }

    public function getBrandAvailableProducts($brand, $count = false)
    {
        $products = Product::has('activeSKU', '>=', 1)
            ->where('marketplace_products.status', 'active');

        $products = $this->brandQueryBuilderFilter($products, $brand);

        if ($count) {
            $products = $products->count();
        } else {
            $products = $products->select('marketplace_products.*')->paginate($this->page_limit);
        }

        return $products;
    }

    protected function tagQueryBuilderFilter($products, $tag, $status = 'active')
    {
        $products = $products->join('marketplace_product_tag', 'marketplace_product_tag.product_id', 'marketplace_products.id')
            ->join('marketplace_tags', 'marketplace_product_tag.tag_id', 'marketplace_tags.id')
            ->where(function ($query) use ($tag) {
                $queryMethod = 'where';

                if (is_array($tag)) {
                    $queryMethod = 'whereIn';
                }

                $orQueryMethod = 'or' . ucfirst($queryMethod);

                $query->{$queryMethod}('marketplace_tags.id', $tag)
                    ->{$orQueryMethod}('marketplace_tags.slug', $tag);
            });

        if (!empty($status)) {
            $products->where('marketplace_tags.status', $status);
        }

        return $products;
    }

    /**
     * @param $tag_id
     * @return mixed
     */
    public function getTagAvailableProducts($tag_id)
    {
        $products = Product::has('activeSKU', '>=', 1)
            ->where('marketplace_products.status', 'active');

        $products = $this->tagQueryBuilderFilter($products, $tag_id);

        $products = $products->select('marketplace_products.*')
            ->paginate($this->page_limit);

        return $products;
    }

    /**
     * @param bool $root
     * @return mixed
     */
    public function getActiveCategories($root = true)
    {
        if ($store = \Store::getStore()) {
            $categories = $store->productCategories();
        } else {
            $categories = Category::active();
        }

        if ($root) {
            $categories = $categories->whereNull('parent_id')->orWhere('parent_id', 0);
        }

        $categories = $categories->get()->unique();

//        $categories = $categories->map(function ($category, $key) {
//            $category['products_count'] = $this->getCategoryAvailableProducts($category->id, true);
//            return $category;
//        });

        return $categories;
    }

    /**
     * @return mixed
     */
    public function getActiveBrands($categories)
    {

        $store = \Store::getStore();

        if (!$categories & !$store) {
            return new Collection();
        }


        $brands = Brand::query()->active();

        if ($categories) {

            if ($categories != "all") {
                if (!is_array($categories)) {
                    $categories = [$categories];
                }
                $brands = $brands->whereHas('categories', function ($query) use ($categories) {
                    $query->where('marketplace_categories.slug', $categories);
                });
            }

        }


        $brands = $brands
            ->leftJoin('marketplace_products', 'marketplace_products.brand_id', '=', 'marketplace_brands.id')
            ->select(\DB::raw('count(marketplace_products.id) as products_count'), 'marketplace_brands.*')
            ->groupBy('marketplace_products.brand_id', 'marketplace_brands.id');

        if ($store) {
            $brands->where('marketplace_products.store_id', $store->id);

        }

        $brands = $brands->get();

        return $brands;
    }

    public function getFeaturedBrands()
    {
        $featuredBrands = Brand::active()->featured()->get();

        return $featuredBrands;
    }

    public function getFeaturedCategories()
    {
        $featuredCategories = Category::active()->featured()->get();

        $featuredCategories = $featuredCategories->map(function ($category, $key) {
            $category['starting_from_price'] = SKU::join('marketplace_products', 'marketplace_products.id', '=', 'marketplace_sku.product_id')
                ->join('marketplace_category_product', 'marketplace_category_product.product_id', 'marketplace_products.id')
                ->join('marketplace_categories', 'marketplace_category_product.category_id', 'marketplace_categories.id')
                ->where('marketplace_categories.id', $category->id)
                ->orWhere('marketplace_categories.parent_id', $category->id)->min('regular_price');

            return $category;
        });

        return $featuredCategories;
    }

    public function getSKUMinPrice()
    {

        if ($store = \Store::getStore()) {
            return (int)$store->skus()->min('regular_price');
        } else {
            return (int)SKU::min('regular_price');
        }
    }

    public function getSKUMaxPrice()
    {
        if ($store = \Store::getStore()) {
            return (int)$store->skus()->max('regular_price');
        } else {
            return (int)SKU::max('regular_price');
        }

    }

    public function checkActiveKey($value, $compareWithKey)
    {
        if (request()->has($compareWithKey)) {
            $compareWithValue = request()->get($compareWithKey);

            if (is_array($compareWithValue)) {
                return array_search($value, $compareWithValue) !== false;
            } else {
                return $value == $compareWithValue;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getAttributesForFilters($categories)
    {

        if (!$categories) {
            $categories = [];
        }

        if (!is_array($categories)) {
            $categories = [$categories];
        }

        $attributes = Attribute::query()->whereHas('categories', function ($query) use ($categories) {
            $query->whereIn('slug', $categories);
        })->get();

        $filters = '';

        foreach ($attributes as $attribute) {
            $filters .= \Category::renderAttribute($attribute, null, ['as_filter' => true]);
        }

        return $filters;
    }

    /**
     * @param int $take
     * @return array
     */
    public function getTopSellers($take = 3)
    {
        // Top Sellers
        $orderItems = OrderItem::select('sku_code', \DB::raw('count(*) as sku_count'))
            ->where('type', 'Product')
            ->groupBy('sku_code')
            ->orderBy('sku_count', 'desc')
            ->take($take)
            ->get();

        $products = collect([]);

        foreach ($orderItems as $orderItem) {
            if ($product = optional($orderItem->sku)->product) {
                $products->push($product);
            }
        }
        if ($products->count() == 0) {
            $products = Product::orderByRaw('RAND()')->take($take)->get();
        }
        return $products;
    }

    /**
     * @param int $take
     * @return mixed
     */
    public function getNewArrivals($take = 3)
    {
        $products = $this->productsPublicBaseQuery();

        return $products->select('marketplace_products.*')->orderBy('marketplace_products.created_at', 'desc')->take($take)->get();
    }

    /**
     * @param int $take
     * @return mixed
     */
    public function getBestRated($take = 3)
    {
        $products = $this->productsPublicBaseQuery();

        $products = $products->addSelect('marketplace_products.*');

        $products = $products->leftJoin('utility_ratings', 'reviewrateable_id', '=', 'marketplace_products.id')
            ->where('utility_ratings.reviewrateable_type', Product::class)
            ->orderBy('averageReviewRateable', 'desc')
            ->addSelect(\DB::raw('ROUND(AVG(rating), 2) as averageReviewRateable'))
            ->take($take)->get();
        if ($products->count() == 0) {
            $products = Product::orderByRaw('RAND()')->take($take)->get();
        }
        return $products;
    }


    public function appendCategories(&$result, $categories, $parentId)
    {
        if ($parentId == -1) {
            $parentCategories = $categories;
        } else {
            $parentCategories = $categories->where('parent_id', $parentId);
        }

        foreach ($parentCategories as $category) {
            $this->appendSingle($result, $category);

            $this->appendCategories($result[$category->id]['children'], $categories, $category->id);
        }
    }

    public function appendSingle(&$result, $category)
    {
        $result[$category->id] = ['id' => $category->id, 'name' => $category->name, 'slug' => $category->slug,
            'products_count' => 0,
            'children' => []];
    }

    public function getCategoriesHierarchy()
    {
        if ($store = \Store::getStore()) {
            $categories = $store->productCategories();
        } else {
            $categories = Category::active();
        }

        $categories = $categories->get()->unique();

        $parentId = null;

        if (($categories->where('parent_id', null))->isEmpty()) {
            $parentId = -1;
        }

        $cacheKey = 'shop_categories';

        if ($store) {
            $cacheKey .= '_store_' . $store->id;
        }

        if (Cache::has($cacheKey)) {
            $result = Cache::get($cacheKey);
        } else {
            $result = [];

            $this->appendCategories($result, $categories, $parentId);

            Cache::put($cacheKey, $result, 1440);
        }

        return $result;
    }
}

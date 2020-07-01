<?php

namespace Corals\Modules\Marketplace\Models;

use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Brand extends BaseModel implements HasMedia
{
    use PresentableTrait, LogsActivity, HasMediaTrait;

    protected $table = 'marketplace_brands';
    public $mediaCollectionName = 'marketplace-brand-thumbnail';

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.brand';

    protected static $logAttributes = ['name'];

    protected $guarded = ['id'];

    protected $casts = [
        'is_featured' => 'boolean'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('marketplace_brands.status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('marketplace_brands.is_featured', true);
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Str::slug($value);
    }


    public function categories()
    {
        return $this->hasManyDeep(Category::class, [Product::class, 'marketplace_category_product']);
    }


}

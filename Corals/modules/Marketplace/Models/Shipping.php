<?php

namespace Corals\Modules\Marketplace\Models;

use Carbon\Carbon;
use Corals\Foundation\Models\BaseModel;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\User\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;

class Shipping extends BaseModel
{
    use PresentableTrait, LogsActivity;

    protected $table = 'marketplace_shippings';
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'marketplace.models.shipping';

    protected static $logAttributes = [];

    protected $guarded = ['id'];

    protected $dates = [
        'start',
        'expiry'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'marketplace_shipping_user');
    }

    public function status()
    {
        if ($this->start <= Carbon::today()->toDateString() && ($this->expiry >= Carbon::today()->toDateString())) {
            return "active";

        } else if ($this->start > Carbon::today()->toDateString()) {
            return "pending";

        } else {
            return "expired";
        }
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'marketplace_shipping_product');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

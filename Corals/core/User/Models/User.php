<?php

namespace Corals\User\Models;

use Corals\Foundation\Traits\BaseRelations;
use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\Hookable;
use Corals\Foundation\Traits\Language\Translatable;
use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Traits\ModelPropertiesTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Corals\Modules\CMS\Models\Content;
use Corals\Modules\Utility\Traits\Rating\ReviewRateable as ReviewRateableTrait;
use Corals\Settings\Traits\CustomFieldsModelTrait;
use Corals\User\Contracts\TwoFactorAuthenticatableContract;
use Corals\User\Traits\HasApiTokens;
use Corals\User\Traits\TwoFactorAuthenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Traits\HasRoles;
use Yajra\Auditable\AuditableTrait;


class User extends Authenticatable implements TwoFactorAuthenticatableContract, HasMedia
{
    use TwoFactorAuthenticatable, Notifiable, HashTrait, HasRoles, ModelPropertiesTrait, HasApiTokens,
        Hookable, PresentableTrait, LogsActivity, HasMediaTrait, AuditableTrait,
        CustomFieldsModelTrait, ModelHelpersTrait, ReviewRateableTrait, Translatable, BaseRelations,
        ModelActionsTrait;

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'user.models.user';

    protected static $logAttributes = ['name', 'email'];

    protected static $ignoreChangedAttributes = ['remember_token'];

    protected $appends = ['picture', 'picture_thumb'];

    protected $dates = ['trial_ends_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'two_factor_options'
    ];

    protected $casts = [
        'address' => 'json',
        'notification_preferences' => 'array',
        'properties' => 'json'
    ];

    public function __construct(array $attributes = [])
    {
        $config = config($this->config);

        if (isset($config['presenter'])) {
            $this->setPresenter(new $config['presenter']);
            unset($config['presenter']);
        }

        foreach ($config as $key => $val) {
            if (property_exists(get_called_class(), $key)) {
                $this->$key = $val;
            }
        }

        return parent::__construct($attributes);
    }

    public function address($type = null)
    {
        return $this->address[$type] ?? [];
    }

    public function getConfirmedAttribute()
    {
        return !is_null($this->confirmed_at);
    }

    public function display_address($type = null)
    {
        $address = optional($this->address)[$type];

        if (!$address) {
            return "";
        }
        $display_address = "";

        $display_address .= $address['address_1'] . "<br>";

        if ($address['address_2']) {
            $display_address .= $address['address_2'] . "<br>";
        }
        $display_address .= $address['city'] . ' ' . $address['state'] . ' ' . $address['zip'] . "<br>";
        $display_address .= $address['country'];
        return $display_address;
    }

    public function saveAddress($address, $type)
    {
        $user_address = $this->address;
        if (!is_array($user_address)) {
            $user_address = [];
        }
        $user_address[$type] = $address;
        $this->address = $user_address;
        $this->save();
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if (strpos($permission, ':') === false) {
            return false;
        }

        $permissions = app(PermissionRegistrar::class)->getPermissions();

        $permission = $permissions->filter(function ($permissionObject) use ($permission) {
            return $permissionObject->name === $permission;
        })->first();

        if (!$permission) {
            return false;
        }

        return $this->hasPermissionViaRole($permission) || $this->hasDirectPermission($permission);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function getPictureAttribute()
    {
        $media = $this->getFirstMedia('user-picture');
        if ($media) {
            return $media->getFullUrl();
        } else {
            $id = $this->attributes['id'] ?? 1;
            $avatar = 'avatar_' . ($id % 10) . '.png';
            return asset(config($this->config . '.default_picture') . $avatar);
        }
    }

    public function getPictureThumbAttribute()
    {
        $media = $this->getFirstMedia('user-picture');
        if ($media) {
            return $media->getFullUrl('thumb');
        } else {
            $id = $this->attributes['id'] ?? 1;
            $avatar = 'avatar_' . ($id % 10) . '.png';
            return asset(config($this->config . '.default_picture') . $avatar);
        }
    }


    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10);
    }


    public function posts()
    {
        return $this->morphToMany(Content::class, 'postable');

    }

    public function getDashboardURL()
    {
        $dashoard_url = 'dashboard';
        $dashoard_url = \Filters::do_filter('dashboard_url', $dashoard_url);
        return url($dashoard_url);

    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function routeNotificationForNexmo()
    {
        $phone = $this->phone_number;
        if ($phone) {
            $phone = preg_replace('/[^0-9]/', '', $phone);
        }
        $phone = $this->phone_country_code . $phone;

        return $phone;
    }

    /**
     * @return $this
     */
    public function getOwner()
    {
        return $this;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserLastLogin()
    {
        return optional(DB::table('activity_log')
            ->where(function ($query) {
                $query->where('log_name', 'auth')
                    ->where('description', 'like', "%logged In")
                    ->where('causer_type', getModelMorphMap(User::class))
                    ->where('causer_id', $this->id);
            })->latest()
            ->first('created_at'))->created_at;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getUserLastActivity()
    {
        return optional(DB::table('http_log')
            ->where('user_id', $this->id)
            ->latest()
            ->take(1)
            ->first('created_at'))->created_at;
    }
}

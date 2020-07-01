<?php

namespace Corals\Settings\Models;

use Corals\Foundation\Traits\Cache\Cachable;
use Corals\Foundation\Traits\HashTrait;
use Corals\Foundation\Traits\ModelActionsTrait;
use Corals\Foundation\Traits\ModelHelpersTrait;
use Corals\Foundation\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CustomFieldSetting extends Model
{
    use PresentableTrait, LogsActivity, HashTrait, Cachable, ModelActionsTrait, ModelHelpersTrait;


    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->initialize();

        return parent::__construct($attributes);
    }

    /**
     * init model
     */
    public function initialize()
    {
        $config = config($this->config);
        if ($config) {
            if (isset($config['presenter'])) {
                $this->setPresenter(new $config['presenter']);
                unset($config['presenter']);
            }

            foreach ($config as $key => $val) {
                if (property_exists(get_called_class(), $key)) {
                    $this->$key = $val;
                }
            }
        }
    }

    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'settings.models.custom_field_setting';

//    protected static $logAttributes = [];

    protected $casts = [
        'fields' => 'json',
        'properties' => 'json'
    ];

    protected $guarded = ['id'];

    public function getCustomAttributesAttribute()
    {
        return getKeyValuePairs($this->attributes['custom_attributes'] ?? []);
    }

    public function getOptionsAttribute()
    {
        return getKeyValuePairs($this->attributes['options'] ?? []);
    }
}

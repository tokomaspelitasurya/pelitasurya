<?php

namespace Corals\Foundation\Traits;

trait ModelPropertiesTrait
{

    public static function bootModelPropertiesTrait()
    {
    }

    /**
     * properties column name for model.
     *
     * @var string
     */
    protected $propertiesColumn = 'properties';

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->{$this->propertiesColumn};
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasProperty($key)
    {
        return \Arr::has($this->{$this->propertiesColumn}, $key);
    }

    /**
     * @param $key
     * @param null $default
     * @param null $castTo
     * @return mixed|null
     *
     * The Php casts allowed are:
     *
     * (int), (integer) - cast to integer
     * (bool), (boolean) - cast to boolean
     * (float), (double), (real) - cast to float
     * (string) - cast to string
     * (array) - cast to array
     * (object) - cast to object
     * (unset) - cast to NULL
     */
    public function getProperty($key, $default = null, $castTo = null)
    {
        $properties = $this->{$this->propertiesColumn};

        $value = $default;

        if (is_array($properties)) {
            $value = \Arr::get($this->{$this->propertiesColumn}, $key, $default);
        } elseif (!empty($properties)) {
            $value = $properties;
        }

        if (!is_null($castTo)) {
            switch ($castTo) {
                case 'int':
                case 'integer':
                    $value = (integer)$value;
                    break;
                case 'bool':
                case'boolean':
                    $value = (boolean)$value;
                    break;
                case 'float':
                case 'double':
                case 'real':
                    $value = (float)$value;
                    break;
                case 'string':
                    $value = (string)$value;
                    break;
                case 'array':
                    $value = (array)$value;
                    break;
                case 'object':
                    $value = (object)$value;
                    break;
                case 'unset':
                    $value = null;
                    break;
                case 'collection':
                    $value = collect($value);
                    break;
            }
        }


        return $value;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setProperty($key, $value)
    {
        // get current Properties
        $properties = $this->{$this->propertiesColumn};

        if (empty($properties)) {
            $properties = [];
        }

        if (!is_null($key) && is_array($properties)) {
            // set property value
            \Arr::set($properties, $key, $value);
        } else {
            $properties = $value;
        }

        // update model properties
        $this->{$this->propertiesColumn} = $properties;

        // save model
        return $this->save();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function forgetProperty($key)
    {
        // get current Properties
        $properties = $this->{$this->propertiesColumn};

        if (!is_null($key) && is_array($properties)) {
            // remove property value
            \Arr::forget($properties, $key);
        } else {
            $properties = null;
        }

        // update model properties
        $this->{$this->propertiesColumn} = $properties;

        // save model
        return $this->save();
    }

    /**
     * @return mixed
     */
    public function purgeProperties()
    {
        return $this->forgetProperty(null);
    }

    /**
     * @param $properties
     * @return mixed
     */
    public function setProperties($properties)
    {
        return $this->setProperty(null, $properties);
    }
}
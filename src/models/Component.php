<?php

namespace modules\components\models;

use craft\base\Model;

class Component extends Model
{
    public $name;
    public $path;
    public $fields;
    public $status;
    public $visible;
    public $type;
    public $key;
    public $description;
    public $variants;

    /**
     * @param string $variant Variant type
     * @return array
     */
    public function getVariant(string $variant) : array
    {
        if ($variant !== 'default') {
            return (array) array_merge(
                (array) $this->variants['default'],
                (array) $this->variants[$variant]
            );
        }

        return (array) $this->variants['default'];
    }

    public function getComponentTemplate() : string
    {
        $path = Components::getInstance()
            ->components
            ->getComponentsPath();

        return $path . DIRECTORY_SEPARATOR . $this->path  . DIRECTORY_SEPARATOR . $this->key;
    }
}
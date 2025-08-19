<?php

namespace SixShop\Core\Entity;

use think\Entity;

abstract class BaseEntity extends Entity
{
    protected function getOptions(): array
    {
        return [
            'modelClass' => str_replace(['\\Entity\\', 'Entity'], ['\\Entity\\', 'Model'], static::class),
        ];
    }
}
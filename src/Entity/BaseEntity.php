<?php

namespace SixShop\Core\Entity;

use think\Entity;

abstract class BaseEntity extends Entity
{
    protected function getOptions(): array
    {
        return [
            'modelClass' => str_replace(
                ['\\Entity\\', '\\entity\\','Entity'], 
                ['\\Model\\', '\\model\\', 'Model'], 
                static::class
            ),
        ];
    }
}
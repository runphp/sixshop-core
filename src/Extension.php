<?php
declare(strict_types=1);

namespace SixShop\Core;


use SixShop\Core\Contracts\CoreExtensionInterface;

class Extension extends ExtensionAbstract implements CoreExtensionInterface
{
    protected function getBaseDir(): string
    {
        return dirname(__DIR__);
    }
}
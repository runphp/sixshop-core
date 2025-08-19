<?php
declare(strict_types=1);

namespace SixShop\Core;


class Extension extends ExtensionAbstract
{
    protected function getBaseDir(): string
    {
        return dirname(__DIR__);
    }
}
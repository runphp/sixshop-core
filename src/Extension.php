<?php
declare(strict_types=1);

namespace SixShop\core;


class Extension extends ExtensionAbstract
{
    protected function getBaseDir(): string
    {
        return dirname(__DIR__);
    }
}
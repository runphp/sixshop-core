<?php
declare(strict_types=1);
namespace SixShop\core\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Cron
{
    public function __construct(
        public string $rule,
        public string $name = '',
    ) {}
}

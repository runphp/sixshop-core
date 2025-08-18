<?php
declare(strict_types=1);

namespace SixShop\core\Trait;

use SixShop\Extension\system\ExtensionManager;

trait ConfigTrait
{
    public function __construct(private readonly ExtensionManager $extensionManager, private array $options = [])
    {
    }

    public function getConfig(string $key = null): mixed
    {
        if (empty($this->options)) {
            $extensionID = explode('\\', static::class)[2];
            $this->options = $this->extensionManager->getExtensionConfig($extensionID);
        }
        return $key ? ($this->options[$key] ?? null) : $this->options;
    }
}
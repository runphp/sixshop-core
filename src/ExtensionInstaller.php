<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\Installer\LibraryInstaller;

class ExtensionInstaller extends LibraryInstaller
{
    public function supports(string $packageType): bool
    {
        return $packageType === $this->type;
    }
}
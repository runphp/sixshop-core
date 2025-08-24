<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class ExtensionInstaller extends LibraryInstaller
{
    public function supports(string $packageType): bool
    {
        return $packageType === $this->type;
    }
}
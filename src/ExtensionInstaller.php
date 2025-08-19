<?php
declare(strict_types=1);
namespace SixShop\Core;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;

class ExtensionInstaller extends LibraryInstaller
{
    public function supports(string $packageType)
    {
        return $packageType === 'sixshop-extension';
    }

    public function getInstallPath(PackageInterface $package)
    {
        return 'extension/' . $package->getPrettyName();
    }
}
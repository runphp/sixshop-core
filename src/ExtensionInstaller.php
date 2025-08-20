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

    public function getInstallPath(PackageInterface $package): string
    {
        if (!isset($package->getExtra()['sixshop']['id'])) {
            throw new \InvalidArgumentException('Extension id not found in extra.sixshop.id');
        }
        $id = $package->getExtra()['sixshop']['id'];
        return 'extension/' . $id;
    }
}
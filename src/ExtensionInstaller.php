<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Installer\PackageEvents;
use Composer\Package\PackageInterface;

class ExtensionInstaller extends LibraryInstaller implements EventSubscriberInterface
{
    public function supports(string $packageType)
    {
        return $packageType === 'sixshop-extension';
    }

    public function getInstallPath(PackageInterface $package)
    {
        return 'extension/' . $package->getPrettyName();
    }


    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
        ];
    }

    public function onPostPackageInstall(PackageEvent $event)
    {
        $package = $event->getOperation()->getPackage();
        $this->io->write('Installing sixshop extension ' . $package->getPrettyName());
    }
}
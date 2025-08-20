<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface, EventSubscriberInterface
{


    public function activate(Composer $composer, IOInterface $io): void
    {
        $installer = new ExtensionInstaller($io, $composer, 'sixshop-extension');
        $composer->getInstallationManager()->addInstaller($installer);
    }


    public static function getSubscribedEvents(): array
    {
        return [
            PackageEvents::PRE_PACKAGE_INSTALL => 'onPrePackageInstall',
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
        ];
    }

    public function onPrePackageInstall(PackageEvent $event): bool
    {
        $package = $event->getOperation()->getPackage();
        $extra = $package->getExtra();
        if (!isset($extra['sixshop'])) {
            throw new \RuntimeException('SixShop extension must have "sixshop" extra section.');
        }
        if (!isset($extra['sixshop']['id']) || !isset($extra['sixshop']['class'])) {
            throw new \RuntimeException('Invalid sixshop extension configuration');
        }
        return true;
    }

    public function onPostPackageInstall(PackageEvent $event): bool
    {
        return true;
    }


    public function deactivate(Composer $composer, IOInterface $io)
    {
        // TODO: Implement deactivate() method.
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // TODO: Implement uninstall() method.
    }
}
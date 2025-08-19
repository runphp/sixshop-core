<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface, EventSubscriberInterface
{


    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = new ExtensionInstaller($io, $composer, 'sixshop-extension');
        $composer->getInstallationManager()->addInstaller($installer);
    }


    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL => 'onPostPackageInstall',
        ];
    }

    public function onPostPackageInstall(PackageEvent $event, IOInterface $io)
    {
        $package = $event->getOperation()->getPackage();
        $io->write('Installing sixshop extension ' . $package->getPrettyName());
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
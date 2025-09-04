<?php
declare(strict_types=1);

namespace SixShop\Core;

use Composer\Autoload\ClassLoader;
use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\InstalledVersions;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

class Plugin implements PluginInterface, EventSubscriberInterface
{
    public const EXTENSION_TYPE = 'sixshop-extension';

    public static array $installedSixShopExtensions = [];

    public static function getSubscribedEvents(): array
    {
        return [
            ScriptEvents::POST_AUTOLOAD_DUMP => 'onPostAutoloadDump',
        ];
    }

    /**
     * @return array{root: array{reference: string}, versions: array<string, array>}
     */
    public static function getInstalledSixShopExtensions(): array
    {
        if (self::$installedSixShopExtensions) {
            return self::$installedSixShopExtensions;
        }
        $vendorDir = key(ClassLoader::getRegisteredLoaders());
        $filePath = $vendorDir . '/composer/installedSixShop.php';
        if (file_exists($filePath)) {
            return self::$installedSixShopExtensions = require $filePath;
        }
        throw new \RuntimeException('Please run "composer dump-autoload" to generate installedSixShop.php');
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        $installer = new ExtensionInstaller($io, $composer, self::EXTENSION_TYPE);
        $composer->getInstallationManager()->addInstaller($installer);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public function onPostAutoloadDump(Event $event): void
    {
        $installedSixShopExtensions = [
            'root' => [],
            'versions' => []
        ];
        $referenceMap = [];
        foreach (InstalledVersions::getAllRawData() as $installed) {
            foreach ($installed['versions'] as $name => $package) {
                if (isset($package['type']) && $package['type'] === self::EXTENSION_TYPE) {
                    $installedSixShopExtensions['versions'][$name] = $package;
                    $referenceMap[$name] = $package['reference'];
                }
            }
        }
        $installedSixShopExtensions['root']['reference'] = hash('md5', json_encode($referenceMap));
        $filePath = $event->getComposer()->getConfig()->get('vendor-dir') . '/composer/installedSixShop.php';
        file_put_contents($filePath, '<?php return ' . var_export($installedSixShopExtensions, true) . ';');
    }
}
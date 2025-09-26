<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Core\Contracts\ExtensionInterface;
use think\App;
use function SixShop\Core\extension_path;


class AutoloadService
{
    public function __construct(private App $app)
    {
    }

    public function load(array $extensionComposerMap, array $extensionNameList): void
    {
        foreach ($extensionComposerMap as $extensionID => $composerFile) {
            if (!isset($composerFile['extra']['sixshop']['class'])) {
                continue;
            }
            $this->app->bind('extension.' . $extensionID, $composerFile['extra']['sixshop']['class']);
        }
        $extensionPath = extension_path();
        $classLoader = $this->app->classLoader;
        foreach ($extensionNameList as $moduleName) {
            $dir = $extensionPath . $moduleName;
            if (!file_exists($dir . '/composer.json')) {
                $namespace = "SixShop\\Extension\\$moduleName\\";
                $path = $dir . '/src';
                if (!isset($classLoader->getPrefixesPsr4()[$namespace])) {
                    $classLoader->addPsr4($namespace, $path);
                    $helperFunctionFile = $dir . "/src/helper.php";
                    if (is_file($helperFunctionFile)) {
                        require_once $helperFunctionFile;
                    }
                }
                $extensionClass = $namespace . 'Extension';
                $this->app->bind('extension.' . $moduleName, $extensionClass);
            }
        }
        foreach ($extensionComposerMap + $extensionNameList as $moduleName => $_) {
            $extension = $this->getExtension($moduleName);
            $extension->boot();
            $this->app->event->trigger('extension.boot', $extension);
        }
    }

    public function getExtension(string $moduleName): ExtensionInterface
    {
        return $this->app->make('extension.' . $moduleName);
    }
}
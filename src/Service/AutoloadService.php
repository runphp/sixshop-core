<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use Composer\Json\JsonFile;
use SixShop\Core\Contracts\ExtensionInterface;
use SixShop\Core\Helper;
use think\App;


class AutoloadService
{
    public function __construct(private App $app)
    {
    }

    public function init(): void
    {
        $extensionPath = Helper::extension_path();
        $classLoader = $this->app->classLoader;
        foreach (Helper::extension_name_list() as $moduleName) {
            $dir = $extensionPath . $moduleName;
            if (file_exists($dir . '/composer.json')) {
                $composerJson = new JsonFile($dir . '/composer.json');
                $composer = $composerJson->read();
                $autoload = $composer['autoload'] ?? [];
                $autoload['psr-4'] = $autoload['psr-4'] ?? [];
                foreach ($autoload['psr-4'] as $namespace => $path) {
                    $classLoader->addPsr4($namespace, $dir . '/' . $path);
                }
                $autoload['files'] = $autoload['files'] ?? [];
                foreach ($autoload['files'] as $file) {
                    require_once $dir . '/' . $file;
                }
                $extensionClass = $composer['extra']['sixshop']['class'];
            } else {
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
            }
            $this->app->bind('extension.' . $moduleName, $extensionClass);
        }
    }

    public function getExtension(string $moduleName): ExtensionInterface
    {
        return $this->app->make('extension.' . $moduleName);
    }
}
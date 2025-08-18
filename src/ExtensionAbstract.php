<?php
declare(strict_types=1);

namespace SixShop\core;

use SixShop\core\Contracts\ExtensionInterface;

abstract class ExtensionAbstract implements ExtensionInterface
{
    private array $info;

    public function getInfo(): array
    {
        if (empty($this->info)) {
            if (!file_exists($this->getBaseDir() . '/info.php')) {
                throw new \Exception('Extension info file not found, please check the extension directory and info.php file.');
            }
            $this->info = require $this->getBaseDir() . '/info.php';
        }
        return $this->info;
    }

    abstract protected function getBaseDir(): string;

    public function getConfig(): array
    {
        if (!file_exists($this->getBaseDir() . '/config.php')) {
            return [];
        }
        return require $this->getBaseDir() . '/config.php';
    }

    public function install(): void
    {
    }

    public function uninstall(): void
    {
    }

    public function getCommands(): array
    {
        if (!file_exists($this->getBaseDir() . '/command.php')) {
            return [];
        }
        return require $this->getBaseDir() . '/command.php';
    }
    public function getHooks(): array
    {
        return  [];
    }

    /**
     * 获取路由
     * @return array<string, string>
     */
    public function getRoutes(): array
    {
        $adminRoute = $this->getBaseDir() . '/route/admin.php';
        $apiRoute = $this->getBaseDir() . '/route/api.php';
        $routes = [];
        if (file_exists($adminRoute)) {
            $routes['admin'] = $adminRoute;
        }
        if (file_exists($apiRoute)) {
            $routes['api'] = $apiRoute;
        }
        return $routes;
    }

    public function getCronJobs(): array
    {
        return [];
    }
}
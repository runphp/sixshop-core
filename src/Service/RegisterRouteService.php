<?php
declare(strict_types=1);

namespace SixShop\core\Service;

use SixShop\core\Middleware\ExtensionStatusMiddleware;
use SixShop\core\SixShopKernel;
use SixShop\Extension\system\ExtensionManager;
use think\event\RouteLoaded;
use think\facade\Route;

class RegisterRouteService
{
    public function init(SixShopKernel $app): void
    {
        $extensionManager = $app->make(ExtensionManager::class);
        $app->event->listen(RouteLoaded::class, function () use ($extensionManager, $app) {
            $appName = $app->http->getName();
            foreach (module_name_list() as $moduleName) {
                $extension = $extensionManager->getExtension($moduleName);
                $routes = $extension->getRoutes();
                if (isset($routes[$appName])) {
                    $routeFile = $routes[$appName];
                    Route::group($moduleName, function () use ($routeFile) {
                        include $routeFile;
                    })->middleware(
                        ExtensionStatusMiddleware::class, $moduleName
                    );
                }
            }
        });
    }
}
<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Core\Helper;
use SixShop\Core\Middleware\ExtensionStatusMiddleware;
use SixShop\System\ExtensionManager;
use think\App;
use think\event\RouteLoaded;
use think\facade\Route;

class RegisterRouteService
{
    public function init(App $app)
    {
        $extensionManager = $app->make(ExtensionManager::class);
        return function () use ($extensionManager, $app) {
            $appName = $app->http->getName();
            foreach (Helper::extension_name_list() as $extensionName) {
                $extension = $extensionManager->getExtension($extensionName);
                $routes = $extension->getRoutes();
                if (isset($routes[$appName])) {
                    $routeFile = $routes[$appName];
                    Route::group($extensionName, function () use ($routeFile) {
                        include $routeFile;
                    })->middleware(
                        ExtensionStatusMiddleware::class, $extensionName
                    );
                }
            }
        };
    }
}
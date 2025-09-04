<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Core\Event\BeforeRegisterRouteEvent;
use SixShop\Core\Helper;
use think\facade\Event;
use think\facade\Route;
use think\Http;

class RegisterRouteService
{
    public function __construct(private AutoloadService $autoloadService, private Http $http)
    {
    }

    public function init(): \Closure
    {
        return function () {
            $appName = $this->http->getName();
            foreach (Helper::extension_name_list() as $extensionName) {
                $extension = $this->autoloadService->getExtension($extensionName);
                if (!$extension->available()) {
                    continue;
                }
                $routes = $extension->getRoutes();
                if (isset($routes[$appName])) {
                    $routeFile = $routes[$appName];
                    $event = new BeforeRegisterRouteEvent();
                    Event::trigger($event);
                    Route::group($extensionName, function () use ($routeFile) {
                        include $routeFile;
                    })->middleware($event->getMiddlewares(), $extensionName);
                }
            }
        };
    }
}
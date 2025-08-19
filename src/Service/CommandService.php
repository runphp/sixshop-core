<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Core\Helper;
use SixShop\Extension\system\ExtensionManager;
use think\App;

class CommandService
{
    public function init(App $app, \Closure $closure): void
    {
        $extensionManager = $app->make(ExtensionManager::class);
        $commands = [];
        foreach (Helper::extension_name_list() as $extensionName) {
            $commands += $extensionManager->getExtension($extensionName)->getCommands();
        }
        $closure($commands);
    }
}
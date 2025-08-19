<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Extension\system\ExtensionManager;

class CommandService
{
    public function init(App $app): void
    {
        $app->re
        $commands = $app->config->get('console.commands', []);
        $extensionManager = $app->make(ExtensionManager::class);
        foreach (module_name_list() as $moduleName) {
            $commands = array_merge($commands, $extensionManager->getExtension($moduleName)->getCommands());
        }
        $app->config->set([
            'commands' => $commands
        ], 'console');
    }
}
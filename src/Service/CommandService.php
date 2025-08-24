<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use SixShop\Core\Helper;
use think\App;

class CommandService
{
    public function __construct(private AutoloadService $autoloadService)
    {
    }

    public function init(\Closure $closure): void
    {
        $commands = [];
        foreach (Helper::extension_name_list() as $extensionName) {
            $commands += $this->autoloadService->getExtension($extensionName)->getCommands();
        }
        $closure($commands);
    }
}
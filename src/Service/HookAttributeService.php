<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use ReflectionClass;
use ReflectionMethod;
use SixShop\Core\Attribute\Hook;
use SixShop\Core\Helper;
use SixShop\Extension\system\Enum\ExtensionStatusEnum;
use SixShop\Extension\system\ExtensionManager;
use think\App;
use think\facade\Event;

class HookAttributeService
{
    public function init(App $app): void
    {
        $extensionManager = $app->make(ExtensionManager::class);
        foreach (Helper::extension_name_list() as $extensionName) {
            if ($extensionManager->getInfo($extensionName)->status !== ExtensionStatusEnum::ENABLED) {
                continue;
            }
            $extension = $extensionManager->getExtension($extensionName);
            $hookClassList = $extension->getHooks();
            foreach ($hookClassList as $hookClass) {
                $ref = new ReflectionClass($hookClass);
                foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    $attributes = $method->getAttributes(Hook::class);
                    foreach ($attributes as $attr) {
                        $hookNameList = (array)$attr->newInstance()->hook;
                        foreach ($hookNameList as $hookName) {
                            Event::listen($hookName, [$hookClass, $method->getName()]);
                        }
                    }
                }
            }
        }
        $app->event->trigger('hook_init', $app);
    }
}
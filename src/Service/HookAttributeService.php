<?php
declare(strict_types=1);

namespace SixShop\core\Service;

use ReflectionClass;
use ReflectionMethod;
use SixShop\core\Attribute\Hook;
use SixShop\core\SixShopKernel;
use SixShop\Extension\system\Enum\ExtensionStatusEnum;
use SixShop\Extension\system\ExtensionManager;
use think\facade\Event;

class HookAttributeService
{
    public function init(SixShopKernel $app): void
    {
        $extensionManager = $app->make(ExtensionManager::class);
        foreach (module_name_list() as $moduleName) {
            if ($extensionManager->getInfo($moduleName)->status !== ExtensionStatusEnum::ENABLED) {
                continue;
            }
            $extension = $extensionManager->getExtension($moduleName);
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
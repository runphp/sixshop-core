<?php
declare(strict_types=1);

namespace SixShop\Core\Service;

use ReflectionClass;
use ReflectionMethod;
use SixShop\Core\Attribute\Hook;
use SixShop\Core\Helper;
use think\App;
use think\Event;

class HookAttributeService
{
    public function __construct(private AutoloadService $autoloadService, private Event $event)
    {
    }

    public function init(): void
    {
        foreach (Helper::extension_name_list() as $extensionName) {
            $extension = $this->autoloadService->getExtension($extensionName);
            $hookClassList = $extension->getHooks();
            foreach ($hookClassList as $hookClass) {
                $ref = new ReflectionClass($hookClass);
                foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                    $attributes = $method->getAttributes(Hook::class);
                    foreach ($attributes as $attr) {
                        $hookNameList = (array)$attr->newInstance()->hook;
                        foreach ($hookNameList as $hookName) {
                            $this->event->listen($hookName, [$hookClass, $method->getName()]);
                        }
                    }
                }
            }
        }
    }
}
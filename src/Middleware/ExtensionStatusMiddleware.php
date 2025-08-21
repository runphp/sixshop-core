<?php
declare(strict_types=1);

namespace SixShop\Core\Middleware;

use Closure;
use SixShop\Core\Helper;
use SixShop\System\Enum\ExtensionStatusEnum;
use SixShop\System\ExtensionManager;

class ExtensionStatusMiddleware
{
    public function __construct(protected ExtensionManager $extensionManager)
    {
    }

    public function handle($request, Closure $next, $moduleName)
    {
        $extensionModel = $this->extensionManager->getInfo($moduleName);
        return match ($extensionModel->status) {
            ExtensionStatusEnum::ENABLED => $next($request),
            default => Helper::error_response(msg: '模块`' . $moduleName . '`未启用', httpCode: 403)
        };
    }
}
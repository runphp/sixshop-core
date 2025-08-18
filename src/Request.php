<?php
declare(strict_types=1);
namespace SixShop\core;

use think\helper\Macroable;

/**
 * @property string $adminID 管理员ID
 * @property string $userID 用户ID
 * @property string $token 令牌
 * @method array pageAndLimit() 分页和每页数量[page, limit]
 */
class Request extends \think\Request
{
    use Macroable;
}
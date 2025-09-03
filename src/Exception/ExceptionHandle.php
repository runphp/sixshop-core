<?php
declare(strict_types=1);
namespace SixShop\Core\Exception;

use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\ValidateException;
use think\Request;
use think\Response;
use Throwable;
use function SixShop\Core\error_response;

class ExceptionHandle extends Handle
{
    public function render(Request $request, Throwable $e): Response
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundException($e);
        }
        if ($e instanceof ValidateException) {
            $e = new LogicException(error_response(msg:$e->getMessage(),  status: 'invalid_argument', code: 400, httpCode: 200));
        }
        return parent::render($request, $e);
    }

    protected function getDebugMsg(Throwable $exception): array
    {
        $debugInfo = parent::getDebugMsg($exception);

        $debugInfo['data'] = $debugInfo['datas'];
        $debugInfo['msg'] = $debugInfo['message'];
        unset($debugInfo['datas'], $debugInfo['message']);


        return $debugInfo;
    }

    protected function getDeployMsg(Throwable $exception): array
    {
        $deployInfo = parent::getDeployMsg($exception);

        $deployInfo['msg'] = $deployInfo['message'];
        unset($deployInfo['message']);

        return $deployInfo;
    }
}
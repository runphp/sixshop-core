<?php
declare(strict_types=1);

namespace SixShop\Core\Exception;

use Exception;
use think\exception\HttpResponseException;
use function SixShop\Core\error_response;

class NotFoundException extends HttpResponseException
{
    public function __construct(Exception|string $e)
    {
        if (is_string($e)) {
            $msg = $e;
        } else {
            $msg = $e->getMessage();
        }
        parent::__construct(error_response(msg: $msg, status: 'not_found', code: 404, httpCode: 200));
    }
}
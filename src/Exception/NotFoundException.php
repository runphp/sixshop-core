<?php

namespace SixShop\core\Exception;

use Exception;
use think\exception\HttpResponseException;

class NotFoundException extends HttpResponseException
{
    public function __construct(Exception $e)
    {
        parent::__construct(error_response(msg:$e->getMessage(),  status: 'not_found', code: 404, httpCode: 200));
    }
}
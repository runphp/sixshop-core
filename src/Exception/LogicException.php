<?php
declare(strict_types=1);

namespace SixShop\Core\Exception;

use think\exception\HttpResponseException;
use think\Response;

class LogicException extends HttpResponseException
{
    public function __construct(protected Response $response)
    {
        parent::__construct($response);
        $this->message = $response->getContent();
    }
}
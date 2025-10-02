<?php
declare(strict_types=1);

namespace SixShop\Core\Exception;

use think\exception\HttpResponseException;
use think\Response;

class LogicException extends HttpResponseException
{
    private string $status;
    public function __construct(protected Response $response)
    {
        parent::__construct($response);
        $this->message = $response->getData()['msg'];
        $this->status = $response->getData()['status'];
    }
    public function getStatus(): string
    {
        return $this->status;
    }
}
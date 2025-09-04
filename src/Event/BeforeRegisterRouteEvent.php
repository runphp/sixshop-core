<?php
declare(strict_types=1);

namespace SixShop\Core\Event;

class BeforeRegisterRouteEvent
{

    public function __construct(private array $middlewares = [])
    {
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function addMiddleware($middleware): void
    {
        $this->middlewares[] = $middleware;
    }
}
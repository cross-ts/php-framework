<?php

declare(strict_types=1);

namespace Http\Middleware;

use SplQueue as Queue;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middleware processor
 */
readonly class Processor implements RequestHandlerInterface
{
    /**
     * Constructor
     * @param RequestHandlerInterface $handler Request handler
     * @param Queue<MiddlewareInterface> $middlewares Middlewares
     */
    public function __construct(
        private RequestHandlerInterface $handler,
        private Queue $middlewares,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->middlewares->isEmpty()) {
            $middleware = $this->middlewares->dequeue();
            return $middleware->process($request, $this);
        }
        return $this->handler->handle($request);
    }
}

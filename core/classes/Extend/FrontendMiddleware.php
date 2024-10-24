<?php

namespace NamelessMC\Framework\Extend;

class FrontendMiddleware extends BaseExtender
{
    private array $middlewares = [];

    public function extend(\DI\Container $container): void
    {
        if ($container->has('FrontendMiddleware')) {
            $middlewares = $container->get('FrontendMiddleware');
        } else {
            $container->set('FrontendMiddleware', $middlewares = []);
        }

        $middlewares = array_merge($middlewares, $this->middlewares);

        $container->set('FrontendMiddleware', $middlewares);
    }

    public function register(string $middleware): self
    {
        $this->middlewares[] = $middleware;

        return $this;
    }
}
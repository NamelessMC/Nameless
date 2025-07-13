<?php

abstract class AbstractMiddleware
{
    public MiddlewareType $type = MiddlewareType::Global;

    public array $exemptRoutes = [];
}

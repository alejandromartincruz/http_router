<?php

namespace Router\Domain;

use Router\Application\MatchRouteUseCase;

class Router
{
    private $matchRouteUseCase;

    public function __construct(
        MatchRouteUseCase $matchRouteUseCase
    )
    {
        $this->matchRouteUseCase = $matchRouteUseCase;
    }

    public function get($route, $method)
    {
        $result = $this->matchRouteUseCase->execute($route);
        $match = $result['match'];
        $args = $result['args'];
        if ($match) {
            echo call_user_func_array($method, array()) . PHP_EOL;
            return;
        }

        throw new \Exception("$route - Route not found: Error 404");
    }
}
<?php


namespace Router\Application;


use Router\Domain\RouteCollection;

class MatchRouteUseCase
{
    private $extractVariableUseCase;

    public function __construct(
        ExtractVariableUseCase $extractVariableUseCase
    )
    {
        $this->extractVariableUseCase = $extractVariableUseCase;
    }

    public function execute(string $requestedRoute): array
    {
        
        $routeCollection = new RouteCollection();
        $routes = $routeCollection->getRouteCollection();

        foreach ($routes as $route) {
            if ($this->isMatch($requestedRoute, $route)) {
                $args = $this->extractVariableUseCase->execute($requestedRoute, $route);
                return array(
                  "match" => true,
                  "args"  => $args
                );
            }
        }

        return array(
                "match" => false,
                "args"  => []
            );

    }

    private function isMatch(string $requestedRoute, string $route): bool
    {
        if ($route === $requestedRoute) {
            return true;
        }

        if ($route === '/') {
            return false;
        }

        $cleanRoute = rtrim($route,"/");
        $cleanRoute = preg_replace('/{[\s\S]+?}/', '', $cleanRoute);

        if (strpos($requestedRoute, $cleanRoute) !== false) {
            return true;
        }

        return false;
    }
}
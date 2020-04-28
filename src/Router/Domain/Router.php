<?php

namespace Router\Domain;

class Router
{

    private $available_routes;

    public function __construct(array $available_routes)
    {
        $this->available_routes = $available_routes;
    }


    public function routeMatch(string $requestedRoute): bool
    {

        foreach ($this->available_routes as $route) {
            if ($this->isMatch($requestedRoute, $route)) {
                return true;
            }
        }

        throw new \Exception("$route - Route not found: Error 404");

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

    public function extractVariables(string $requestedRoute): array
    {
        foreach ($this->available_routes as $route) {
            if ($this->isMatch($requestedRoute, $route)) {
                $regexUri = $route;
                break;
            }
        }

        if (!isset($regexUri)) {
            throw new \Exception("$requestedRoute - Route not found: Error 404");
        }

        $params = [];
        preg_match_all('\'' . '{(\w+)}' . '\'', $regexUri, $matches);

        $matches = $matches[0];

        foreach ($matches as $key => $value)
        {
            $matches[$key] = str_replace('{', '', $matches[$key]);
            $matches[$key] = str_replace('}', '', $matches[$key]);
        }

        $regexUri = preg_replace('%' . '{(\w+)}' . '%', '(\w+|\d+)', $regexUri);

        $regexUri .= '$';
        $regexUri = '%^' . $regexUri . '%';
        $res = preg_match($regexUri, $requestedRoute, $params);

        if (!$res || $res == 0 )
        {
            return array();
        }

        $paramLength = count($matches);
        $keyParams = array();
        for ($i=0; $i < $paramLength; $i++)
        {
            $keyParams[$matches[$i]] = $params[$i+1];
        }

        return $keyParams;
    }
}
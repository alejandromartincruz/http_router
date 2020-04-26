<?php

namespace Router\Domain;

class Router
{
    const ROUTE_COLLECTION = array(
            '/',
            '/post/{id}/',
            '/post/'
        );

    public function get($route, $method)
    {
        $result = $this->match($route);
        $match = $result['match'];
        $args = $result['args'];
        if ($match) {
            return call_user_func_array($method, $args);
        }

        throw new \Exception("$route - Route not found: Error 404");
    }

    public function match(string $requestedRoute): array
    {
        $routes = self::ROUTE_COLLECTION;

        foreach ($routes as $route) {
            if ($this->isMatch($requestedRoute, $route)) {
                $args = $this->extractVariable($requestedRoute, $route);
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

    public function extractVariable($uri, $regexUri): array
    {
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
        $res = preg_match($regexUri, $uri, $params);

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
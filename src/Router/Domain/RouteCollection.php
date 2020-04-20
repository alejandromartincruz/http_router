<?php


namespace Router\Domain;


class RouteCollection
{
    private $routeCollection;

    public function __construct()
    {
        $this->routeCollection = array(
            '/',
            '/post/{id}/',
            '/post/'
        );
    }

    /**
     * @return array
     */
    public function getRouteCollection(): array
    {
        return $this->routeCollection;
    }

}
<?php

namespace Elodie\Router;


/**
 *  Class RouterTest
 * @package Elodie/Router
 */
class Router {

    /**
     * @var Route[]
     */
    private array $routes = [];

    /**
     * @param Route $route
     * @return $this
     */
    public function add(Route $route): self {
        $this->routes[$route->getName()] = $route;
        return  $this;
    }

    public function get(string $name): ?Route {
        foreach ($this->routes as $route) {
            if($route->getName() ===$name) {
                return $route;
            }
        }
        throw new RouteNotFoundException();
    }

    /**
     * @return array|Route[]
     */
    public  function getRouteCollection(): array {
        return $this->routes;
    }

}
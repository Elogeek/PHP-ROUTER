<?php

namespace Elodie\Router;

use function PHPUnit\Framework\throwException;

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
     * Return path url
     * @param string $path
     * @return Route
     */
    public function match(string $path): Route {
        foreach ($this->routes as $route) {
            if ($route->test($path)) {
                return $route;
            }

        }
        throw new RouteNotFoundException();
    }

    /**
     * Shortcut the function match and call $path
     * @param string $path
     * @return false|mixed
     * @throws \ReflectionException
     */
    public function call(string $path) {
        return $this->match($path)->call($path);
    }

    /**
     * @param Route $route
     * @return $this
     * @throws RouteAlreadyExistsException
     */
    public function add(Route $route): self {
        if ($this->has($route->getName())) {
            throw new RouteAlreadyExistsException();
        }
        $this->routes[$route->getName()] = $route;
        return $this;
    }

    /**
     * @param string $name
     * @return Route
     */
    public function get(string $name): Route {
        if (!$this->has($name)) {
            throw new RouteNotFoundException();
        }
        return $this->routes[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool {
        return isset($this->routes[$name]);
    }

    /**
     * @return Route[]
     */
    public function getRouteCollection(): array {
        return $this->routes;
    }
}
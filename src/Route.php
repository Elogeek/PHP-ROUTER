<?php

namespace Elodie\Router;

use ReflectionClass;
use ReflectionFunction;
use ReflectionParameter;

/**
 *  Class RouterTest
 * @package Elodie/Router
 */
class Route {

    /**
     * @var string
     */
    private  string $name;
    private  string $path;

    /**
     * @var array|callable
     */
    private $callable;

    /**
     * @param string $name
     * @param string $path
     * @param array|callable $callable
     */
    public function __construct(string $name, string $path, callable|array $callable)
    {
        $this->name = $name;
        $this->path = $path;
        $this->callable = $callable;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return array|callable
     */
    public function getCallable(): callable|array {
        return $this->callable;
    }

    /**
     * @param array|callable $callable
     */
    public function setCallable(callable|array $callable): void {
        $this->callable = $callable;
    }

    // Transform the path into a regex to retrieve its id here
    public function test(string $path): bool {
        $pattern = str_replace("/", "\/", $this->path);
        $pattern = sprintf("/^%s$/", $pattern);
        $pattern = preg_replace("/(\{\w+\})/", "(.+)", $pattern);
        return preg_match($pattern, $path);
    }

    /**
     * @param string $path
     * @return false|mixed
     * @throws \ReflectionException
     */
    public function call(string $path) {
        $pattern = str_replace("/", "\/", $this->path);
        $pattern = sprintf("/^%s$/", $pattern);
        $pattern = preg_replace("/(\{\w+\})/", "(.+)", $pattern);
        preg_match($pattern, $path, $matches);

        // Delete a first element of the table
        array_shift($matches);

        preg_match_all("/\{(\w+)\}/", $this->path, $paramMatches);

        // Pops an element at the start of an array, delete the 1st element
        $parameters = $paramMatches[1];

        $argsValue = [];

        if (count($parameters) > 0) {

            $parameters = array_combine($parameters, $matches);
            if (is_array($this->callable)) {
                $reflectionFunc = (new ReflectionClass($this->callable[0]))->getMethod($this->callable[1]);
            }

            else {
                $reflectionFunc = new ReflectionFunction($this->callable);
            }
            // Get the parameters from the array in the correct order
            $args = array_map(fn (ReflectionParameter $param) => $param->getName(), $reflectionFunc->getParameters());
            $argsValue = array_map(function (string $name) use ($parameters) {
                return $parameters[$name];
            },$args);
        }

        $callable = $this->callable;

        if (is_array($callable)) {
            $callable = [new $callable[0](), $callable[1]];
        }

        return call_user_func_array($callable, $argsValue);
    }

}
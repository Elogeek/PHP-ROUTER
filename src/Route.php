<?php

namespace Elodie\Router;

/**
 *  Class RouterTest
 * @package Elodie/Router
 */
class Route {

    /**
     * @var string
     */
    private  string $name;

    /**
     * @var string
     */
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
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void {
        $this->path = $path;
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
}
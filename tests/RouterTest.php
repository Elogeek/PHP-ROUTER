<?php
namespace Elodie\Router\Tests;

use Elodie\Router\Route;
use Elodie\Router\RouteAlreadyExistsException;
use Elodie\Router\RouteNotFoundException;
use Elodie\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 *  Class RouterTest
 * @package Elodie/Router/tests
 */
class RouterTest extends TestCase{

public function test() {

    $router = new Router();

    $route = new Route("home","/",function () {
        echo "Hello world !";
    });

    $router->add($route);

    $this->assertCount(1,$router->getRouteCollection());
    $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());
    $this->assertEquals($route, $router->get("home"));

    // Check if route no exist
    $this->expectException(RouteNotFoundException::class);
    $this->assertEquals($route, $router->get("fail"));

    // Check if route exist
    $this->expectException(RouteAlreadyExistsException::class);
    $this->assertEquals($route, $router->add(new Route("home", "/", function () {})));
}

}
<?php
namespace Elodie\Router\Tests;

use Elodie\Router\Route;
use Elodie\Router\RouteAlreadyExistsException;
use Elodie\Router\RouteNotFoundException;
use Elodie\Router\Router;
use Elodie\Routeur\Tests\Fixtures\FooController;
use Elodie\Routeur\Tests\Fixtures\HomeController;
use PHPUnit\Framework\TestCase;

/**
 *  Class RouterTest
 * @package Elodie/Router/tests
 */
class RouterTest extends TestCase {

    /**
     * Test
     */
    public function test() {

        $router = new Router();

        $routeHome = new Route("home", "/", [HomeController::class, "index"]);
        $routeFoo = new Route("foo", "/foo/{bar}", [FooController::class, "bar"]);
        $routeArticle = new Route("article", "/blog/{id}/{slug}", function(string $slug, string $id) {
            // concat
            return sprintf("%s : %s", $id, $slug);
        });

        $router->add($routeHome);
        $router->add($routeFoo);
        $router->add($routeArticle);


        $this->assertCount(3, $router->getRouteCollection());
        $this->assertContainsOnlyInstancesOf(Route::class, $router->getRouteCollection());

        // Redirection to the page (index)
        $this->assertEquals($routeHome, $router->get("home"));
        $this->assertEquals($routeHome, $router->match("/"));
        $this->assertEquals($routeArticle, $router->match("/blog/12/mon-article"));

        $this->assertEquals("Hello world ! ", $router->call("/"));
        $this->assertEquals("12 : mon-article", $router->call("/blog/12/mon-article"));
        $this->assertEquals("bar", $router->call("/foo/bar"));

    }

    public function testIfRouteNotFoundByMatch() {
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->match("/");
    }


    // Check if route no exist
    public function testIfRouteNotFoundByGet() {
        $router = new Router();
        $this->expectException(RouteNotFoundException::class);
        $router->get("fail");
    }


    // Check if route exist
    public function testIfRouteAlreadyExists() {
        $router = new Router();
        $router->add(new Route("home", "/", function () {}));
        $this->expectException(RouteAlreadyExistsException::class);
        $router->add(new Route("home", "/", function () {}));
    }


}
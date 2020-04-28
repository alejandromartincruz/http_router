<?php


namespace RouterUnit\Domain;

use PHPUnit\Framework\TestCase;
use Router\Application\MatchRouteUseCase;
use Router\Domain\Router;

class RouterTest extends TestCase
{
    private $matchRouteUseCase;

    private $router;

    public function setUp(): void
    {
        $available_routes = array(
            '/',
            '/post/{id}/',
            '/post/'
        );

        $this->router = new Router($available_routes);
    }

    /** @test */
    public function checkNotExistingRouteThrowsException()
    {
        $this->expectException(\Exception::class);

        $this->router->routeMatch('/noexiste');
    }

    /** @test */
    public function checkExistingRouteReturnsTrue()
    {

        $this->assertTrue($this->router->routeMatch('/post'));

    }

    /** @test */
    public function checkExistingRouteWithParamReturnsTrue()
    {

        $this->assertTrue($this->router->routeMatch('/post/5/'));

    }

    /** @test */
    public function routeWithNoParamsReturnsEmptyArray()
    {

        $this->assertIsArray($this->router->extractVariables('/post'));
        $this->assertCount(0, $this->router->extractVariables('/post'));

    }

    /** @test */
    public function routeWithOneParamReturnsArrayWithOneElement()
    {

        $this->assertIsArray($this->router->extractVariables('/post/5/'));
        $this->assertCount(1, $this->router->extractVariables('/post/5/'));

    }

    /** @test */
    public function routeWithMoreParamsThanAcceptedReturnsEmptyArray()
    {

        $this->assertIsArray($this->router->extractVariables('/post/5/18/'));
        $this->assertCount(0, $this->router->extractVariables('/post/5/18/'));

    }

    /** @test */
    public function routeWithOneParamWithoutLastBackslashReturnsEmptyArray()
    {

        $this->assertIsArray($this->router->extractVariables('/post/5'));
        $this->assertCount(0, $this->router->extractVariables('/post/5'));

    }

}
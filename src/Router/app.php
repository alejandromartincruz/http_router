<?php

namespace Router;

require __DIR__.'/../../vendor/autoload.php';

use Router\Application\ExtractVariableUseCase;
use Router\Application\MatchRouteUseCase;
use Router\Domain\Router;

$router = new Router(new MatchRouteUseCase(new ExtractVariableUseCase()));

$router->get('/post/5/', function() {
    return <<<HTML
  <h1>Post 5</h1>
HTML;
});

$router->get('/', function() {
    return <<<HTML
  <h1>Hello world</h1>
HTML;
});


$router->get('/post', function() {
    return <<<HTML
  <h1>Post</h1>
HTML;
});

$router->get('/noexiste', function() {
    return <<<HTML
  <h1>Post</h1>
HTML;
});
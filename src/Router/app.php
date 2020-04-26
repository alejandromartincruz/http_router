<?php

// este archivo no se pide para la practica, se ha generado exclusivamente para comprobar el correcto funcionamiento de la clase Router antes de desarrollar los tests

namespace Router;

require __DIR__.'/../../vendor/autoload.php';

use Router\Domain\Router;

$router = new Router();

$router->get('/post/67/', function($args) {
    return <<<HTML
  <h1>Post $args</h1>
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
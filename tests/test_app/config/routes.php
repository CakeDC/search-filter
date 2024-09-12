<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {

        // $builder->connect('/articles', ['controller' => 'Articles', 'action' => 'index']);
        // $builder->connect('/articles/search', ['controller' => 'Articles', 'action' => 'search']);

        $builder->fallbacks();
    });
};

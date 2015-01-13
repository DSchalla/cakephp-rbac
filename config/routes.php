<?php
use Cake\Routing\Router;

Router::plugin('RBAC', ['path' => '/rbac'], function ($routes) {
    $routes->fallbacks('InflectedRoute');
});


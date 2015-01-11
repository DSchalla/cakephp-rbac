<?php
use Cake\Routing\Router;

Router::plugin('Schalla/RBAC', ['path' => '/rbac'], function ($routes) {
    $routes->fallbacks('InflectedRoute');
});

<?php
use Cake\Routing\Router;

Router::prefix('Backend', function ($routes) {
    $routes->plugin('RBAC', ['path' => '/rbac'], function ($routes) {
        $routes->fallbacks();
    });
});

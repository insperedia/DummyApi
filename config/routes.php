<?php

$routesPath = __DIR__ . DIRECTORY_SEPARATOR . 'routes';
$routeFiles = scandir($routesPath);
$routes = [];

foreach ($routeFiles as $fileName) {
    if ($fileName === '.' || $fileName === '..') {
        continue;
    }

    $routes = array_merge($routes, require_once $routesPath . DIRECTORY_SEPARATOR . $fileName);
}

return $routes;

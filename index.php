<?php
$routes = include 'routes.php';

if (!$routes || !is_array($routes)) {
    die('Error: Routes configuration not found or invalid.');
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (array_key_exists($uri, $routes)) {
    $controller = $routes[$uri];

    if (file_exists($controller)) {
        require $controller;
    } else {
        http_response_code(500);
        echo "500 - Internal Server Error: Controller not found.";
    }
} else {
    http_response_code(404);
    echo "404 - Page not found";
}

<?php

require_once 'routes/web.php';
require_once 'functions/main.php';
require_once 'helpers/Validator.php';


session_start();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

$routeFound = false;

foreach ($routes as $route) {
    $routePattern = preg_replace('/:\w+/', '(\w+)', $route['path']); 
    $routePattern = "#^" . $routePattern . "$#"; 

    if ($route['method'] === $requestMethod && preg_match($routePattern, $requestUri, $matches)) {
        $routeFound = true;
        if (!isset($route['file']) && !isset($route['requirements'])){
            
        array_shift($matches); 
        call_user_func_array($route['handler'], $matches);
        break;
        
        }else{
            if ($route['requirements']()){
                render($route['file']);
            }

        }
    }
}

if (!$routeFound) {
    http_response_code(404);
    render ('errorPage.php');
}




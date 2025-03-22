<?php

require_once 'controllers/routesController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/RequestController.php';
require_once 'controllers/AiController.php';


$routes = [
    
    [
        'method' => 'GET',
        'path' => '/',
        'handler' => 'mainPage',
    ],
    [
        'method' => 'GET',
        'path' => '/register',
        'handler' => 'registerPage',
    ],
    [
        'method' => 'GET',
        'path' => '/login',
        'handler' => 'loginPage',
    ],

    [
        'method' => 'POST',
        'path' => '/register',
        'handler' => 'registerUser',
    ],

    [
        'method' => 'POST',
        'path' => '/login',
        'handler' => 'loginUser',
    ],

    [
        'method' => 'GET',
        'path' => '/c/public',
        'handler' => 'publicChat',
    ],


    [
        'method' => 'GET',
        'path' => '/c/:id',
        'handler' => 'chatUser',
    ],


    [
        'method' => 'GET',
        'path' => '/logout',
        'handler' => 'logout',
    ],


    
    [
        'method' => 'POST',
        'path' => '/request/manage',
        'handler' => 'manageRequest',
    ],


    [
        'method' => 'POST',
        'path' => '/users/search',
        'handler' => 'searchUser',
    ],

    [
        'method' => 'POST',
        'path' => '/user/request',
        'handler' => 'makeRequest',
    ],


    [
        'method' => 'POST',
        'path' => '/user/profile/edit',
        'handler' => 'editProfile',
    ],

    [
        'method' => 'GET',
        'path' => '/ai/chatgpt',
        'handler' => 'getGPTChat',
    ],

    [
        'method' => 'POST',
        'path' => '/ai/gpt/',
        'handler' => 'chatGPT',
    ],

    






];

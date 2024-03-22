<?php

use App\Controllers\UserController;
    
$routes = [
    'GET' => [
        '/api/users' => ['UserController@index'],
        '/api/users/{id}' => ['UserController@show'],
    ],
    'DELETE' => [
        '/api/users/{id}' => ['UserController@delete'],
    ],
    'POST' => [
        '/api/users/store' => ['UserController@store'],
    ],
    'PUT' => [
        '/api/users/{id}' => ['UserController@update'],
    ]
];
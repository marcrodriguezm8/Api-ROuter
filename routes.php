<?php

use App\Controllers\UserController;

$routes = [

    '/api/users' => ['GET' => 'UserController@index'],
    '/api/users/{id}' => [
                            'GET' => 'UserController@show',
                            'DELETE' => 'UserController@delete',
                            'PUT' => 'UserController@update',
                        ],

    '/api/users/{name}' => ['GET' => 'UserController@showName'],
    '/api/users/store' => ['POST' => 'UserController@store'],


];

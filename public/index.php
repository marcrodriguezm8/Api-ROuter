<?php
use App\Request;
use App\Router;
require '../routes.php';
require '../bootstrap.php';


try{
    $request = new Request();

    $router = new Router($request, $routes);
}
catch(\Exception $e){
    echo $e->getMessage();
}


<?php 

require __DIR__.'/vendor/autoload.php';

use App\Database\QueryBuilder;
    use App\Database\Connection;
    use App\Registry;
    // register all the services
    

    Registry::bind('config', require 'config.php');
    
    Registry::bind('database', new QueryBuilder(
        Connection::make(Registry::get('config'))
    ));
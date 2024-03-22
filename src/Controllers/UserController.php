<?php 

namespace App\Controllers;

use App\Request;
use App\Registry;

class UserController {
    function index(Request $request){
        //todos los users
        $users = Registry::get('database')
        ->selectAll('users')
        ->get();

        var_dump($users);
        
    }

    function show(Request $request){
        
        $id = $request->parameters()[0];
        $user = Registry::get('database')
        ->select('users', ['id', 'name'])
        ->condition(['id'], 'users', [$id], '=')
        ->get();
        
        var_dump($user);

    }
    function store(Request $request){
       
        $values = $request->parameters();
        Registry::get('database')
        ->insert('users', $values)
        ->get();

        echo ("User añadido correctamente\n");

    }

    function delete(Request $request){
        $id = $request->parameters()[0];
        Registry::get('database')
        ->delete('users')
        ->condition(['id'], 'users', [$id], '=')
        ->get();

        echo "Usuario eliminado\n";

    }
    function update(Request $request){
        
        $id = $request->parameters()[0];
        $values = $request->parameters()[1];
        
        Registry::get('database')
        ->update('users', $values)
        ->condition(['id'], 'users', [$id], '=')
        ->get();

        echo "Usuario actualizado\n";
    }
}
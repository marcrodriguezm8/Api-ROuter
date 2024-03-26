<?php 

namespace App\Controllers;

use App\Request;
use App\Registry;

class UserController {
    function index(Request $request){
        //todos los users
        try{
            $users = Registry::get('database')
            ->selectAll('users')
            ->get();

            var_dump($users);
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
        
    }

    function show(Request $request){
        try{
            $id = $request->parameters()['id'];
            
            $user = Registry::get('database')
                ->select('users', ['id', 'name', 'role'])
                ->condition(['id'], 'users', [$id], '=')
                ->get();
            if(sizeof($user) != 0) var_dump($user);
            else throw new \Exception("Usuario no encontrado\n");
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }
    function showName(Request $request){
        try{
            $name = $request->parameters()['name'];
            $user = Registry::get('database')
                ->select('users', ['id', 'name', 'role'])
                ->condition(['name'], 'users', [$name], '=')
                ->get();

            if(sizeof($user) != 0) var_dump($user);
            else throw new \Exception("Usuario no encontrado\n");
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }
    function store(Request $request){
       
        try{
            $values = $request->parameters()['input'];

            Registry::get('database')
                ->insert('users', $values)
                ->get();
            echo ("User añadido correctamente\n");
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }

        

    }

    function delete(Request $request){
        try{
            $id = $request->parameters()['id'];
            Registry::get('database')
                ->delete('users')
                ->condition(['id'], 'users', [$id], '=')
                ->get();

            echo "Usuario eliminado\n";
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }

    }
    function update(Request $request){
        
        try{

            $id = $request->parameters()['id'];
            $values = $request->parameters()['input'];
            
            Registry::get('database')
            ->update('users', $values)
            ->condition(['id'], 'users', [$id], '=')
            ->get();

            echo "Usuario actualizado\n";
        }
        catch(\Exception $e){
            echo $e->getMessage();
        }
    }
}
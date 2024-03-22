<?php
namespace App\Controllers;

class BaseController {
    protected $request;

    public function __construct($request) {
        $this->request = $request;
    }

    public function show($id) {
        // Lógica para mostrar un recurso por su ID
    }

    public function store() {
        // Lógica para almacenar un nuevo recurso
    }

    public function update($id) {
        // Lógica para actualizar un recurso por su ID
    }

    public function destroy($id) {
        // Lógica para eliminar un recurso por su ID
    }
}

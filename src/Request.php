<?php


namespace App;

class Request {
    protected string $uri;
    protected string $method;

    protected array $parameters = [];

    public function __construct(){
        $this->uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    public function uri(){
        return $this->uri;
    }

    public function method(){
        return $this->method;
    }
    public function parameters(){
        return $this->parameters;
    }
    public function setParameters($parameters){
        $this->parameters[] = $parameters;
    }
}

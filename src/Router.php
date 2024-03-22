<?php

namespace App;

class Router
{
    protected Request $request;
    protected string $controllerName;
    protected string $methodName;
    protected array $routes;
    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->routes = $routes;
        $this->router();
    }

    protected function router()
    {
        //dump($this->request->uri());

        //dump(array_keys($this->routes));
        $parts = explode('/', trim($this->request->uri(), '/'));
        $routes = $this->routes[$this->request->method()];
        if (!preg_match('/\d/', $this->request->uri())) {

            if (in_array($this->request->uri(), array_keys($routes))) {

                //dump($this->routes[$this->request->uri()]);
                $class = explode('@', $routes[$this->request->uri()][0]);
                $controllerClass = "\\App\\Controllers\\" . $class[0];
                $action = $class[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();

                    if (method_exists($controller, $action)) {
                        switch ($this->request->method()) {
                            case 'GET':
                                $this->request->setParameters($_GET);
                                break;
                            case 'POST':
                                $result = json_decode(file_get_contents('php://input'), true);
                                if ($result == null) {
                                    $result = [];
                                    parse_str(file_get_contents('php://input'), $result);
                                }
                                $this->request->setParameters($result);
                                break;
                            case 'PUT':
                            case 'DELETE':
                                $result = [];
                                parse_str(file_get_contents('php://input'), $result);
                                $this->request->setParameters($result);
                                break;
                        }

                        $controller->$action($this->request);
                    }
                }
            } else throw new \Exception('El método ' . $this->request->method() . ' no está permitido para esta ruta');
        } else {
            $path = '/' . $parts[0] . '/' . $parts[1];

            $matches = array_filter(
                $routes,
                function ($key) use ($path) {
                    return strpos($key, $path) !== false;
                },
                ARRAY_FILTER_USE_KEY
            );

            if (sizeof($matches) != 0) {

                $keys = array_keys($matches);
                $route = "";
                foreach ($keys as $element) {
                    if (strpos($element, '{') !== false) {
                        $route = $element;
                        break;
                    }
                }
                $class = explode('@', $routes[$route][0]);
                $controllerClass = "\\App\\Controllers\\" . $class[0];
                $action = $class[1];



                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();

                    if (method_exists($controller, $action)) {
                        switch ($this->request->method()) {
                            case 'GET':

                                $this->request->setParameters($parts[2]);
                                break;
                            case 'POST':
                                $result = json_decode(file_get_contents('php://input'), true);
                                if ($result == null) {
                                    $result = [];
                                    parse_str(file_get_contents('php://input'), $result);
                                }
                                $this->request->setParameters($result);
                                break;
                            case 'PUT':
                                $result = json_decode(file_get_contents('php://input'), true);
                                if ($result == null) {
                                    $result = [];
                                    parse_str(file_get_contents('php://input'), $result);
                                }
                                $this->request->setParameters($parts[2]);
                                $this->request->setParameters($result);
                                break;
                            case 'DELETE':
                                $this->request->setParameters($parts[2]);
                                break;
                        }

                        $controller->$action($this->request);
                    }
                }
            } else throw new \Exception('El método ' . $this->request->method() . ' no está permitido para esta ruta');
        }
    }
}

function parseUri($uri)
{
}

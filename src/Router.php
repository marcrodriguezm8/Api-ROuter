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

        //$routes = $this->routes[$this->request->method()];
        $checkRoute = $this->searchRoutes($this->request->uri(), $this->routes);
        
        if ($checkRoute) {
            $routeData = $this->routes[$checkRoute['route']];
            
            if (in_array($this->request->method(), array_keys($routeData))) {
    
                $class = explode('@', $routeData[$this->request->method()]);
                $controllerClass = "\\App\\Controllers\\" . $class[0];
                $action = $class[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    
                    if (method_exists($controller, $action)) {

                        switch ($this->request->method()) {
                            case 'GET':
                                if ($checkRoute['value']) $param = $checkRoute['value'];
                                else $param = $_GET;
                                $this->request->setParameters($param);
                                break;
                            case 'POST':
                                $result = json_decode(file_get_contents('php://input'), true);
                                if ($result == null) {
                                    $result = [];
                                    parse_str(file_get_contents('php://input'), $result);
                                }
                                $this->request->setParameters(['input' => $result]);
                                break;
                            case 'PUT':
                                if ($checkRoute['value']) {
                                    $param = $checkRoute['value'];
                                    $this->request->setParameters($param);
                                }

                                $result = json_decode(file_get_contents('php://input'), true);
                                if ($result == null) {
                                    $result = [];
                                    parse_str(file_get_contents('php://input'), $result);
                                }
                                
                                $this->request->setParameters(['input' => $result]);
                                break;
                            case 'DELETE':
                                if ($checkRoute['value']) {
                                    $param = $checkRoute['value'];
                                    $this->request->setParameters($param);
                                }

                                break;
                        }

                        $controller->$action($this->request);
                    }
                }
            } else throw new \Exception('El método ' . $this->request->method() . ' no está permitido para ' . $this->request->uri());
        } else throw new \Exception('La ruta no existe');
    }
    function searchRoutes($uri, $routes)
    {
        $uriParts = explode('/', trim($uri, '/'));
        $uriSize = sizeof($uriParts);

        if (in_array($uri, array_keys($routes))) return ['route' => $uri];

        foreach ($routes as $route => $value) {
            $routeParts = explode('/', trim($route, '/'));
            $routeSize = sizeof($routeParts);

            if ($uriSize == $routeSize) {

                $compareResult = $this->compareParts($uriParts, $routeParts);

                if ($compareResult !== false) {
                    return array_merge(['route' => $route], $compareResult);
                }
            }
        }


        return false;
    }
    function compareParts($uriParts, $routeParts)
    {
        $coincidences = 0;

        $validations = [
            'id' => function ($id) {
                return preg_match('/^\d+$/', $id);
            },
            'name' => function ($name) {
                return true;
            }
        ];

        $params = [];
        for ($i = 0; $i < sizeof($uriParts); $i++) {
            
            if ($uriParts[$i] == $routeParts[$i]) {

                $coincidences++;
            } else if (array_key_exists(str_replace(['{', '}'], '', $routeParts[$i]), $validations)) {
                
                if ($validations[str_replace(['{', '}'], '', $routeParts[$i])]($uriParts[$i])) {           
                    $coincidences++;
                    $params = ['value' => [str_replace(['{', '}'], '', $routeParts[$i]) => $uriParts[$i]]];
                }
            } else $i = sizeof($uriParts);
        }

        if ($coincidences == sizeof($routeParts)) return $params;

        return false;
    }
}

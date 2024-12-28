<?php

// class Router {

//     // Map of route paths to controller methods
//     private $routes = [];

//     // Register a route with its HTTP method
//     public function add($method, $route, $controllerMethod) {
//         $this->routes[] = ['method' => $method, 'route' => $route, 'controllerMethod' => $controllerMethod];
//     }

//     // Match the current request URL with a registered route
//     public function dispatch() {
//         $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  // Get the current URI
//         $method = $_SERVER['REQUEST_METHOD'];  // Get the current HTTP method

//         foreach ($this->routes as $route) {
//             // Check if the route matches the request method and URI
//             if ($method == $route['method'] && $uri == $route['route']) {
//                 list($controller, $method) = explode('@', $route['controllerMethod']);
//                 $controller = new $controller();
//                 $controller->$method();
//                 return;
//             }
//         }

//         // If no match is found, show a 404 error
//         echo "404 - Not Found";
//     }
// }
?>

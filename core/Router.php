<?php

class Router {
    private $routes = [];
    private $middlewares = [];
    
    public function __construct() {
        
    }
    
    public function get($path, $handler, $middleware = []) {
        $this->addRoute('GET', $path, $handler, $middleware);
    }
    
    public function post($path, $handler, $middleware = []) {
        $this->addRoute('POST', $path, $handler, $middleware);
    }
    
    public function put($path, $handler, $middleware = []) {
        $this->addRoute('PUT', $path, $handler, $middleware);
    }
    
    public function delete($path, $handler, $middleware = []) {
        $this->addRoute('DELETE', $path, $handler, $middleware);
    }
    
    private function addRoute($method, $path, $handler, $middleware) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }
    
    public function dispatch(Request $request) {
        $method = $request->getMethod();
        $path = $request->getPath();
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $params = $this->extractParams($route['path'], $path);
                
                // Execute middleware
                foreach ($route['middleware'] as $middlewareClass) {
                    if (!class_exists($middlewareClass)) {
                        throw new Exception("Middleware class not found: $middlewareClass");
                    }
                    $middleware = new $middlewareClass();
                    $result = $middleware->handle($request);
                    if ($result !== true) {
                        return $result;
                    }
                }
                
                // Execute handler
                if (is_string($route['handler'])) {
                    list($controller, $method) = explode('@', $route['handler']);
                    if (!class_exists($controller)) {
                        throw new Exception("Controller class not found: $controller");
                    }
                    $controllerInstance = new $controller();
                    if (!method_exists($controllerInstance, $method)) {
                        throw new Exception("Method not found: $controller::$method");
                    }
                    $result = $controllerInstance->$method($request, ...$params);
                    if ($result === null) {
                        throw new Exception("Controller method returned null: $controller::$method");
                    }
                    return $result;
                } elseif (is_callable($route['handler'])) {
                    return call_user_func($route['handler'], $request, ...$params);
                }
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        return new Response('Page not found', 404);
    }
    
    private function matchPath($routePath, $requestPath) {
        $routePath = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePath = '#^' . $routePath . '$#';
        return preg_match($routePath, $requestPath);
    }
    
    private function extractParams($routePath, $requestPath) {
        preg_match_all('/\{([^}]+)\}/', $routePath, $paramNames);
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        preg_match('#^' . $routePattern . '$#', $requestPath, $paramValues);
        
        array_shift($paramValues);
        
        // URL decode parameters to handle spaces and special characters
        return array_map('urldecode', $paramValues);
    }
}

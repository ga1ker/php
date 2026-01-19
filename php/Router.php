<?php
// router.php
require_once "./Database.php";

class Router {
    private array $routes = [];
    
    public function get(string $path, callable $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void {
        $this->routes['PUT'][$path] = $handler;
    }

    public function route(): void {
        header('Content-Type: application/json; charset=utf-8');
        
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        if (isset($this->routes[$method][$path])) {
            call_user_func($this->routes[$method][$path]);
        } else {
            $this->notFound();
        }
    }

    private function notFound(): void {
        http_response_code(404);
        echo json_encode(["status" => "error", "code" => 404, "answer" => "Ruta no encontrada"]);
    }
}

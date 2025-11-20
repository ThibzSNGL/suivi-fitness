<?php
// backend/core/Router.php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../config/routes.php';

/**
 * Router très simple basé sur un paramètre ?route=/...
 */
class Router
{
    private array $routes;

    public function __construct()
    {
        // routes.php retourne un tableau avec 'GET' et 'POST'
        $this->routes = require __DIR__ . '/../config/routes.php';
    }

    /**
     * Gère une requête.
     *
     * @param string $path   ex: "/", "/login"
     * @param string $method ex: "GET", "POST"
     */
    public function handle(string $path, string $method): void
    {
        $method = strtoupper($method);
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 - Page non trouvée ({$path})";
            return;
        }

        [$controllerName, $action] = $this->routes[$method][$path];

        $this->dispatch($controllerName, $action);
    }

    /**
     * Charge le bon contrôleur et appelle la bonne méthode.
     */
    private function dispatch(string $controllerName, string $action): void
    {
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(500);
            echo "Controller introuvable : " . htmlspecialchars($controllerFile);
            return;
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            http_response_code(500);
            echo "Classe contrôleur introuvable : " . htmlspecialchars($controllerName);
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo "Méthode {$action} inexistante dans {$controllerName}";
            return;
        }

        $controller->$action();
    }
}

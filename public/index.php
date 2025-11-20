<?php
// public/index.php

session_start(); // On démarre la session

// Connexion BDD (utilisée plus tard dans les contrôleurs)
require_once __DIR__ . '/../backend/config/db.php';

// Router
require_once __DIR__ . '/../backend/core/Router.php';

// On récupère la route depuis ?route=...
$route = $_GET['route'] ?? '/';

// On instancie et on délègue au Router
$router = new Router();
$router->handle($route, $_SERVER['REQUEST_METHOD']);

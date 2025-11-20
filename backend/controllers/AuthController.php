<?php
// backend/controllers/AuthController.php

require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller
{
    public function login(): void
    {
        // Plus tard, on pourra récupérer un éventuel message d'erreur de session
        $this->render('auth/login', [
            'title' => 'Connexion',
            'error' => null,
            'old'   => [],
        ]);
    }

    public function register(): void
    {
        $this->render('auth/register', [
            'title' => 'Inscription',
            'error' => null,
            'old'   => [],
        ]);
    }

    public function doLogin(): void
    {
        // Ici on traitera le POST (validation + vérification en BDD)
        // Pour l'instant, on va juste afficher les données reçues (debug).
        echo "<pre>";
        echo "Traitement de la connexion (doLogin)\n\n";
        var_dump($_POST);
        echo "</pre>";
    }

    public function doRegister(): void
    {
        echo "<pre>";
        echo "Traitement de l'inscription (doRegister)\n\n";
        var_dump($_POST);
        echo "</pre>";
    }

    public function logout(): void
    {
        // On implémentera la déconnexion plus tard
        echo "Déconnexion à implémenter.";
    }
}

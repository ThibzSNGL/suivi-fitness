<?php
// backend/controllers/DashboardController.php

require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
         // 🔒 Protection : si pas connecté -> redirection login
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?route=/login');
            exit;
        }

        $userPseudo = $_SESSION['user_pseudo'] ?? 'Utilisateur';

        // Pour l'instant, simple texte. On fera une vraie vue plus tard.
        echo "<h1>Dashboard</h1>";
        echo "<p>Bienvenue, " . htmlspecialchars($userPseudo) . " 👋</p>";
        echo '<p><a href="index.php?route=/logout">Se déconnecter</a></p>';
    }
}

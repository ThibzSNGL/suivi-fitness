<?php
// backend/controllers/DashboardController.php

require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        echo "<h1>Dashboard</h1>";
        echo "<p>Le router fonctionne ✔</p>";
        echo '<p><a href="index.php?route=/login">Aller à la page de connexion</a></p>';
    }
}

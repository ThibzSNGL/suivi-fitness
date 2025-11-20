<?php
// backend/core/Controller.php

abstract class Controller
{
    /**
     * Rend une vue simple (on fera le layout plus tard).
     * Exemple : render('auth/login', ['title' => 'Connexion']);
     */
    protected function render(string $view, array $data = []): void
    {
       $viewFile = __DIR__ . '/../../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Vue introuvable : " . htmlspecialchars($viewFile);
            return;
        }

        // Variables pour la vue
        extract($data);

        // On capture le contenu de la vue
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // On inclut le layout de base
        $layoutFile = __DIR__ . '/../../views/layout/base.php';

        if (!file_exists($layoutFile)) {
            http_response_code(500);
            echo "Layout introuvable : " . htmlspecialchars($layoutFile);
            return;
        }

        require $layoutFile;
    }
}

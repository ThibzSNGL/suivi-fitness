<?php
// backend/config/routes.php

/**
 * Chaque route pointe vers [NomDuController, nomDeLaMethode]
 * Exemple : '/login' => ['AuthController', 'login']
 */

return [
    'GET' => [
        '/'         => ['DashboardController', 'index'],
        '/login'    => ['AuthController', 'login'],
        '/register' => ['AuthController', 'register'],
    ],

    'POST' => [
        '/login'    => ['AuthController', 'doLogin'],
        '/register' => ['AuthController', 'doRegister'],
    ],
];

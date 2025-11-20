<?php
// backend/controllers/AuthController.php

require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller
{
    public function login(): void
    {
        // Si déjà connecté -> dashboard
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?route=/');
            exit;
        }

        $this->render('auth/login', [
            'title' => 'Connexion',
            'error' => $_SESSION['auth_error'] ?? null,
            'old'   => $_SESSION['auth_old']   ?? [],
        ]);

        // On nettoie les données temporaires
        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
    }

    public function register(): void
    {
        // Si déjà connecté -> dashboard
        if (!empty($_SESSION['user_id'])) {
            header('Location: index.php?route=/');
            exit;
        }

        $this->render('auth/register', [
            'title' => 'Inscription',
            'error' => $_SESSION['auth_error'] ?? null,
            'old'   => $_SESSION['auth_old']   ?? [],
        ]);

        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
    }

    public function doRegister(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=/register');
            exit;
        }

        $pdo = get_db_connection();

        // Récupération et nettoyage des données
        $pseudo            = trim($_POST['pseudo'] ?? '');
        $email             = trim($_POST['email'] ?? '');
        $password          = $_POST['password'] ?? '';
        $password_confirm  = $_POST['password_confirm'] ?? '';

        $old = [
            'pseudo' => $pseudo,
            'email'  => $email,
        ];

        // Validation basique
        if ($pseudo === '' || $email === '' || $password === '' || $password_confirm === '') {
            $this->setAuthError("Tous les champs sont obligatoires.", $old);
            header('Location: index.php?route=/register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setAuthError("Adresse e-mail invalide.", $old);
            header('Location: index.php?route=/register');
            exit;
        }

        if (strlen($password) < 8) {
            $this->setAuthError("Le mot de passe doit contenir au moins 8 caractères.", $old);
            header('Location: index.php?route=/register');
            exit;
        }

        if ($password !== $password_confirm) {
            $this->setAuthError("Les mots de passe ne correspondent pas.", $old);
            header('Location: index.php?route=/register');
            exit;
        }

        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $this->setAuthError("Un compte existe déjà avec cette adresse e-mail.", $old);
            header('Location: index.php?route=/register');
            exit;
        }

        // Hasher le mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Insérer l'utilisateur
        $stmt = $pdo->prepare("
            INSERT INTO users (email, password_hash, pseudo, created_at, updated_at)
            VALUES (:email, :password_hash, :pseudo, NOW(), NOW())
        ");

        $stmt->execute([
            'email'         => $email,
            'password_hash' => $passwordHash,
            'pseudo'        => $pseudo,
        ]);

        $userId = (int) $pdo->lastInsertId();

        // Connexion automatique
        $_SESSION['user_id']    = $userId;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_pseudo']= $pseudo;

        // On redirige vers l'onboarding pour compléter le profil
        header('Location: index.php?route=/onboarding');
        exit;
    }

    public function doLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=/login');
            exit;
        }

        $pdo = get_db_connection();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $old = ['email' => $email];

        if ($email === '' || $password === '') {
            $this->setAuthError("Veuillez remplir tous les champs.", $old);
            header('Location: index.php?route=/login');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setAuthError("Adresse e-mail invalide.", $old);
            header('Location: index.php?route=/login');
            exit;
        }

        // Récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT id, email, password_hash, pseudo FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->setAuthError("Identifiants incorrects.", $old);
            header('Location: index.php?route=/login');
            exit;
        }

        // Connexion OK
        $_SESSION['user_id']     = (int) $user['id'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_pseudo'] = $user['pseudo'];

        // Vérifier si le profil est complet

        $stmt = $pdo->prepare("
           SELECT sex, height_cm, weight_kg, level
           FROM users
           WHERE id = :id
        ");

        $stmt->execute(['id' => $user['id']]);
        $profile = $stmt->fetch();

        $needsOnboarding = false;

        if (!$profile) {
            $needsOnboarding = true;
            } else {
            if ($profile['sex'] === 'O'
                || $profile['height_cm'] === null
                || $profile['weight_kg'] === null
                || $profile['level'] === null
                ) {
                $needsOnboarding = true;
                 }
        }

        if ($needsOnboarding) {
            header('Location: index.php?route=/onboarding');
        } else {
            header('Location: index.php?route=/');
        }
        exit;
    }

    public function logout(): void
    {
        // Déconnexion simple
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header('Location: index.php?route=/login');
        exit;
    }

    /**
     * Stocke un message d'erreur et les anciennes valeurs dans la session
     */
    private function setAuthError(string $message, array $old = []): void
    {
        $_SESSION['auth_error'] = $message;
        $_SESSION['auth_old']   = $old;
    }
}


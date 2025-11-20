<?php
// backend/controllers/ProfileController.php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../config/db.php';

class ProfileController extends Controller
{
    private function requireAuth(): int
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: index.php?route=/login');
            exit;
        }
        return (int) $_SESSION['user_id'];
    }

    /**
     * Affiche le formulaire d'onboarding si profil incomplet
     */
    public function onboardingForm(): void
    {
        $userId = $this->requireAuth();
        $pdo    = get_db_connection();

        $stmt = $pdo->prepare("SELECT sex, height_cm, weight_kg, level FROM users WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }

        $this->render('profile/onboarding', [
            'title' => 'Compléter mon profil',
            'error' => $_SESSION['profile_error'] ?? null,
            'old'   => $_SESSION['profile_old']   ?? $user,
        ]);

        unset($_SESSION['profile_error'], $_SESSION['profile_old']);
    }

    /**
     * Sauvegarde les infos d'onboarding
     */
    public function saveOnboarding(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=/onboarding');
            exit;
        }

        $userId = $this->requireAuth();
        $pdo    = get_db_connection();

        $sex    = $_POST['sex'] ?? 'O';
        $weight = trim($_POST['weight_kg'] ?? '');
        $height = trim($_POST['height_cm'] ?? '');
        $level  = $_POST['level'] ?? 'beginner';

        $old = [
            'sex'       => $sex,
            'weight_kg' => $weight,
            'height_cm' => $height,
            'level'     => $level,
        ];

        // validations basiques
        if (!in_array($sex, ['M', 'F', 'O'], true)) {
            $this->setProfileError("Sexe invalide.", $old);
            header('Location: index.php?route=/onboarding');
            exit;
        }

        if ($weight === '' || !is_numeric($weight) || $weight <= 0) {
            $this->setProfileError("Poids invalide.", $old);
            header('Location: index.php?route=/onboarding');
            exit;
        }

        if ($height === '' || !ctype_digit($height) || $height <= 0) {
            $this->setProfileError("Taille invalide.", $old);
            header('Location: index.php?route=/onboarding');
            exit;
        }

        if (!in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
            $this->setProfileError("Niveau invalide.", $old);
            header('Location: index.php?route=/onboarding');
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE users
            SET sex = :sex,
                weight_kg = :weight,
                height_cm = :height,
                level = :level,
                updated_at = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'sex'    => $sex,
            'weight' => $weight,
            'height' => $height,
            'level'  => $level,
            'id'     => $userId,
        ]);

        // une fois le profil complété -> dashboard
        header('Location: index.php?route=/');
        exit;
    }

    /**
     * Page "Mon compte"
     */
    public function showProfile(): void
    {
        $userId = $this->requireAuth();
        $pdo    = get_db_connection();

        $stmt = $pdo->prepare("
            SELECT email, pseudo, sex, height_cm, weight_kg, level, created_at
            FROM users
            WHERE id = :id
        ");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }

        $this->render('profile/account', [
            'title' => 'Mon compte',
            'user'  => $user,
            'error' => $_SESSION['profile_error'] ?? null,
            'success' => $_SESSION['profile_success'] ?? null,
        ]);

        unset($_SESSION['profile_error'], $_SESSION['profile_success']);
    }

    /**
     * Mise à jour depuis la page "Mon compte"
     */
    public function updateProfile(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?route=/profile');
            exit;
        }

        $userId = $this->requireAuth();
        $pdo    = get_db_connection();

        $pseudo = trim($_POST['pseudo'] ?? '');
        $sex    = $_POST['sex'] ?? 'O';
        $weight = trim($_POST['weight_kg'] ?? '');
        $height = trim($_POST['height_cm'] ?? '');
        $level  = $_POST['level'] ?? 'beginner';

        if ($pseudo === '') {
            $this->setProfileError("Le pseudo ne peut pas être vide.");
            header('Location: index.php?route=/profile');
            exit;
        }

        if (!in_array($sex, ['M', 'F', 'O'], true)) {
            $this->setProfileError("Sexe invalide.");
            header('Location: index.php?route=/profile');
            exit;
        }

        if ($weight === '' || !is_numeric($weight) || $weight <= 0) {
            $this->setProfileError("Poids invalide.");
            header('Location: index.php?route=/profile');
            exit;
        }

        if ($height === '' || !ctype_digit($height) || $height <= 0) {
            $this->setProfileError("Taille invalide.");
            header('Location: index.php?route=/profile');
            exit;
        }

        if (!in_array($level, ['beginner', 'intermediate', 'advanced'], true)) {
            $this->setProfileError("Niveau invalide.");
            header('Location: index.php?route=/profile');
            exit;
        }

        $stmt = $pdo->prepare("
            UPDATE users
            SET pseudo = :pseudo,
                sex = :sex,
                weight_kg = :weight,
                height_cm = :height,
                level = :level,
                updated_at = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'pseudo' => $pseudo,
            'sex'    => $sex,
            'weight' => $weight,
            'height' => $height,
            'level'  => $level,
            'id'     => $userId,
        ]);

        $_SESSION['user_pseudo']   = $pseudo;
        $_SESSION['profile_success'] = "Profil mis à jour avec succès.";

        header('Location: index.php?route=/profile');
        exit;
    }

    private function setProfileError(string $message, array $old = []): void
    {
        $_SESSION['profile_error'] = $message;
        $_SESSION['profile_old']   = $old;
    }
}

<!-- views/auth/login.php -->
<section class="auth auth-login">
    <h2>Connexion</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?route=/login" method="post" class="auth-form">
        <div class="form-group">
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <p class="auth-switch">
        Pas encore de compte ?
        <a href="index.php?route=/register">Créer un compte</a>
    </p>
</section>

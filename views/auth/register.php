<!-- views/auth/register.php -->
<section class="auth auth-register">
    <h2>Créer un compte</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?route=/register" method="post" class="auth-form">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input
                type="text"
                id="pseudo"
                name="pseudo"
                required
                value="<?= isset($old['pseudo']) ? htmlspecialchars($old['pseudo']) : '' ?>"
            >
        </div>

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

        <div class="form-group">
            <label for="password_confirm">Confirmer le mot de passe</label>
            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Créer mon compte</button>
    </form>

    <p class="auth-switch">
        Déjà un compte ?
        <a href="index.php?route=/login">Se connecter</a>
    </p>
</section>

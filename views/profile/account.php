<!-- views/profile/account.php -->
<section class="profile-account">
    <h2>Mon compte</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?route=/profile" method="post" class="profile-form">

        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input
                type="text"
                id="pseudo"
                name="pseudo"
                required
                value="<?= htmlspecialchars($user['pseudo']) ?>"
            >
        </div>

        <div class="form-group">
            <label for="email">Adresse e-mail (non modifiable)</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($user['email']) ?>"
                disabled
            >
        </div>

        <div class="form-group">
            <label for="sex">Sexe</label>
            <?php $currentSex = $user['sex'] ?? 'O'; ?>
            <select id="sex" name="sex">
                <option value="M" <?= $currentSex === 'M' ? 'selected' : '' ?>>Homme</option>
                <option value="F" <?= $currentSex === 'F' ? 'selected' : '' ?>>Femme</option>
                <option value="O" <?= $currentSex === 'O' ? 'selected' : '' ?>>Autre / Ne pas préciser</option>
            </select>
        </div>

        <div class="form-group">
            <label for="weight_kg">Poids (kg)</label>
            <input
                type="number"
                step="0.1"
                id="weight_kg"
                name="weight_kg"
                value="<?= htmlspecialchars($user['weight_kg']) ?>"
            >
        </div>

        <div class="form-group">
            <label for="height_cm">Taille (cm)</label>
            <input
                type="number"
                id="height_cm"
                name="height_cm"
                value="<?= htmlspecialchars($user['height_cm']) ?>"
            >
        </div>

        <div class="form-group">
            <label for="level">Niveau</label>
            <?php $currentLevel = $user['level'] ?? 'beginner'; ?>
            <select id="level" name="level">
                <option value="beginner" <?= $currentLevel === 'beginner' ? 'selected' : '' ?>>Débutant</option>
                <option value="intermediate" <?= $currentLevel === 'intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
                <option value="advanced" <?= $currentLevel === 'advanced' ? 'selected' : '' ?>>Avancé</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <p class="profile-meta">
        Compte créé le : <?= htmlspecialchars($user['created_at']) ?>
    </p>

    <p>
        <a href="index.php?route=/">Retour au dashboard</a>
    </p>
</section>

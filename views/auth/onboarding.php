<!-- views/profile/onboarding.php -->
<section class="profile-onboarding">
    <h2>Compléter mon profil</h2>
    <p>Ces informations permettront de mieux suivre ta progression.</p>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?route=/onboarding" method="post" class="profile-form">

        <div class="form-group">
            <label for="sex">Sexe</label>
            <select id="sex" name="sex" required>
                <?php
                $currentSex = $old['sex'] ?? 'O';
                ?>
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
                required
                value="<?= isset($old['weight_kg']) ? htmlspecialchars($old['weight_kg']) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label for="height_cm">Taille (cm)</label>
            <input
                type="number"
                id="height_cm"
                name="height_cm"
                required
                value="<?= isset($old['height_cm']) ? htmlspecialchars($old['height_cm']) : '' ?>"
            >
        </div>

        <div class="form-group">
            <label for="level">Niveau</label>
            <?php $currentLevel = $old['level'] ?? 'beginner'; ?>
            <select id="level" name="level" required>
                <option value="beginner" <?= $currentLevel === 'beginner' ? 'selected' : '' ?>>Débutant</option>
                <option value="intermediate" <?= $currentLevel === 'intermediate' ? 'selected' : '' ?>>Intermédiaire</option>
                <option value="advanced" <?= $currentLevel === 'advanced' ? 'selected' : '' ?>>Avancé</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer et continuer</button>
    </form>
</section>

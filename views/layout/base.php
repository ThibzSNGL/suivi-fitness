<!-- views/layout/base.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Suivi Fitness' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS global (à créer plus tard) -->
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body class="app app-dark">
    <header class="app-header">
        <h1 class="app-logo">Suivi Fitness</h1>
    </header>

    <main class="app-main">
        <?= $content ?? '' ?>
    </main>

    <footer class="app-footer">
        <small>&copy; <?= date('Y') ?> - Suivi Fitness</small>
    </footer>
</body>
</html>

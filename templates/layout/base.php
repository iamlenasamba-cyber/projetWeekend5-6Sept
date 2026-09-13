<?php
$title = $title ?? 'Réservation de salles';
$content = $content ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header>
        <nav>
            <a href="/salles">Salles</a>
            <a href="/reservations">Réservations</a>
            <a href="/salles/create">Nouvelle salle</a>
            <a href="/reservations/create">Nouvelle réservation</a>
            <form method="POST" action="/responsable/logout" style="display:inline;">
                <button type="submit">Déconnexion</button>
            </form>
        </nav>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>

<?php

$old = $_POST ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="/assets/style.css">
    <title>Document</title>
</head>
<body>
    <h1>Connexion responsable</h1>
<?php if (!empty($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
<form method="POST" action="/responsable/authenticate">
    <label for="responsable">Responsable</label>
    <input id="responsable" name="responsable" value="<?=  ($old['responsable'] ?? '')?>" required>

    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="<?= ($old['email'] ?? '')?>" required>

    <button type="submit">Se connecter</button>
</form>

</body>
</html>


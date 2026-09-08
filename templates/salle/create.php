<?php
$old = $_POST ?? [];
ob_start();
?>
<h1>Créer une salle</h1>
<?php if (!empty($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
<form method="POST" action="/salles/store">
    <label for="nom">Nom</label>
    <input id="nom" name="nom" value="<?= $old['nom'] ?>" required>
    <label for="batiment">Bâtiment</label>
    <input id="batiment" name="batiment" value="<?= $old['batiment'] ?? '' ?>" required>
    <label for="capacite">Capacité</label>
    <input id="capacite" name="capacite" type="number" min="1" value="<?= $old['capacite'] ?? '' ?>" required>
    <label for="type_id">Type</label>
    <input id="type_id" name="type_id" type="number" min="1" value="<?= $old['type_id'] ?? '' ?>" required>
    <label for="active">Active</label>
    <select id="active" name="active"><option value="1">Oui</option><option value="0">Non</option></select>
    <button type="submit">Enregistrer</button>
</form>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

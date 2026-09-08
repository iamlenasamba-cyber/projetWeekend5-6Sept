<?php

$old = $_POST ?? [];
ob_start();
?>
<h1>Créer une réservation</h1>
<?php if (!empty($error)): ?><p class="error"><?= $error ?></p><?php endif; ?>
<form method="POST" action="/reservations/store">
    <label for="salle_id">Salle</label>
    <select id="salle_id" name="salle_id" required>
        <?php foreach ($salles as $salle): ?>
            <option value="<?= $salle->id ?>" ><?= $salle->nom ?></option>
        <?php endforeach; ?>
    </select>
    <label for="responsable">Responsable</label>
    <input id="responsable" name="responsable" required>
    <label for="email">Email</label>
    <input id="email" name="email" type="email" required>
    <label for="motif">Motif</label>
    <input id="motif" name="motif"  required>
    <label for="date_debut">Date de début</label>
    <input id="date_debut" name="date_debut" type="datetime-local" required>
    <label for="date_fin">Date de fin</label>
    <input id="date_fin" name="date_fin" type="datetime-local"  required>
    <button type="submit">Enregistrer</button>
</form>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

<?php
ob_start();
?>
<h1>Liste des salles</h1>
<a class="btn" href="/salles/create">Nouvelle salle</a>
<table>
    <thead><tr><th>Nom</th><th>Bâtiment</th><th>Capacité</th><th>Type</th><th>Active</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($salles as $salle): ?>
        <tr>
            <td><?= $salle->nom ?></td>
            <td><?= $salle->batiment ?></td>
            <td><?= $salle->capacite ?></td>
            <td><?= $salle->type_nom ?? $salle->type_id ?></td>
            <td><?= $salle->active ? 'Oui' : 'Non' ?></td>
            <td><a href="/salles/<?= $salle->id ?>">Voir</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

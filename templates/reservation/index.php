<?php
ob_start();
?>
<h1>Liste des réservations</h1>
<a class="btn" href="/reservations/create">Nouvelle réservation</a>
<table>
    <thead><tr><th>ID</th><th>Salle</th><th>Responsable</th><th>Email</th><th>Début</th><th>Fin</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($reservations as $reservation): ?>
        <tr>
            <td><?= $reservation->id ?></td>
            <td><?= $reservation->salle->nom ?? 'Inconnue' ?></td>
            <td><?= $reservation->responsable ?></td>
            <td><?= $reservation->email ?></td>
            <td><?= $reservation->date_debut ?></td>
            <td><?= $reservation->date_fin ?></td>
            <td><a href="/reservations/<?=  $reservation->id ?>">Voir</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

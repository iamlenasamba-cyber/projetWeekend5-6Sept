<?php
ob_start();
?>
<h1>Réservation #<?= (int) $reservation->id ?></h1>
<dl>
    <dt>Salle</dt><dd><?= $reservation->salle->nom ?? 'Inconnue' ?></dd>
    <dt>Responsable</dt><dd><?= $reservation->responsable ?></dd>
    <dt>Email</dt><dd><?= $reservation->email ?></dd>
    <dt>Motif</dt><dd><?= $reservation->motif ?></dd>
    <dt>Début</dt><dd><?= $reservation->date_debut ?></dd>
    <dt>Fin</dt><dd><?= $reservation->date_fin ?></dd>
</dl>
<a href="/reservations">Retour à la liste</a>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

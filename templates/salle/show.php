<?php
ob_start();
?>
<h1><?= $salle->nom ?></h1>
<dl>
    <dt>Bâtiment</dt><dd><?= $salle->batiment ?></dd>
    <dt>Capacité</dt><dd><?= $salle->capacite ?></dd>
    <dt>Type</dt><dd><?=$salle->type_nom ?></dd>
    <dt>Active</dt><dd><?= $salle->active ? 'Oui' : 'Non' ?></dd>
</dl>
<a href="/salles">Retour à la liste</a>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

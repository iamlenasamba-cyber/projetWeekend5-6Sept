<?php

ob_start();
?>
<h1>Méthode non autorisée</h1>
<p>Cette action n'est pas disponible avec cette méthode HTTP.</p>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

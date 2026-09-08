<?php

ob_start();
?>
<h1>Page introuvable</h1>
<p>La ressource demandée n'existe pas.</p>
<a href="/salles">Retour aux salles</a>
<?php
$content = ob_get_clean();
require_once dirname(__DIR__) . '/layout/base.php';

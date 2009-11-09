<?php
include_once ("classes/juego_classes.inc.php");
$admin = new AdminJuego($_REQUEST['padre']);
echo $admin->getHtml();
?>

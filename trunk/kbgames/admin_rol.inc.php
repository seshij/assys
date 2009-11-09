<?php
//echo "(admin_ruta) classes/classes.inc.php<br>";
include_once ("classes/rol_classes.inc.php");

$admin = new AdminRol();
echo $admin->getHtml();
?>

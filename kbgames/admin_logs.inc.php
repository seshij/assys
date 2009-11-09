<?php
//echo "(admin_ruta) classes/classes.inc.php<br>";
include_once ("classes/logs_classes.inc.php");

$admin = new AdminLogs();
echo $admin->getHtml();
?>

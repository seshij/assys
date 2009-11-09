<?php
//echo "(admin_ruta) classes/classes.inc.php<br>";
include_once ("classes/back_end_user_classes.inc.php");

$admin = new AdminBackEndUser();
echo $admin->getHtml();
?>

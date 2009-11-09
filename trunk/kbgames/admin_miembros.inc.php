<?php
include_once ("classes/miembro_classes.inc.php");
$admin = new AdminMiembro();
echo $admin->getHtml();
?>

<?php
//echo "(admin_default) classes/classes.inc.php<br>";
include_once ("classes/default_classes.inc.php");

$admin = new AdminDefault();
echo $admin->getHtml();
?>
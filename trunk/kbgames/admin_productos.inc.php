<?php
include_once ("classes/producto_classes.inc.php");
$admin = new AdminProducto($_REQUEST['padre']);
echo $admin->getHtml();
?>

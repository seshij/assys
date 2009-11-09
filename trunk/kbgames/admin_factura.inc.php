<?php
include_once ("classes/factura_classes.inc.php");

$admin = new AdminFactura();
echo $admin->getHtml();
?>

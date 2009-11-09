<?php
include_once ("classes/cliente_classes.inc.php");

$admin = new AdminCliente();
echo $admin->getHtml();
?>

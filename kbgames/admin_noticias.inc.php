<?php
include_once ("classes/noticia_classes.inc.php");
$admin = new AdminNoticia();
echo $admin->getHtml();
?>

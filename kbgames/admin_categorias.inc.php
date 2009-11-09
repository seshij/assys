<?php
include_once ("classes/categoria_classes.inc.php");

if(isset($_REQUEST["padre"])){
	$admin = new AdminCategoria($_REQUEST["padre"]);
}else{
	$admin = new AdminCategoria("-1");
}
echo $admin->getHtml();
?>

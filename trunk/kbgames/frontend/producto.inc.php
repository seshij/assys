<?php
include_once ("feclasses/producto_feclasses.inc.php");

if(isset($_REQUEST['parent'])){
	$admin = new ProductoFEController($_REQUEST['parent']);
	echo $admin->getHtml();
}


?>
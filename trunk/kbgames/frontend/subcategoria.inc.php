<?php
include_once ("feclasses/subcategoria_feclasses.inc.php");

if(isset($_REQUEST['parent'])){
	$admin = new SubcategoriaFEController($_REQUEST['parent']);
}else{
	header("Location: index.php");
}

echo $admin->getHtml();
?>
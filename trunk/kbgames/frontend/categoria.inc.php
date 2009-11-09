<?php
include_once ("feclasses/categoria_feclasses.inc.php");

if(isset($_REQUEST['chglang'])){
	$_SESSION['lang']=$_REQUEST['chglang'];
}
$admin = new CategoriaFEController();
echo $admin->getHtml();
?>
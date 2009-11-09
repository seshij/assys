<?php
include_once ("feclasses/anuncia_feclasses.inc.php");

if(isset($_REQUEST['myid'])){
	$admin = new AnunciaFEController($_REQUEST['myid']);
}else{
	header("Location: index.php");
}
echo $admin->getHtml();
?>
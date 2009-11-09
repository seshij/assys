<?php
include_once ("feclasses/anunlist_feclasses.inc.php");

if(isset($_REQUEST['parent'])){
	$admin = new AnunlistFEController($_REQUEST['parent']);
}else{
	header("Location: index.php");
}

echo $admin->getHtml();
?>
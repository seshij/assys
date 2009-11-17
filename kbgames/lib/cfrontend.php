<?php
include_once("dao.inc.php");
include_once ("html.inc.php");
//include_once ("feclasses/front_end_user_feclasses.inc.php");
//include_once("classes/disan_user_classes.inc.php");

header("Cache-Control: no-store");
header("Pragma: no-cache");
header("Expires: 0");



// -------------------------------------------------
session_start();
ob_start();
$include_option = "frontend/default.inc.php";
if (isset($_REQUEST["option"])) {
	$option = $_REQUEST["option"];
	$include_option = getIncludeOption($option);
}

if ($include_option != NULL) {
	include_once($include_option);
}

$CMS_CONTENT = ob_get_contents();
ob_end_clean();
//----------------------------------------------------


function indexContent(){
	echo $GLOBALS["CMS_CONTENT"];
}



function getIncludeOption($option){

	if ($option == NULL){
		$option = "default";
	}
	return "frontend/" . $option . ".inc.php";
}
?>

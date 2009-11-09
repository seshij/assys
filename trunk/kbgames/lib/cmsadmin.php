<?php
include_once("dao.inc.php");
include_once ("html.inc.php");
include_once ("classes/back_end_user_classes.inc.php");

header("Cache-Control: no-store");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
ob_start();
$include_option = "admin_default.inc.php";
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


function adminContent(){
	echo $GLOBALS["CMS_CONTENT"];

}

function getIncludeOption($option){
	if ($option == NULL || !isset($_SESSION["user"])){
		$option = "default";
	}
	return "admin_" . $option . ".inc.php";
}
?>
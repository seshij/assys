<?php
include_once ("feclasses/content_feclasses.inc.php");

	$admin = new ContentFEController();
	echo $admin->getHtml();



?>
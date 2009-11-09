<?php
include_once ("feclasses/news_feclasses.inc.php");
	$admin = new NewsFEController();
	echo $admin->getHtml();
?>
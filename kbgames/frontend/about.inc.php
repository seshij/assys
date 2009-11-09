<?php
include_once ("feclasses/about_feclasses.inc.php");
$admin = new AboutFEController();

echo $admin->getHtml();
?>
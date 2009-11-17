<?php
include_once ("feclasses/xbox_360_feclasses.inc.php");
$admin = new Xbox360FEController();
echo $admin->getHtml();
?>
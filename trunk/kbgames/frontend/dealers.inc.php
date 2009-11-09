<?php
include_once ("feclasses/dealers_feclasses.inc.php");

$admin = new DealersFEController();
echo $admin->getHtml();
?>
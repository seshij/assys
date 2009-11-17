<?php
include_once ("feclasses/play_station_3_feclasses.inc.php");
$admin = new PlayStation3FEController();
echo $admin->getHtml();
?>
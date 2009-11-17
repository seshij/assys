<?php
include_once ("feclasses/play_station_2_feclasses.inc.php");
$admin = new PlayStation2FEController();
echo $admin->getHtml();
?>
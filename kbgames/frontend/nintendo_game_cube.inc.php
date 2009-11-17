<?php
include_once ("feclasses/game_cube_feclasses.inc.php");
$admin = new GameCubeFEController();
echo $admin->getHtml();
?>
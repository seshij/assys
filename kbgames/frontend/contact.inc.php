<?php
include_once ("feclasses/contact_feclasses.inc.php");

$admin = new ContactFEController();
echo $admin->getHtml();
?>
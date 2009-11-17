<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$encuesta=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_sorpresas");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");


$div=new HtmlDiv("link_sorpresas");
$btnSorpresa=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnSorpresa","");
$btnSorpresa->setAttribute("class", "ui-corner-button-all");
$btnSorpresa->addAttribute("id", "btnsorpresa");
$btnSorpresa->onClick("window.location='#'");
$div->addElement($btnSorpresa);
$table->addCell($div);
$table->nextRow();

$encuesta->addElement($table);
echo $encuesta->getHtml();
?>

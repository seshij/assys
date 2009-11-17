<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$encuesta=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_registrate");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");


$div=new HtmlDiv("link_registrate");
$btnRegistrate=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnRegistrate","");
$btnRegistrate->setAttribute("class", "ui-corner-button-all");
$btnRegistrate->addAttribute("id", "btnregistrate");
$btnRegistrate->onClick("window.location='#'");
$div->addElement($btnRegistrate);
$table->addCell($div);
$table->nextRow();

$encuesta->addElement($table);
echo $encuesta->getHtml();
?>

<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$encuesta=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_soporte_juega");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");


$div=new HtmlDiv("link_soporte");
$btnSoporte=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnSoporte","");
$btnSoporte->setAttribute("class", "ui-corner-button-all");
$btnSoporte->addAttribute("id", "btnsoporte");
$btnSoporte->onClick("window.location='#'");
$div->addElement($btnSoporte);
$table->addCell($div);

$div1=new HtmlDiv("link_juega");
$btnJuega=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnJuega","");
$btnJuega->setAttribute("class", "ui-corner-button-all");
$btnJuega->addAttribute("id", "btnjuega");
$btnJuega->onClick("window.location='#'");
$div1->addElement($btnJuega);
$table->addCell($div1);

$table->nextRow();

$encuesta->addElement($table);
echo $encuesta->getHtml();
?>

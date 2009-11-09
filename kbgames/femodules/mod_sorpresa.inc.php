<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$encuesta=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_sorpresas");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");


//Titulo de la encuesta en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_sorpresas");
$div->addAttribute("class", "ui-widget ui-widget-header ui-corner-top");
$div_titulo=new HtmlDiv("divimg_titulo");
$div_titulo->addElement(new HtmlText("<img src='images/frontend/header_sorpresas.jpg'>"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

$div=new HtmlDiv("link_sorpresas");
$div->addAttribute("class", "ui-widget ui-widget-content ui-corner-bottom");
$btnSorpresa=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnSorpresa","");
$btnSorpresa->addAttribute("id", "btnsorpresa");
$btnSorpresa->onClick("window.location='#'");
$div->addElement($btnSorpresa);
$table->addCell($div);
$table->nextRow();
$td=new HtmlTd();
$td->addElement($table);
$tr=new HtmlTr();
$tr->addElement($td);
$encuesta->addElement($tr);
echo $encuesta->getHtml();
?>

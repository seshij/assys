<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$top10=new HtmlContainer();

//Tabla que contiene todo (Titulo de encuesta y Encuesta)
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_top10");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");

//Titulo de la encuesta en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_top10");
$div->addAttribute("class", "ui-widget ui-widget-header ui-corner-top");
$div_titulo=new HtmlDiv("divimg_titulo");
$div_titulo->addElement(new HtmlText("<img src='images/frontend/header_top10.jpg'>"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

//DIV que contiene una tabla donde se genera la pregunta y opciones de la encuesta
$div=new HtmlDiv("top10_swf");
$div->addAttribute("class", "ui-widget ui-widget-content ui-corner-bottom");
$table->addCell($div);
$table->nextRow();

$top10->addElement($table);
echo $top10->getHtml();
?>

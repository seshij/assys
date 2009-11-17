<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

/*function crearEncuesta(&$table_opt) {
    $notiRow=DaoMgr::getDao()->executeQuery("select titulo,contenido,imagen from noticias where id_idioma=1 order by fecha_ingreso limit 2");
    $table_opt->addCell(new HtmlText("<p class='pregunta'>".$pregRow[0]['pregunta']."</p>"));
    $table_opt->nextRow();

    $i=0;
    foreach($notiRows as $opt) {
        $cont=new HtmlContainer();
        $radiobtn=new HtmlRadio("opcion",$opt['id_opcion']);
        $radiobtn->addAttribute("class", "radiobtn");
        $radiobtn->addAttribute("id", "rad".$i);
        $cont->addElement($radiobtn);
        $cont->addTextElement("<label for='rad".$i."'>".$opt['opcion']."</label>");
        $table_opt->addCell($cont);
        $table_opt->nextRow();
        $i++;
    }
}*/
//Encuesta
$noticias=new HtmlContainer();

//Tabla que contiene todo (Titulo de encuesta y Encuesta)
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_noticias");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");
$table->addAttribute("border", "0");

//Titulo de la encuesta en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_noticias");
$div->addAttribute("class", "ui-corner-top");
$div_titulo=new HtmlDiv("divimg_titulo");
$div_titulo->addElement(new HtmlText("<img src='images/frontend/header_noticias.jpg'>"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

//DIV que contiene una tabla donde se genera la pregunta y opciones de la encuesta
/*$div2=new HtmlDiv("noticias");
$div2->addAttribute("class", "ui-corner-bottom");
$noticias = new HtmlTable();
$noticias->addAttribute("id", "tbl_noticias");
$noticias->addAttribute("cellspacing", "0");
$noticias->addAttribute("cellpadding", "0");
$noticias->addCell(new HtmlText("<img src='images/frontend/noticias.jpg'>"));
$noticias->nextRow();
//$div2->addElement($noticias);*/
$table->addCell(new HtmlText("<img src='images/frontend/noticias.jpg'>"));
$table->nextRow();


$noticias->addElement($table);
echo $noticias->getHtml();
?>

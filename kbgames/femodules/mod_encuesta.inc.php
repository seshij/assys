<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

function crearEncuesta(&$table_opt) {
    $pregRow=DaoMgr::getDao()->executeQuery("select id_encuesta,pregunta from encuesta where activa=1 and id_idioma=1");
    $optRows=DaoMgr::getDao()->executeQuery("select id_opcion,opcion from opts_encuesta where id_encuesta=".$pregRow[0]['id_encuesta']." and id_idioma=1");

    $table_opt->addCell(new HtmlText("<p class='pregunta'>".$pregRow[0]['pregunta']."</p>"));
    $table_opt->nextRow();

    $i=0;
    foreach($optRows as $opt) {
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
}
//Encuesta
$encuesta=new HtmlContainer();

//Tabla que contiene todo (Titulo de encuesta y Encuesta)
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_encuesta");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");

//Titulo de la encuesta en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_encuesta");
$div->addAttribute("class", "ui-widget ui-widget-header ui-corner-top");
$div_titulo=new HtmlDiv("divimg_titulo");
$div_titulo->addElement(new HtmlText("<img src='images/frontend/header_encuesta.jpg'>"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

//DIV que contiene una tabla donde se genera la pregunta y opciones de la encuesta
$div=new HtmlDiv("opts_encuesta");
$div->addAttribute("class", "ui-widget ui-widget-content ui-corner-bottom");
$table_opt = new HtmlTable();
$table_opt->addAttribute("id", "tbl_opciones");
$table_opt->addAttribute("cellspacing", "0");
$table_opt->addAttribute("cellpadding", "5");
crearEncuesta($table_opt);
$btnVotar=new HtmlButton(HtmlButton::SUBMIT_BUTTON,"btnVotar","VOTA");
$btnVotar->setAttribute("class", "ui-button-submit ui-corner-button-all");
$btnVotar->addAttribute("id", "btnvotar");
$btnResultados=new HtmlButton(HtmlButton::SCRIPT_BUTTON,"btnResultados","RESULTADOS");
$btnResultados->setAttribute("class", "ui-button-submit ui-corner-button-all");
$btnResultados->addAttribute("id", "btnresultados");
$btnResultados->onClick("window.location='#'");
$divBotones=new HtmlDiv();
$divBotones->addElement($btnResultados);
$divBotones->addElement($btnVotar);
$table_opt->addCell($divBotones);
$table_opt->nextRow();

//Formulario que contiene la tabla anterior
$form=new HtmlForm("#","GET","frm_encuesta");
$form->addElement($table_opt);
$div->addElement($form);
$table->addCell($div);
$table->nextRow();

//Se agrega a una fila que va a hacer parte de la tabla exterior que contiene todo el codigo de este archivo
$td=new HtmlTd();
$td->addElement($table);
$tr=new HtmlTr();
$tr->addElement($td);
$encuesta->addElement($tr);
echo $encuesta->getHtml();
?>

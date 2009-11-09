<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$cont=new HtmlContainer();

$form_registro=new HtmlForm('#','GET','frm_busqueda');
$form_registro->addAttribute("id", "form_busqueda");

$table_form=new HtmlTable();
$table_form->addAttribute('cellspacing', '0');
$table_form->addAttribute('cellpadding', '0');
$table_form->addAttribute('id', 'tabla_form_busqueda');

$div_usuario=new HtmlDiv('div_criterio');
$div_usuario->addAttribute('class', 'ui-corner-all');
$usuario=new HtmlInputText('criterio','que quiere buscar?', 16, 100);
$div_usuario->addElement($usuario);
$table_form->addCell($div_usuario);

$btn=new HtmlButton(HtmlButton::SUBMIT_BUTTON,"btnBuscar"," ");
$btn->setAttribute("class", "ui-button-submit ui-corner-button-all");
$btn->addAttribute("id", "btnbuscar");
$table_form->addCell($btn);

$table_form->nextRow();

$form_registro->addElement($table_form);

$cont->addElement($form_registro);

echo "<table cellpadding='0' cellspacing='0' id='tabla_busqueda'>
    <tr>
        <td style='vertical-align:top'>
            ".$cont->getHtml()."
        </td>
    </tr>
</table>"
?>
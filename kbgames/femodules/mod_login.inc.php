<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$cont=new HtmlContainer();

$form_registro=new HtmlForm('#','GET','frm_registro');
$form_registro->addAttribute("id", "form_registro");

$table_form=new HtmlTable();
$table_form->addAttribute('cellspacing', '0');
$table_form->addAttribute('cellpadding', '0');
$table_form->addAttribute('id', 'tabla_form');

$div_usuario=new HtmlDiv('div_usuario');
$div_usuario->addAttribute('class', 'ui-corner-all');
$usuario=new HtmlInputText('usuario','usuario', 12, 100);
$div_usuario->addElement($usuario);
$table_form->addCell($div_usuario);

$div_password=new HtmlDiv('div_password');
$div_password->addAttribute('class', 'ui-corner-all');
$password=new HtmlInputPassword('password','password',12,100);
$password->addAttribute('id', 'password');
$div_password->addElement($password);
$table_form->addCell($div_password);

$btn=new HtmlButton(HtmlButton::SUBMIT_BUTTON,"btnSingIn"," ");
$btn->setAttribute("class", "ui-button-submit ui-corner-button-all");
$btn->addAttribute("id", "btnsingin");
$table_form->addCell($btn);

$table_form->nextRow();

$form_registro->addElement($table_form);

$cont->addElement($form_registro);

echo "<table cellpadding='0' cellspacing='0' id='tabla_registro'>
    <tr>
        <td style='vertical-align:top'>
            <img src='images/frontend/registrate.jpg' border='0' hspace='0' vspace='0'/>
        </td>
        <td style='vertical-align:top'>
            ".$cont->getHtml()."
        </td>
    </tr>
</table>"
?>
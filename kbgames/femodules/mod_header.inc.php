<?php
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');

/*$cont=new HtmlContainer();
$header = new HtmlTable();
$header->addAttribute('cellspacing', '0');
$header->addAttribute('cellpadding', '0');
$header->addAttribute('id', 'header');

//Botonoes y Logo
$tbl_btns=new HtmlTable();
$tbl_btns->addAttribute('border', '0');
$tbl_btns->addAttribute('cellspacing', '0');
$tbl_btns->addAttribute('cellpadding', '0');
$tbl_btns->addAttribute('id', 'tabla_btns');
$tbl_btns->addCell(new HtmlImg('images/frontend/logo_kbgames.jpg'));
$div_separador=new HtmlDiv('separador');
$tbl_btns->addCell($div_separador);
$tbl_btns->addCell(new HtmlImg('images/frontend/header_btn_nintendo.jpg'));
$tbl_btns->addCell(new HtmlImg('images/frontend/header_btn_play.jpg'));
$tbl_btns->addCell(new HtmlImg('images/frontend/header_btn_xbox.jpg'));
$tbl_btns->nextRow();

$header->addCell($tbl_btns);
$header->nextRow();

//Slogan y formularios de login y busqueda
$tbl_slogan=new HtmlTable();
$tbl_slogan->addAttribute('border', '0');
$tbl_slogan->addAttribute('cellspacing', '0');
$tbl_slogan->addAttribute('cellpadding', '0');
$tbl_slogan->addAttribute('id', 'tabla_slogan');
$tbl_slogan->addCell(new HtmlImg('images/frontend/slogan.jpg'));

$div_registro=new HtmlDiv('registro');
$div_registro->addAttribute('class', 'registro');
$tabla_registro=new HtmlTable();
$tabla_registro->addAttribute('border', '0');
$tabla_registro->addAttribute('cellspacing', '0');
$tabla_registro->addAttribute('cellpadding', '0');
$tabla_registro->addAttribute('id', 'tabla_registro');
$tabla_registro->addCell(new HtmlImg('images/frontend/registrate.jpg'));
$form_registro=new HtmlForm('#','GET','frm_registro');
$table_form=new HtmlTable('table_form');
$table_form->addAttribute('border', '0');
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
$table_form->nextRow();
$form_registro->addElement($table_form);
$tabla_registro->addCell($form_registro);
$tabla_registro->nextRow();
$div_registro->addElement($tabla_registro);

$tbl_slogan->addCell($div_registro);
$tbl_slogan->nextRow();

$header->addCell($tbl_slogan);
$header->nextRow();

$cont->addElement($header);
echo $cont->getHtml();*/
?>
<table cellspacing='0' cellpadding='0' id='header'>
    <tr>
        <td align='left'>
            <table border='0' cellspacing='0' cellpadding='0' id='tabla_btns'>
                <tr>
                    <td align='left'>
                        <img src='images/frontend/logo_kbgames.jpg' border='0' hspace='0' vspace='0'/>
                    </td>
                    <td align='left'>
                        <div id='separador'>
                        </div>
                    </td>
                    <td align='left'>
                        <img src='images/frontend/header_btn_nintendo.jpg' border='0' hspace='0' vspace='0'/>
                    </td>
                    <td align='left'>
                        <img src='images/frontend/header_btn_play.jpg' border='0' hspace='0' vspace='0'/>
                    </td>
                    <td align='left'>
                        <img src='images/frontend/header_btn_xbox.jpg' border='0' hspace='0' vspace='0'/>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align='left'>
            <table width='700px' border='0' cellspacing='0' cellpadding='0' id='tabla_slogan'>
                <tr>
                    <td align='left' style="vertical-align:top">
                        <img src='images/frontend/slogan.jpg' border='0' hspace='0' vspace='0'/>
                    </td>
                    <td align='left' style="vertical-align:top">
                        <?require("femodules/mod_login.inc.php")?>
                    </td>
                    <td align='left' style="vertical-align:top">
                        <?require("femodules/mod_busqueda.inc.php")?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>



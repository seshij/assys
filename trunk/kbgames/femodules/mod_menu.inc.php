<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

function buildMenu3($myRoot,&$menu,$sp="") {
    $rows=DaoMgr::getDao()->executeQuery("select * from categoria where id_padre=$myRoot order by orden");
    $i=0;
    foreach($rows as $mirow) {
        $i++;
        $id=$mirow['id_categoria'];
        $name=$mirow['nombre_categoria'];

        if($myRoot=="-1" && !$mirow['nodo_terminal']) {
            if($name=="Sony"){
                $name="<img src='images/frontend/sony.jpg' alt=''>";
            }
            if($name=="Nintendo"){
                $name="<img src='images/frontend/nintendo.jpg' alt=''>";
            }
            if($name=="Microsoft"){
                $name="<img src='images/frontend/microsoft.jpg' alt=''>";
            }
            $menu->addElement(new HtmlText("<p id='header".($i+1)."' class='menu_head'>".$name."</p>"));
            $div=new HtmlDiv("menu_body");
            $div->addAttribute("class", "menu_body");
            $menu->addElement($div);
            buildMenu3($id,$div,$sp."&nbsp;&nbsp;");
        }

        if($myRoot=="-1" && $mirow['nodo_terminal']) {
            $menu->addElement(new HtmlText("<a href='admin.php?option=juegos&padre=".$id."'><p id='header".(i+1)."' class='menu_head'>".$name."</p></a>"));
            //buildMenu3($id,$menu);
            buildMenu3($id,$div,$sp."&nbsp;&nbsp;");
        }

        if($myRoot!="-1" && !$mirow['nodo_terminal']) {
            $name="<img src='images/frontend/flechamenu.jpg' alt=''>".$name;
            $div=new HtmlDiv("menu_list");
            $div->addAttribute("class", "menu_list");
            $div->addElement(new HtmlText("<p id='submenu".($i+1)."' class='menu_head'>".$sp.$name."</p>"));
            $div1=new HtmlDiv("menu_body");
            $div1->addAttribute("class", "menu_body");
            $div->addElement($div1);
            $menu->addElement($div);
            //buildMenu3($id,$div1);
            buildMenu3($id,$div1,$sp."&nbsp;&nbsp;&nbsp;&nbsp;");
        }

        if($myRoot!="-1" && $mirow['nodo_terminal']) {
            $menu->addElement(new HtmlText("<a href='admin.php?option=juegos&padre=".$id."'>".$sp.$name."</a>"));
            buildMenu3($id,$div,$sp);
        }
    }
}
//Menu
$menu=new HtmlContainer();

//Tabla que ocntiene todo (Titulo de menu y Menu)
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_menu");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");

//Titulo del menu en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_menu");
$div->addAttribute("class", "ui-widget ui-menu-content ui-corner-top");
$div_titulo=new HtmlDiv("div_titulo");
$div_titulo->addElement(new HtmlText("CONSOLAS"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

//Menu dentro de la segunda fila de la tabla
$div=new HtmlDiv("firstpane");
$div->addAttribute("class", "menu_list ui-widget ui-menu-content ui-corner-bottom");
buildMenu3(-1,$div);
$table->addCell($div);
$table->nextRow();

//Se agrega a una fila que va a hacer parte de la tabla exterior que contiene todo el codigo de este archivo
$td=new HtmlTd();
$td->addElement($table);
$tr=new HtmlTr();
$tr->addElement($td);
$menu->addElement($tr);

echo $menu->getHtml();
?>

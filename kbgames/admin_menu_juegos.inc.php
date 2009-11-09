<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

function buildMenu($myRoot,&$buildString) {
    $rows=DaoMgr::getDao()->executeQuery("select * from categoria where id_padre=$myRoot");
    if(sizeOf($rows)==0) {
        $buildString=substr($buildString, 0, -1);
    }
    $i=0;
    foreach($rows as $mirow) {
        $id=$mirow['id_categoria'];
        $name=$mirow['nombre_categoria'];
        $ulr="";
        if($mirow['nodo_terminal']) {
            $url="admin.php?option=juegos&padre=".$id;
        }
        if($i!=0) {
            $buildString.=",";
        }
        $buildString.="\n['', '$name', '$url','_self','',";

        buildMenu($id,$buildString);
        $i++;
    }
    $buildString.="]";
}

function buildMenu2($myRoot,&$buildString,&$flag=0,&$ianterior=0,&$anterior=0) {
    $rows=DaoMgr::getDao()->executeQuery("select * from categoria where id_padre=$myRoot order by nombre_categoria");
    $i=0;
    foreach($rows as $mirow) {
        $id=$mirow['id_categoria'];
        $name=$mirow['nombre_categoria'];
        $ulr="";
       
        if($myRoot=="-1" && !$mirow['nodo_terminal']){
            $buildString.="<p id='header".($i+1)."' class='menu_head'>".$name."</p>
                   <div class='menu_body'>
";
        }

        if($myRoot=="-1" && $mirow['nodo_terminal']){
            $buildString.="     <a href='admin.php?option=juegos&padre=".$id."'><p id='header".(i+1)."' class='menu_head'>".$name."</p></a>
";
        }

        if($myRoot!="-1" && !$mirow['nodo_terminal']){
            $buildString.="        <div class='menu_list'>
                            <p id='submenu".($i+1)."' class='menu_head'>".$name."</p>
                                <div class='menu_body'>
";
            if(($ianterior+1)==$nanterior && $nanterior!=0){
                $flag=1;
            }else{
                $flag=2;
            }
        }

        if($myRoot!="-1" && $mirow['nodo_terminal']){
            $buildString.="     <a href='admin.php?option=juegos&padre=".$id."'>".$name."</a>
";
        }
        $i++;
        $ianterior=$i;
        $nanterior=sizeOf($rows);
        buildMenu2($id,$buildString,$flag,$ianterior,$nanterior);
    }
   if($mirow['nodo_terminal']&& $flag==0){
       $buildString.="</div>";
   }else if($mirow['nodo_terminal'] && $flag==1){
        $buildString.="</div></div></div>";
            $flag=0;
    }else if($mirow['nodo_terminal'] && $flag==2){
        $buildString.="</div></div>";
            $flag=0;
    }
}

function buildMenu3($myRoot,&$menu,$sp="") {
    $rows=DaoMgr::getDao()->executeQuery("select * from categoria where id_padre=$myRoot");
    $i=0;
    foreach($rows as $mirow) {
        $i++;
        $id=$mirow['id_categoria'];
        $name=$mirow['nombre_categoria'];

        if($myRoot=="-1" && !$mirow['nodo_terminal']){
            $menu->addElement(new HtmlText("<p id='header".($i+1)."' class='menu_head'>".$name."</p>"));
            $div=new HtmlDiv("menu_body");
            $div->addAttribute("class", "menu_body");
            $menu->addElement($div);
            buildMenu3($id,$div,$sp."&nbsp;&nbsp;");
        }

        if($myRoot=="-1" && $mirow['nodo_terminal']){
            $menu->addElement(new HtmlText("<a href='admin.php?option=juegos&padre=".$id."'><p id='header".(i+1)."' class='menu_head'>".$name."</p></a>"));
            //buildMenu3($id,$menu);
            buildMenu3($id,$div,$sp."&nbsp;&nbsp;");
        }

        if($myRoot!="-1" && !$mirow['nodo_terminal']){
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

        if($myRoot!="-1" && $mirow['nodo_terminal']){
            $menu->addElement(new HtmlText("<a href='admin.php?option=juegos&padre=".$id."'>".$sp.$name."</a>"));
            //buildMenu3($id,$menu);
            buildMenu3($id,$div,$sp);
        }
    }
}

$menu=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("align","center");
$table->addAttribute("border","1");
$table->setWidth("50%");

$div=new HtmlDiv("firstpane");
$div->addAttribute("class", "menu_list");
buildMenu3(-1,$div);
$table->addCell($div);
$table->nextRow();
$menu->addElement($table);

echo $menu->getHtml();
?>

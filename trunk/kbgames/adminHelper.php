<?php
include_once("lib/dao.inc.php");
include_once("lib/html.inc.php");

$rpta="";

if($_REQUEST['accion']=="codigo") {
    if($_REQUEST['idjuego']!="") {
        $rows=DaoMgr::getDao()->executeQuery("select count(id_juego) as cod from juego where id_juego='".$_REQUEST['id_juego']."'");//."and tipo_inmueble=".$_POST['padre']);
        $cont=$rows[0]['cod'];
        if($cont>0) {
            $rpta='false';
        }else {
            $rpta='true';
        }
    }else {
        $rpta='true';
    }
}
if($_REQUEST['accion']=="miembro") {
    if($_REQUEST['idmiembro']!="") {
        $rows=DaoMgr::getDao()->executeQuery("select count(id_miembro) as cod from miembro where id_miembro='".$_REQUEST['id_miembro']."'");//."and tipo_inmueble=".$_POST['padre']);
        $cont=$rows[0]['cod'];
        if($cont>0) {
            $rpta='false';
        }else {
            $rpta='true';
        }
    }else {
        $rpta='true';
    }
}
echo $rpta;	
?>
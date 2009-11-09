<?php
include_once("lib/dao.inc.php");

if($_POST['caso']=='categorias') {
    $rs=DaoMgr::getDao()->executeQuery("select * from categoria where id_padre=(select id_categoria from categoria where nombre_categoria='MotoWi4')and nodo_terminal=1");
    $datos="";
    if($rs) {
        $i=0;
        foreach($rs as $arr) {
            $datos.="id_categoria".$i."=".$arr['id_categoria']."&"."nombre_categoria".$i."=".$arr['nombre_categoria']."&";
            $i++;
        }
        $datos.="tam=".$i;
        echo $datos;
    }else {
        echo "-1";
    }
}
if($_POST['caso']=='productos') {
    $rs=DaoMgr::getDao()->executeQuery('select * from producto where id_categoria='.$_POST['id_categoria']);
    $datos="";
    if($rs) {
        $i=0;
        foreach($rs as $arr) {
            $datos.="nombre".$i."=".$arr['nombre']."&"."descripcion".$i."=".$arr['descripcion']."&"."imagen".$i."=".$arr['imagen']."&"."especificaciones".$i."=".$arr['especificaciones']."&";
            $i++;
        }
        $datos.="tam=".$i;
        echo $datos;
    }else {
        echo "-1";
    }
}
if($_POST['caso']=='email') {
    $boundary = md5(time().rand(1,100));
    $fecha = date("d-M-y H:i");
    $nombre=$_POST['nombre'];
    $telefono=$_POST['telefono'];
    $para="info@assysltd.com";
    $de=$_POST['email'];
    $consulta=$_POST['consulta'];
    $mensaje=$_POST['mensaje'];
    $content='
                <html>
                <head>
                <title>Contacto ASSYS</title>
                </head>
                <body link="/* CCCCCC" vlink="/* FFFFFF" alink="/* CC0033">
                <table width="550" height="300" border="0" class="tImagen">
                  <tr>
                    <th width="550" height="200"> <table width="550" height="300" border="0">
                        <tr>
                          <td width="550" height="20" class="tImagen" valign="middle" bgcolor="/* 666666">
                            <p><font color="/* FFFFFF" size="-1" face="Verdana, Arial, Helvetica, sans-serif"><strong>Informacion del Contacto</strong></font></p></td>
                        </tr>
                        <tr>
                          <th width="550" height="280"><table width="550" height="280" border="0">
                              <tr>
                                <td width="80" height="20" align="left" valign="middle" bgcolor="/* CCCCCC">
                                  <p><font size="-2" face="Verdana, Arial, Helvetica, sans-serif">Nombre:</font></p></td>
                                <td width="470" height="20" align="left" valign="middle" bgcolor="/* 0099CC">
                                  <p><font color="/* FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="-2">'.$nombre.'</font></font></font></p></td>
                              </tr>
                              <tr>
                                <td width="80" height="20" align="left" valign="middle" bgcolor="/* CCCCCC">
                                  <p><font size="-2" face="Verdana, Arial, Helvetica, sans-serif">Teléfono:</font></p></td>
                                <td width="470" height="20" align="left" valign="middle" bgcolor="/* 0099CC">
                                  <p><font color="/* FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="-2">'.$telefono.'</font></font></font></p></td>
                              </tr>
                              <tr>
                                <td width="80" height="20" align="left" valign="middle" bgcolor="/* CCCCCC">
                                  <p><font face="Georgia, Times New Roman, Times, serif"><font size="-2"><font face="Verdana, Arial, Helvetica, sans-serif">Correo:</font></font></font></p></td>
                                <td width="470" height="20" align="left" valign="middle" bgcolor="/* 0099CC">
                                  <p><font color="/* FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="-2"><a href="'.$de.'">'.$de.'</a></font></font></font></p></td>
                              </tr>
                              <tr>
                                <td width="80" height="80" align="left" valign="top" bgcolor="/* CCCCCC">
                                  <p><font face="Georgia, Times New Roman, Times, serif"><font size="-2"><font face="Verdana, Arial, Helvetica, sans-serif">Mensaje:</font></font></font></p></td>
                                <td width="470" height="80" align="left" valign="top" bgcolor="/* 0099CC">
                                  <p><font color="/* FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="-2">'.$mensaje.'</font></font></font></p></td>
                              </tr>
                              <tr>
                                <td width="80" height="20" align="left" valign="middle" bgcolor="/* CCCCCC">
                                  <p><font face="Georgia, Times New Roman, Times, serif"><font size="-2"><font face="Verdana, Arial, Helvetica, sans-serif">Fecha:</font></font></font></p></td>
                                <td width="470" height="20" align="left" valign="middle" bgcolor="/* 0099CC">
                                  <p><font color="/* FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="-2">'.$fecha.'</font></font></font></p></td>
                              </tr>
                             </table></th>
                        </tr>
                      </table> </th>
                  </tr>
                </table>
                </body>
                </html>
                ';

    mail($para, $consulta,$content,"MIME-Version: 1.0\nContent-type: text/html; charset=UTF-8\nFrom: $nombre < $de >");
    echo "1";
}
?>

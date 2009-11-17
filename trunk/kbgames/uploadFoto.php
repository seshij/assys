<?php
include($_SERVER['DOCUMENT_ROOT'].'/foto_upload_script.php');
include_once("lib/dao.inc.php");

if($_POST['accion']=="upload") {
    $foto_upload = new Foto_upload;

    $json['size'] = $_POST['MAX_FILE_SIZE'];
    $json['img'] = '';

    $foto_upload->upload_dir = "./media/images/";
    $foto_upload->foto_folder = "./media/images/".$_POST['cod']."/";
    $foto_upload->thumb_folder = "./media/images/thumbs/".$_POST['cod']."/";
    $foto_upload->extensions = array(".jpg", ".gif", ".png",".jpeg");
    $foto_upload->language = "es";
    $foto_upload->x_max_size = 380;
    $foto_upload->y_max_size = 260;
    $foto_upload->x_max_thumb_size = 120;
    $foto_upload->y_max_thumb_size = 120;

    $foto_upload->the_temp_file = $_FILES['upload']['tmp_name'];
    $foto_upload->the_file = $_FILES['upload']['name'];
    $foto_upload->http_error = $_FILES['upload']['error'];
    $foto_upload->rename_file = false;

    if ($foto_upload->upload()) {
        $foto_upload->process_image(false, true, true, 80);
        $json['img'] = $foto_upload->file_copy;
        echo "";
    }
    echo $foto_upload->the_file;
}else if($_POST['accion']=="upload_caratula") {
        $foto_upload = new Foto_upload;

        $json['size'] = $_POST['MAX_FILE_SIZE'];
        $json['img'] = '';

        $foto_upload->upload_dir = "./media/caratulas/";
        $foto_upload->foto_folder = "./media/caratulas/".$_POST['cod']."/";
        $foto_upload->thumb_folder = "./media/caratulas/thumbs/".$_POST['cod']."/";
        $foto_upload->extensions = array(".jpg", ".gif", ".png",".jpeg");
        $foto_upload->language = "es";
        $foto_upload->x_max_size = 380;
        $foto_upload->y_max_size = 260;
        $foto_upload->x_max_thumb_size = 120;
        $foto_upload->y_max_thumb_size = 120;

        $foto_upload->the_temp_file = $_FILES['upload_caratula']['tmp_name'];
        $foto_upload->the_file = $_FILES['upload_caratula']['name'];
        $foto_upload->http_error = $_FILES['upload_caratula']['error'];
        $foto_upload->rename_file = false;

        if ($foto_upload->upload()) {
            $foto_upload->process_image(false, true, true, 80);
            $json['img'] = $foto_upload->file_copy;
            echo "";
        }
        echo $foto_upload->the_file;
    }else if($_POST['accion']=="upload_imgnoticia") {
        $foto_upload = new Foto_upload;

        $json['size'] = $_POST['MAX_FILE_SIZE'];
        $json['img'] = '';

        $foto_upload->upload_dir = $_SERVER['DOCUMENT_ROOT']."/kbgames/media/noticias/";
        $foto_upload->foto_folder = $_SERVER['DOCUMENT_ROOT']."/kbgames/media/noticias/";
        $foto_upload->thumb_folder = $_SERVER['DOCUMENT_ROOT']."/kbgames/media/noticias/thumbs/";
        $foto_upload->extensions = array(".jpg", ".gif", ".png",".jpeg");
        $foto_upload->language = "es";
        $foto_upload->x_max_size = 380;
        $foto_upload->y_max_size = 260;
        $foto_upload->x_max_thumb_size = 120;
        $foto_upload->y_max_thumb_size = 120;

        $foto_upload->the_temp_file = $_FILES['upload_imgnoticia']['tmp_name'];
        $foto_upload->the_file = $_FILES['upload_imgnoticia']['name'];
        $foto_upload->http_error = $_FILES['upload_imgnoticia']['error'];
        $foto_upload->rename_file = false;

        if ($foto_upload->upload()) {
            $foto_upload->process_image(false, true, true, 80);
            $json['img'] = $foto_upload->file_copy;
            echo "";
        }
        echo $foto_upload->the_file;
    }else if($_POST['accion']=="delete") {
            if($_POST['id_foto']!="-1") {
                DaoMgr::getDao()->execute("delete from foto where id_foto=".$_POST['id_foto']);
                $dirf="./media/images/".$_POST['cod'];
                $dirt="./images/thumbs/".$_POST['cod'];

                unlink($dirf."/".str_replace(" ", "_",$_POST['nom_foto']));
                unlink($dirt."/".str_replace(" ", "_",$_POST['nom_foto']));

                if(isEmptyDir($dirf)) {
                    rmdir($dirf);
                }
                if(isEmptyDir($dirt)) {
                    rmdir($dirt);
                }

                echo 'true';
            }else {
                echo 'false';
            }
        }else if($_POST['accion']=="delete_caratula") {
                if($_POST['id_foto']!="-1") {
                //DaoMgr::getDao()->execute("delete from foto where id_foto=".$_POST['id_foto']);
                    $dirf="./media/caratulas/".$_POST['cod'];
                    $dirt="./media/caratulas/thumbs/".$_POST['cod'];

                    unlink($dirf."/".str_replace(" ", "_",$_POST['nom_foto']));
                    unlink($dirt."/".str_replace(" ", "_",$_POST['nom_foto']));

                    if(isEmptyDir($dirf)) {
                        rmdir($dirf);
                    }
                    if(isEmptyDir($dirt)) {
                        rmdir($dirt);
                    }

                    echo 'true';
                }else {
                    echo 'false';
                }
            }else if($_POST['accion']=="delete_imgnoticia") {
                //DaoMgr::getDao()->execute("delete from foto where id_foto=".$_POST['id_foto']);
                    $dirf=$_SERVER['DOCUMENT_ROOT']."/media/noticias";
                    $dirt=$_SERVER['DOCUMENT_ROOT']."/media/noticias/thumbs";

                    unlink($dirf."/".str_replace(" ", "_",$_POST['nom_foto']));
                    unlink($dirt."/".str_replace(" ", "_",$_POST['nom_foto']));

                    if(isEmptyDir($dirf)) {
                        rmdir($dirf);
                    }
                    if(isEmptyDir($dirt)) {
                        rmdir($dirt);
                    }

                    echo 'true';
                    
                }

function isEmptyDir($dir) {
    if ($handle = opendir($dir)) {
        $array = array();
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if(is_dir($dir.$file)) {
                    rmdir($dir.$file); // Empty directory? Remove it
                }
            }
        }
        closedir($handle);

        rmdir($dir);
    }
}
?>
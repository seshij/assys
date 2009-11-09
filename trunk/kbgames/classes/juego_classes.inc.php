<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");
include_once("juego_classes.inc.php");
class Juego extends DBElement {

    var $id_juego;
    var $id_categoria;
    var $id_rating;
    var $imagen_adelante;
    var $imagen_atras;
    var $desarrollador;
    var $calificacion;
    var $stock;
    var $num_vendidos;
    var $fecha_lanzamiento;
    var $estado;
    var $precio;

    public function getTableDescriptor() {
        $td = new TableDescriptor("juego");

        $td->addField("id_juego","id_juego", true);
        $td->addField("id_categoria","id_categoria", false);
        $td->addField("id_rating","id_rating", false);
        $td->addField("imagen_adelante","imagen_adelante", true);
        $td->addField("imagen_atras","imagen_atras", true);
        $td->addField("desarrollador","desarrollador", true);
        $td->addField("calificacion","calificacion", false);
        $td->addField("stock","stock", false);
        $td->addField("num_vendidos","num_vendidos", false);
        $td->addField("fecha_lanzamiento","fecha_lanzamiento", true);
        $td->addField("estado","estado", true);
        $td->addField("precio","precio", false);

        $td->setFindable("id_juego", true);
        $td->setUpdateable("id_juego", false);
        $td->setInsertable("id_juego", true);

        return $td;
    }

    public function getId_juego() {
        return $this->id_juego;
    }

    public function setId_juego($c) {
        $this->id_juego = $c;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($c) {
        $this->id_categoria = $c;
    }

    public function getId_rating() {
        return $this->id_rating;
    }

    public function setId_rating($c) {
        $this->id_rating = $c;
    }

    public function getImagen_adelante() {
        return $this->imagen_adelante;
    }

    public function setImagen_adelante($c) {
        $this->imagen_adelante = $c;
    }

    public function getImagen_atras() {
        return $this->imagen_atras;
    }

    public function setImagen_atras($c) {
        $this->imagen_atras = $c;
    }

    public function getDesarrollador() {
        return $this->desarrollador;
    }

    public function setDesarrollador($c) {
        $this->desarrollador = $c;
    }
    public function getCalificacion() {
        return $this->calificacion;
    }

    public function setCalificacion($c) {
        $this->calificacion = $c;
    }
    public function getStock() {
        return $this->stock;
    }

    public function setStock($c) {
        $this->stock = $c;
    }
    public function getNum_vendidos() {
        return $this->num_vendidos;
    }

    public function setNum_vendidos($c) {
        $this->num_vendidos = $c;
    }

    public function getFecha_lanzamiento() {
        return $this->fecha_lanzamiento;
    }

    public function setFecha_lanzamiento($c) {
        $this->fecha_lanzamiento = $c;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($c) {
        $this->estado = $c;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($c) {
        $this->precio = $c;
    }
}

class JuegoController extends AbstractComtorFacadeAdministratorController {
    var $parent_id;
    public function JuegoController($parent_id) {
        $this->parent_id=$parent_id;

    }
    public function getAddFormLabel() {
        return "Adicionar  Juego";
    }

    protected function getBaseUrl() {
        return "?option=" . $this->getOption()."&padre=".$this->parent_id;
    }
    public function getAddNewObjectLabel() {
        return "Nuevo Juego";
    }

    public function getAddPrivilege() {
        return "INMUEBLE_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar Juego.";
    }

    public function getAddedMessage($object) {
        return "El Juego ha sido adicionado.";
    }

    public function getConfirmDeleteMessage($object) {
        $cat = $object;
        return "Confirma eliminar el siguiente Juego: " . $cat->getId_juego(). "]?";
    }

    public function getDeletePrivilege() {
        return "INMUEBLE_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar Juego.";
    }

    public function getDeletedMessage($element) {
        return "El Juego ha sido eliminado.";
    }

    public function getEditFormLabel() {
        return "Editar Juego";
    }

    public function getEditPrivilege() {
        return "INMUEBLE_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar Juego";
    }
    public function getViewPrivilege() {
        return "INMUEBLE_VIEW";
    }

    public function getViewPrivilegeMsg() {
        return "Ud. no posee privilegios para ver Juego";
    }

    public function getFormName() {
        return "juego_form";
    }

    public function getLogicFacade() {
        return new JuegoLogicFacade($this->parent_id);
    }

    public function getUpdatedMessage($object) {
        return "El Juego ha sido editado.";
    }

    public function initForm($form, $juego) {

        $form->getForm()->addAttribute("id",$this->getFormName());
        $form->getForm()->addAttribute("class","frmjuego");

        $form->addMessage("Todos los campos son requeridos");

        $cod=new HtmlInputText("id_juego", "", 50, 200);
        $form->addField("id_juego", "Codigo", $cod, "Ingrese el Codigo del  de Juego");
        if($_REQUEST["action"]=="editform") {
            $cod->addAttribute("disabled",true);
        }
        $form->addInputHidden("id_categoria",$this->parent_id);

        $calif=DaoMgr::getDao()->executeQuery("select * from rating");
        $selectc=new HtmlSelect("id_rating");
        $selectc->addOption(" ","Seleccione Clasificaci&oacute;n");
        foreach ($calif as $c) {
            $selectc->addOption($c["id_rating"],$c["rating"]);
        }
        $form->addField("id_rating","Clasificaci&oacute;n", $selectc,"Seleccione la clasificaci&oacute;n del juego");

        $this->loadDetalles($form);

        $form->addField("desarrollador", "Desarrollador", new HtmlInputText("desarrollador", "", 50, 200), "Ingrese el nombre de la empresa desarrolladora del juego");
        $form->addField("stock", "Stock", new HtmlInputText("stock", "", 50, 200), "Ingrese el numero de ejemplares que existe en inventario");
        $form->addField("fecha_lanzamiento", "Fecha de Aparici&oacute;n<br>(AAAA-MM-DD)", new HtmlInputText("fecha_lanzamiento", "", 50, 200), "Ingrese la fecha en que salio o saldra el juego");
        $form->addField("precio", "Precio", new HtmlInputText("precio", "", 50, 200), "Ingrese el precio del juego");

        $cont=new HtmlContainer();
        $intro1 =new HtmlRadio("estado","proximo","Proximo a Salir");
        $cont->addElement($intro1);
        $intro2=new HtmlRadio("estado","existente","Existente");
        $cont->addElement($intro2);
        $label=new HtmlTag("label");
        $label->addAttribute("for","estado");
        $label->addAttribute("class","error");
        $label->addTextElement(" * campo requerido");
        $cont->addElement($label);
        $this->setRadioButtonState("estado",$intro1,$intro2);
        $form->addField("estado","Estado",$cont,"Seleccione si el juego existe o si esta proximo a salir");

        $form->addField("caratulas","",$this->loadCaratulasDiv($juego, $form),"Seleccione las caratulas que desea subir");
        $form->addField("imagenes","",$this->loadImagesDiv($juego, $form),"Seleccione las imagenes que desea subir");

        $form->addInputHidden("calificacion","0");
        $form->addInputHidden("num_vendidos","0");
    }

    private function loadDetalles(&$form,$juego) {
        $t="";
        $desc="";
        $pv="";

        if($_REQUEST['key']!=NULL) {
            $datos=DaoMgr::getDao()->executeQuery("select titulo,descripcion,prevrev from detalle_juego where id_juego='".$_REQUEST['key']."' and id_idioma=1");
            $t=$datos[0]['titulo'];
            $desc=$datos[0]['descripcion'];
            $pv=$datos[0]['prevrev'];
        }

        $titulo=new HtmlInputText("titulo", $t, 50, 200);
        $form->addField("titulo", "T&iacute;tulo", $titulo, "Ingrese Barrio donde se encuentra el Juego");

        $txtDescripcion=new HtmlTextArea("descripcion",$desc,50,10);
        $txtDescripcion->addAttribute("id", "descripcion");
        $form->addField("descripcion","Descripci&oacute;n", $txtDescripcion,"Escriba un preview si el juego esta proximo a salir o un review si el juego es existente");

        $txtPrevrev=new HtmlTextArea("prevrev",$pv,50,10);
        $txtPrevrev->addAttribute("id", "prevrev");
        $form->addField("prevrev","Preview o Review", $txtPrevrev,"Escriba un preview si el juego esta proximo a salir o un review si el juego es existente");
    }

    private function setRadioButtonState($campo,&$rb1,&$rb2) {
        if($_REQUEST['key']!=NULL) {
            $dato=DaoMgr::getDao()->executeQuery("select ".$campo." from juego where id_juego='".$_REQUEST['key']."'");
            if($dato[0][$campo]==1) {
                $rb1->addAttribute("checked","true");
            }else {
                $rb2->addAttribute("checked","true");
            }
        }
    }

    private function loadImagesDiv($juego,&$form) {
        $fotosDiv=new HtmlDiv("div_fotos");
        $nfotos=0;
        if($juego!=NULL) {
            $query="select * from foto where id_juego='".$juego->getId_juego()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $nfotos=sizeOf($rowsUR);
        }

        $fotosTab=new HtmlTable();
        $fotosTab->setWidth("60%");
        $fotosTab->addAttribute("border","0");
        $fotosTab->setClass("ui-widget-content");
        $th=new HtmlTh("Imagenes");
        $th->addAttribute("colspan","3");
        $th->addAttribute("align","center");
        $th->setClass("ui-widget-header ui-corner-all");
        $fotosTab->getCurrentRow()->addElement($th);
        $fotosTab->nextRow();

        for($i=0;$i<5;$i++) {
            $id_f="";
            $id_i="";
            $ruta_f="";
            $nombre="";
            if($juego!=NULL) {
                if($nfotos>$i) {
                    $id_f=$rowsUR[$i]['id_foto'];
                    $id_i=$rowsUR[$i]['id_juego'];
                    $ruta_f=$rowsUR[$i]['ruta_imagen'];
                    $nombre=$rowsUR[$i]['nombre'];
                }
            }

            $form->addInputHidden("id_foto_".$i,$id_f);
            if($juego!=NULL && $nombre!="") {
                $fotosTab->addCell("<div id='img_".$i."'><img src='../media/images/thumbs/".$id_i."/".$nombre."' alt='' id='img".$i."'><div>");
            }else {
                $fotosTab->addCell("<div id='img_".$i."'></div>");
            }

            $fotosTab->nextRow();
            $intro=new HtmlInputText("ruta_f_".$i, $nombre, 35, 100);
            $fotosTab->addCell($intro);

            $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-plus' id='upload_button".$i."'></div>");
            $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-close' id='delete_button".$i."'></div>");
            $fotosTab->nextRow();
        }
        $fotosDiv->addElement($fotosTab);
        return $fotosDiv;
    }

    private function loadCaratulasDiv($juego,&$form) {
        $fotosDiv=new HtmlDiv("div_caratulas");
        $nfotos=0;
        $nombre_adelante="";
        $nombre_atras="";
        $thumb_adelante="";
        $thumb_atras="";

        $fotosTab=new HtmlTable();
        $fotosTab->setWidth("60%");
        $fotosTab->addAttribute("border","0");
        $fotosTab->setClass("ui-widget-content");
        $th=new HtmlTh("Caratulas");
        $th->addAttribute("colspan","3");
        $th->setClass("ui-widget-header ui-corner-all");
        $fotosTab->getCurrentRow()->addElement($th);
        $fotosTab->nextRow();

        if($juego!=NULL) {
            $query="select imagen_adelante,imagen_atras from juego where id_juego='".$juego->getId_juego()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $nombre_adelante=$rowsUR[0]['imagen_adelante'];
            $nombre_atras=$rowsUR[0]['imagen_atras'];
            $thumb_adelante="../media/caratulas/thumbs/".$juego->getId_juego()."/".$nombre_adelante;
            $thumb_atras="../media/caratulas/thumbs/".$juego->getId_juego()."/".$nombre_atras;
        }

        if($juego!=NULL && $nombre_adelante!='') {
            $fotosTab->addCell("<div id='img_adelante'><img src='".$thumb_adelante."' alt='' id='imgadelante'></div>");
        }else {
            $fotosTab->addCell("<div id='img_adelante'></div>");
        }
        $fotosTab->nextRow();
        $caratula_adelante=new HtmlInputText("imagen_adelante", $nombre_adelante, 35, 100);
        $fotosTab->addCell($caratula_adelante);
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-plus' id='upload_caratula_adelante'></div>");
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-close' id='delete_caratula_adelante'></div>");
        $fotosTab->nextRow();

        if($juego!=NULL && $nombre_atras!='') {
            $fotosTab->addCell("<div id='img_atras'><img src='".$thumb_atras."' alt='' id='imgatras'></div>");
        }else {
            $fotosTab->addCell("<div id='img_atras'></div>");
        }
        $fotosTab->nextRow();
        $caratula_atras=new HtmlInputText("imagen_atras", $nombre_atras, 35, 100);
        $fotosTab->addCell($caratula_atras);
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-plus' id='upload_caratula_atras'></div>");
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-close' id='delete_caratula_atras'></div>");
        $fotosTab->nextRow();

        $fotosDiv->addElement($fotosTab);
        return $fotosDiv;
    }


    private function loadjuego($cat) {
        return $cat;
    }

    public function getDeleteMessage($objectKeys) {
        $juego = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar el siguiente Juego : " . $juego->getId_juego() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("Codigo");
        $resp->add("T&iacute;tulo");
        return $resp;
    }

    public function getKey($obj) {
        $juego = $obj;
        return $juego->getId_juego();
    }

    public function getRow($o) {
        $juego = $o;
        $resp = new Vector();
        $resp->add($juego->getId_juego());
        //$resp->add($juego->getNombre());
        return $resp;
    }
}

class JuegoLogicFacade implements BusinessLogicFacade {
    var $parent_id;

    public function JuegoLogicfacade($parent_id) {
        $this->parent_id=$parent_id;

    }
    public function delete($key) {
        try {
            $juego = $this->find($key);
            if ($juego instanceof Juego) {
                DaoMgr::getDao()->delete($juego, $juego->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function find($key) {
        $object = new Juego();
        $object->setId_juego($key);
        try {
            return DaoMgr::getDao()->load($object, $object->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function getObjectsList($filters) {
        return $this->createObjectList($filters, false);
    }

    private function createObjectList($filters, $withLimit) {

        $rows = DaoMgr::getDao()-> executeQuery($this->createQuery($filters, $withLimit));
        $vector = new Vector();
        foreach ($rows as $row) {
            $pc = new Juego();
            $pc = DaoMgr::getDao()->getObject($pc, $row);
            $vector->add($pc);
        }
        return $vector;
    }

    private function createQuery($filters, $withLimit) {
        $where = " 1 = 1 and id_categoria=".$this->parent_id." ";
        if ($filters != NULL) {
            $filter = "";
            $filter_array = $filters->toArray();
            foreach ($filter_array as $listFilter) {
                $id = $listFilter->getId();
                if ($id == "filter") {
                    $value = $listFilter->getValue();
                    $filter = ($value != NULL) ? $value : $filter;
                }
            }

            $tokens = explode(" ", $filter);
            foreach ($tokens as $token) {
                $token = str_replace(" ", "'", $token);
                $where .= " and  (id_juego like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where;
        if ($withLimit) {
            $query .= " limit 100 ";
        }
        return $query;
    }

    public function getObjectsListForFinder($filters) {
        return $this->getObjectsList($filters);
    }

    public function getObjectsListWithParameter($filters) {
        return $this->getObjectsListForFinder($filters);
    }

    public function getQueryString() {
        return "select * from juego";
    }

    public function getSampleElement() {
        return new Juego();
    }

    public function insert($object) {
        try {
            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
            $detalles=new Detalle_juego();
            $detalles->setId_juego($object->getId_juego());
            $detalles->setId_idioma("1");
            $detalles->setTitulo($_REQUEST["titulo"]);
            $detalles->setDescripcion($_REQUEST["descripcion"]);
            $detalles->setPrevrev($_REQUEST["prevrev"]);
            DaoMgr::getDao()->insert($detalles, $detalles->getTableDescriptor());
            $this->addFotos($object->getId_juego(),false);
        }
        catch (Exception $exception ) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object) {
        try {
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());
            $detalles=new Detalle_juego();
            $detalles->setId_juego($object->getId_juego());
            $detalles->setId_idioma("1");
            $detalles->setTitulo($_REQUEST["titulo"]);
            $detalles->setDescripcion($_REQUEST["descripcion"]);
            $detalles->setPrevrev($_REQUEST["prevrev"]);
            DaoMgr::getDao()->update($detalles, $detalles->getTableDescriptor());
            $this->addFotos($object->getId_juego(),true);
        }
        catch (Exception $exception ) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    private function uploadFile() {
        $uploaddir = 'images/frontend/juegos/';
        $uploadfile1 = $uploaddir . basename($_FILES['caratula_adelante']['name']);
        $uploadfile2 = $uploaddir . basename($_FILES['caratula_atras']['name']);

        if (move_uploaded_file($_FILES['caratula_adelante']['tmp_name'], $uploadfile1)) {
        } else {
            echo "Fallo el envio de la caratula anterior\n";
        }

        if (move_uploaded_file($_FILES['caratula_atras']['tmp_name'], $uploadfile2)) {
        } else {
            echo "Fallo el envio de la caratula posterior\n";
        }
    }

    private function addFotos($id_juego,$isEdit) {
        if($isEdit) {
            $query="delete from foto where id_juego='".$id_juego."'";
            DaoMgr::getDao()->execute($query);
        }
        $i=0;
        while($i<5) {
            if(isset($_REQUEST["ruta_f_".$i]) && $_REQUEST["ruta_f_".$i]!="") {
                $foto=new Foto();
                $foto->setRuta_imagen($_SERVER['DOCUMENT_ROOT']."/kbgames/images/".$_REQUEST['id_juego']."/".$_REQUEST['ruta_f_'.$i]);
                $foto->setRuta_thumbs($_SERVER['DOCUMENT_ROOT']."/kbgames/images/thumbs/".$_REQUEST['id_juego']."/".$_REQUEST['ruta_f_'.$i]);
                $foto->setNombre($_REQUEST['ruta_f_'.$i]);
                $foto->setId_juego($id_juego);
                DaoMgr::getDao()->insert($foto, $foto->getTableDescriptor());
            }
            $i++;
        }
    }

    public function validateObjectPreAdd($object) {
        $juego = $object;

        $vector = new Vector();
        if
        ($juego->getId_juego()==NULL || $juego->getId_juego()=="") {
            $error=new ObjectValidatorException("juego","Falto el nombre");
            $vector->addElement($error);
        }


        return $vector;
    }

    public function validateObjectPreDelete($object) {
        $vector = new Vector();
        return $vector;
    }

    public function validateObjectPreEdit($object) {

        return $this->validateObjectPreAdd($object);
    }

}

class AdminJuego extends ComtorAbstractAdministrable {
    var $parent_id;

    public function AdminJuego ($parent_id) {
        $this->parent_id=$parent_id;

    }
    public function getController() {
        return new JuegoController($this->parent_id);
    }
}

class Foto extends DBElement {

    var $id_foto;
    var $id_juego;
    var $nombre;
    var $ruta_imagen;
    var $ruta_thumbs;

    public function getTableDescriptor() {
        $td = new TableDescriptor("foto");

        $td->addField("id_foto","id_foto", true);
        $td->addField("id_juego","id_juego", true);
        $td->addField("nombre","nombre", true);
        $td->addField("ruta_imagen","ruta_imagen", true);
        $td->addField("ruta_thumbs","ruta_thumbs", true);

        $td->setFindable("id_foto", true);
        $td->setUpdateable("id_foto", false);
        $td->setInsertable("id_foto", false);

        return $td;
    }

    public function getId_foto() {
        return $this->id_foto;
    }

    public function setId_foto($id) {
        $this->id_foto = $id;
    }

    public function getId_juego() {
        return $this->id_juego;
    }

    public function setId_juego($id) {
        $this->id_juego = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($id) {
        $this->nombre = $id;
    }

    public function getRuta_imagen() {
        return $this->ruta_imagen;
    }

    public function setRuta_imagen($ruta) {
        $this->ruta_imagen = $ruta;
    }

    public function getRuta_thumbs() {
        return $this->ruta_thumbs;
    }

    public function setRuta_thumbs($ruta) {
        $this->ruta_thumbs = $ruta;
    }
}

class Detalle_juego extends DBElement {

    var $id_juego;
    var $id_idioma;
    var $titulo;
    var $descripcion;
    var $prevrev;

    public function getTableDescriptor() {
        $td = new TableDescriptor("detalle_juego");

        $td->addField("id_juego","id_juego", true);
        $td->addField("id_idioma","id_idioma", true);
        $td->addField("titulo","titulo", true);
        $td->addField("descripcion","descripcion", true);
        $td->addField("prevrev","prevrev", true);

        $td->setFindable("id_juego", true);
        $td->setUpdateable("id_juego", false);
        $td->setInsertable("id_juego", true);
        $td->setFindable("id_idioma", true);
        $td->setUpdateable("id_idioma", false);
        $td->setInsertable("id_idioma", true);

        return $td;
    }

    public function getId_juego() {
        return $this->id_juego;
    }

    public function setId_juego($id) {
        $this->id_juego = $id;
    }

    public function getId_idioma() {
        return $this->id_idioma;
    }

    public function setId_idioma($id) {
        $this->id_idioma = $id;
    }

    public function getPrevrev() {
        return $this->prevrev;
    }

    public function setPrevrev($id) {
        $this->prevrev = $id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($id) {
        $this->titulo = $id;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($id) {
        $this->descripcion = $id;
    }
}
?>
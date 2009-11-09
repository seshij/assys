<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");

class Noticia extends DBElement {

    var $id_noticia;
    var $id_idioma;
    var $titulo;
    var $contenido;
    var $imagen;

    public function getTableDescriptor() {
        $td = new TableDescriptor("noticias");

        $td->addField("id_noticia","id_noticia", false);
        $td->addField("id_idioma","id_idioma", false);
        $td->addField("titulo","titulo", true);
        $td->addField("contenido","contenido", true);
        $td->addField("imagen","imagen", true);

        $td->setFindable("id_noticia", true);
        $td->setUpdateable("id_noticia", false);
        $td->setInsertable("id_noticia", false);

        return $td;
    }

    public function getId_noticia() {
        return $this->id_noticia;
    }

    public function setId_noticia($c) {
        $this->id_noticia = $c;
    }

    public function getId_idioma() {
        return $this->id_idioma;
    }

    public function setId_idioma($c) {
        $this->id_idioma = $c;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setTitulo($c) {
        $this->titulo = $c;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function setContenido($c) {
        $this->contenido = $c;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($c) {
        $this->imagen = $c;
    }
}

class NoticiaController extends AbstractComtorFacadeAdministratorController {
    public function getAddFormLabel() {
        return "Adicionar Noticia";
    }

    protected function getBaseUrl() {
        return "?option=" . $this->getOption();
    }
    public function getAddNewObjectLabel() {
        return "Nueva Noticia o Evento";
    }

    public function getAddPrivilege() {
        return "NOTICIA_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar Noticia.";
    }

    public function getAddedMessage($object) {
        return "El Noticia ha sido adicionado.";
    }

    public function getConfirmDeleteMessage($object) {
        $cat = $object;
        return "Confirma eliminar el siguiente Noticia: " . $cat->getId_noticia(). "]?";
    }

    public function getDeletePrivilege() {
        return "NOTICIA_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar Noticia.";
    }

    public function getDeletedMessage($element) {
        return "El Noticia ha sido eliminado.";
    }

    public function getEditFormLabel() {
        return "Editar Noticia";
    }

    public function getEditPrivilege() {
        return "NOTICIA_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar Noticia";
    }
    public function getViewPrivilege() {
        return "NOTICIA_VIEW";
    }

    public function getViewPrivilegeMsg() {
        return "Ud. no posee privilegios para ver Noticia";
    }

    public function getFormName() {
        return "noticia_form";
    }

    public function getLogicFacade() {
        return new NoticiaLogicFacade();
    }

    public function getUpdatedMessage($object) {
        return "El Noticia ha sido editado.";
    }

    public function initForm($form, $noticia) {

        $form->getForm()->addAttribute("id",$this->getFormName());
        $form->getForm()->addAttribute("class","frmjuego");
        $form->addInputHidden("id_noticia","-1");
        $form->addInputHidden("id_idioma",1);
        $form->addMessage("Todos los campos son requeridos");

        $form->addField("titulo", "T&iacute;tulo", new HtmlInputText("titulo", "", 50, 200), "Ingrese el T&iacute;tulo del Noticia");
        $form->addField("contenido","Contenido", new HtmlTextArea("contenido","",50,10),"Escriba el contenido de la noticia o evento");
        $form->addField("imagen","",$this->loadImagenDiv($noticia, $form),"Seleccione la imagen de la noticia o evento");
    }

    private function loadImagenDiv($noticia,&$form) {
        $fotosDiv=new HtmlDiv("div_imgnoticia");
        $nfotos=0;
        $nombre_imagen="";
        $thumb="";

        $fotosTab=new HtmlTable();
        $fotosTab->setWidth("60%");
        $fotosTab->addAttribute("border","0");
        $fotosTab->setClass("ui-widget-content");
        $th=new HtmlTh("Imagen de Noticia o Evento");
        $th->addAttribute("colspan","3");
        $th->setClass("ui-widget-header ui-corner-all");
        $fotosTab->getCurrentRow()->addElement($th);
        $fotosTab->nextRow();

        if($noticia!=NULL) {
            $query="select imagen from noticias where id_noticia='".$noticia->getId_noticia()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $nombre_imagen=$rowsUR[0]['imagen'];
            $thumb="../media/noticias/thumbs/".$nombre_imagen;
        }

        if($noticia!=NULL && $nombre_imagen!='') {
            $fotosTab->addCell("<div id='img_noticia'><img src='".$thumb."' alt='' id='imgnoticia'></div>");
        }else {
            $fotosTab->addCell("<div id='img_noticia'></div>");
        }
        $fotosTab->nextRow();
        $imagen=new HtmlInputText("imagen", $nombre_imagen, 35, 100);
        $fotosTab->addCell($imagen);
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-plus' id='upload_imagen_noticia'></div>");
        $fotosTab->addCell("<div style='border:1px solid #ffffff' class='ui-icon ui-icon-circle-close' id='delete_imagen_noticia'></div>");
        $fotosTab->nextRow();

        $fotosDiv->addElement($fotosTab);
        return $fotosDiv;
    }


    private function loadnoticia($cat) {
        return $cat;
    }

    public function getDeleteMessage($objectKeys) {
        $noticia = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar el siguiente Noticia : " . $noticia->getId_noticia() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("T&iacute;tulo");
        return $resp;
    }

    public function getKey($obj) {
        $noticia = $obj;
        return $noticia->getId_noticia();
    }

    public function getRow($o) {
        $noticia = $o;
        $resp = new Vector();
        $resp->add($noticia->getTitulo());
        return $resp;
    }
}

class NoticiaLogicFacade implements BusinessLogicFacade {
    public function delete($key) {
        try {
            $noticia = $this->find($key);
            if ($noticia instanceof Noticia) {
                DaoMgr::getDao()->delete($noticia, $noticia->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function find($key) {
        $object = new Noticia();
        $object->setId_noticia($key);
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
            $pc = new Noticia();
            $pc = DaoMgr::getDao()->getObject($pc, $row);
            $vector->add($pc);
        }
        return $vector;
    }

    private function createQuery($filters, $withLimit) {
        $where = " 1 = 1";
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
                $where .= " and  (id_noticia like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where. " order by fecha_ingreso";
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
        return "select * from noticias";
    }

    public function getSampleElement() {
        return new Noticia();
    }

    public function insert($object) {
        try {
            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
        }
        catch (Exception $exception ) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object) {
        try {
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());
        }
        catch (Exception $exception ) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function validateObjectPreAdd($object) {
        $noticia = $object;

        $vector = new Vector();
        if
        ($noticia->getId_noticia()==NULL || $noticia->getId_noticia()=="") {
            $error=new ObjectValidatorException("noticia","Falto el nombre");
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

class AdminNoticia extends ComtorAbstractAdministrable {
    public function getController() {
        return new NoticiaController();
    }
}
?>
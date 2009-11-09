<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");

class Categoria extends DBElement {

    var $id_categoria;
    var $nombre_categoria;
    var $id_padre;
    var $nodo_terminal;
    var $orden;

    public function getTableDescriptor() {
        $td = new TableDescriptor("categoria");

        $td->addField("id_categoria","id_categoria", false);
        $td->addField("nombre_categoria","nombre_categoria", true);
        $td->addField("id_padre","id_padre", false);
        $td->addField("nodo_terminal","nodo_terminal", false);
        $td->addField("orden","orden", false);

        $td->setFindable("id_categoria", true);
        $td->setUpdateable("id_categoria", false);
        $td->setInsertable("id_categoria", false);

        return $td;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($c) {
        $this->id_categoria = $c;
    }

    public function getNombre_categoria() {
        return $this->nombre_categoria;
    }

    public function setNombre_categoria($c) {
        $this->nombre_categoria = $c;
    }

    public function getId_padre() {
        return $this->id_padre;
    }

    public function setId_padre($c) {
        $this->id_padre = $c;
    }

    public function getNodo_terminal() {
        return $this->nodo_terminal;
    }

    public function setNodo_terminal($c) {
        $this->nodo_terminal = $c;
    }

    public function getOrden() {
        return $this->orden;
    }

    public function setOrden($c) {
        $this->orden = $c;
    }
}

class CategoriaController extends AbstractComtorFacadeAdministratorController {

    var $parent_id;
    public function CategoriaController($parent_id) {
        $this->parent_id=$parent_id;

    }

    public function getAddFormLabel() {
        return "Adicionar Categoria";
    }

    protected function getBaseUrl() {
        if($this->parent_id=="-1") {
            return "?option=" . $this->getOption();
        }else {
            return "?option=" . $this->getOption()."&padre=".$this->parent_id;
        }
    }
    public function getAddNewObjectLabel() {
        if($this->parent_id=="-1") {
            return "Nueva Categoria";
        }else {
            $row=DaoMgr::getDao()->executeQuery("select * from categoria where id_categoria=".$this->parent_id);
            $categoria=$row[0]['nombre_categoria'];
            return "Nueva Subcategoria de ".$categoria;
        }
    }

    public function getAddPrivilege() {
        return "CATEGORIA_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar Categoria.";
    }

    public function getAddedMessage($object) {
        return "La Categoria ha sido adicionada.";
    }

    public function getConfirmDeleteMessage($object) {
        $cat = $object;
        return "Confirma eliminar la siguiente Categoria: " . $cat->getNombre_categoria(). "?";
    }

    public function getDeletePrivilege() {
        return "CATEGORIA_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar Categoriaes.";
    }

    public function getDeletedMessage($element) {
        return "La Categoria ha sido eliminada.";
    }

    public function getEditFormLabel() {
        return "Editar Categoria";
    }

    public function getEditPrivilege() {
        return "CATEGORIA_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar la Categoria";
    }
    public function getViewPrivilege() {
        return "CATEGORIA_VIEW";
    }

    public function getViewPrivilegeMsg() {
        return "Ud. no posee privilegios para ver las Categoriaes";
    }

    public function getFormName() {
        return "categoria_form";
    }

    public function getLogicFacade() {
        return new CategoriaLogicFacade($this->parent_id);
    }

    public function getUpdatedMessage($object) {
        return "La Categoria ha sido editada.";
    }

    public function initForm($form, $categoria) {
        $form->addInputHidden("id_categoria","-1");
        $form->addInputHidden("id_padre",$this->parent_id);
        $form->addInputHidden("nodo_terminal","1");
        $form->addField("nombre_categoria", "Categoria", new HtmlInputText("nombre_categoria", "", 50, 200), "Ingrese el Nombre de la categoria");
        $form->addField("orden", "Posici&oacute;n", new HtmlInputText("orden", "", 5, 5), "Ingrese la posicion en la lista");
    }

    private function loadCategoria($cat) {
        return $cat;
    }

    public function getDeleteMessage($objectKeys) {
        $categoria = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar la siguiente Categoria y sus subcategorias : " . $categoria->getCategoria() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("Categoria");
        $resp->add("Posici&oacute;n");
        $resp->add("Ver Subcategoria");
        return $resp;
    }

    public function getKey($obj) {
        $categoria = $obj;
        return $categoria->getId_categoria();
    }

    public function getRow($o) {
        $categoria = $o;
        $resp = new Vector();
        $resp->add($categoria->getNombre_categoria());
        $resp->add($categoria->getOrden());
        $childLink=new HtmlLink("Subcategoria", "admin.php?option=categorias&padre=".$categoria->getId_categoria());
        $resp->add($childLink->getHtml());
        return $resp;
    }

    public function getToolBar() {
        $toolBar = new StyledToolBar();
        $toolBar->addItem($this->getBaseUrl() . "&action=addform", StyledToolBar::ICON_NEW, $this->getAddNewObjectLabel());

        if($this->parent_id!="-1") {
            /******************************************************************************/
            $row=DaoMgr::getDao()->executeQuery("select * from categoria where id_categoria=".$this->parent_id);
            $padre=$row[0]['id_padre'];
            if($padre=="-1") {
                $toolBar->addItem("?option=categorias",StyledToolBar::ICON_BACK, "Volver");
            }else {
                $toolBar->addItem("?option=categorias&padre=".$padre,StyledToolBar::ICON_BACK, "Volver");
            }
            /************************************Fin*******************************************/
        }
        return $toolBar;
    }
}

class CategoriaLogicFacade implements BusinessLogicFacade {

    var $parent_id;
    public function CategoriaLogicfacade($parent_id) {
        $this->parent_id=$parent_id;
    }

    public function delete($key) {
        try {
            $categoria = $this->find($key);
            if($categoria==NULL) {
                return;
            }else {
                if ($categoria instanceof Categoria) {
                    DaoMgr::getDao()->delete($categoria, $categoria->getTableDescriptor());
                    $hijos=DaoMgr::getDao()->executeQuery("select id_categoria from categoria where id_padre=".$key);
                    foreach ($hijos as $hijo) {
                        $this->delete($hijo["id_categoria"]);
                    }
                    return true;
                }
                return false;
            }
        }
        catch (Exception $exceptio ) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    /*public function delete($key) {
        try {
            $categoria = $this->find($key);
            if ($categoria instanceof Categoria) {
                DaoMgr::getDao()->delete($categoria, $categoria->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }*/

    public function find($key) {
        $object = new Categoria();
        $object->setId_categoria($key);
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
            $pc = new Categoria();
            $pc = DaoMgr::getDao()->getObject($pc, $row);
            $vector->add($pc);
        }
        return $vector;
    }

    private function createQuery($filters, $withLimit) {
        $where = " 1 = 1 AND id_padre = ".$this->parent_id;
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
                $where .= " and  (nombre_categoria like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where. " order by orden";
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
        return "select * from categoria";
    }

    public function getSampleElement() {
        return new Categoria();
    }

    public function insert($object) {
        try {
            if($this->parent_id!="-1"){
                $cat=$this->find($this->parent_id);
                $cat->setNodo_terminal("0");
                $this->update($cat);
            }
            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object) {
        try {
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function validateObjectPreAdd($object) {
        $categoria = $object;
        $vector = new Vector();
        if($categoria->getId_categoria()==NULL || $categoria->getId_categoria()=="") {
            $error=new ObjectValidatorException("nombre_categoria","Falto el nombre");
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

class AdminCategoria extends ComtorAbstractAdministrable {
    var $parent_id;
    public function AdminCategoria ($parent_id) {
        $this->parent_id=$parent_id;

    }
    public function getController() {
        return new CategoriaController($this->parent_id);
    }
}
?>
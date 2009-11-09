<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");

class Producto extends DBElement {

    var $codigo;
    var $id_categoria;
    var $imagen;
    var $precio;


    public function getTableDescriptor() {
        $td = new TableDescriptor("producto");

        $td->addField("codigo","codigo", true);
        $td->addField("id_categoria","id_categoria", false);
        $td->addField("imagen","imagen", true);
        $td->addField("precio","precio", false);

        $td->setFindable("codigo", true);
        $td->setUpdateable("codigo", false);
        $td->setInsertable("codigo", true);

        return $td;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($c) {
        $this->codigo = $c;
    }

    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function setId_categoria($c) {
        $this->id_categoria = $c;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setImagen($c) {
        $this->imagen = $c;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($c) {
        $this->precio = $c;
    }
}

class ProductoController extends AbstractComtorFacadeAdministratorController {

    var $parent_id;
    public function ProductoController($parent_id) {
        $this->parent_id=$parent_id;
    }

    public function getAddFormLabel() {
        return "Adicionar Producto";
    }

    protected function getBaseUrl() {
        return "?option=" . $this->getOption()."&padre=".$this->parent_id;
    }
    public function getAddNewObjectLabel() {
        $row=DaoMgr::getDao()->executeQuery("select * from categoria where id_categoria=".$this->parent_id);
        $categoria=$row[0]['nombre_categoria'];
        return "Nuevo Producto de ".$categoria;
    }

    public function getAddPrivilege() {
        return "PRODUCTO_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar Producto.";
    }

    public function getAddedMessage($object) {
        return "La Producto ha sido adicionada.";
    }

    public function getConfirmDeleteMessage($object) {
        $producto = $object;
        $query="select nombre from detalle_producto where codigo_producto='".$producto->getCodigo()."'";
        $rowsUR=DaoMgr::getDao()->executeQuery($query);
        return "Confirma eliminar la siguiente Producto: " .$rowsUR[0]["nombre"]. "]?";
    }

    public function getDeletePrivilege() {
        return "PRODUCTO_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar Productoes.";
    }

    public function getDeletedMessage($element) {
        return "El Producto ha sido eliminada.";
    }

    public function getEditFormLabel() {
        return "Editar Producto";
    }

    public function getEditPrivilege() {
        return "PRODUCTO_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar la Producto";
    }

    public function getViewPrivilege() {
        return "PRODUCTO_VIEW";
    }

    public function getViewPrivilegeMsg() {
        return "Ud. no posee privilegios para ver las Productoes";
    }

    public function getFormName() {
        return "city_form";
    }

    public function getLogicFacade() {
        return new ProductoLogicFacade($this->parent_id);
    }

    public function getUpdatedMessage($object) {
        return "La Producto ha sido editada.";
    }

    public function initForm($form, $producto) {
        $form->addInputHidden("id_categoria",$this->parent_id);
        $form->addField("codigo", "Codigo Producto", new HtmlInputText("codigo","",50), "Ingrese el Codigo del Producto");
        $this->loadProductos($producto, $form);
        $form->addField("imagen", "Imagen", new HtmlInputFile("imagen","",50), "Seleccione la imagen que desea subir");
        $form->addField("precio", "Precio", new HtmlInputText("precio", "0", 50, 200), "Ingrese el precio del Producto");
    }

    private function loadProductos($producto,&$form) {

        if($producto!=NULL) {
            $query="select nombre, descripcion from detalle_producto where codigo_producto='".$producto->getCodigo()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $nproductos=sizeOf($rowsUR);
        }

        $nombre="";
        $descripcion="";

        if($nproductos!=0) {
            $nombre=$rowsUR[0]['nombre'];
            $descripcion=$rowsUR[0]['descripcion'];
        }

        $form->addField("nombre", "Nombre Producto", new HtmlInputText("nombre",$nombre, 50, 200), "Ingrese el Nombre de la producto");
        $form->addField("descripcion","Descripci&oacute;n", new HtmlTextArea("descripcion",$descripcion,50,10),"Ingrese una descripci&oacute;n del Producto");
    }

    private function loadProducto($cat) {
        return $cat;
    }

    public function getDeleteMessage($objectKeys) {
        $producto = $this->getLogicFacade().find($objectKeys);
        $query="select nombre from detalle_producto where codigo_producto='".$producto->getCodigo()."'";
        $rowsUR=DaoMgr::getDao()->executeQuery($query);
        return "Confirma eliminar la siguiente Producto: " . $rowsUR[0]["nombre"] . "?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("Codigo");
        $resp->add("Producto");
        return $resp;
    }

    public function getKey($obj) {
        $producto = $obj;
        return $producto->getCodigo();
    }

    public function getRow($o) {
        $producto = $o;
        $resp = new Vector();
        $resp->add($producto->getCodigo());
        $query="select nombre from detalle_producto where codigo_producto='".$producto->getCodigo()."'";
        $rowsUR=DaoMgr::getDao()->executeQuery($query);
        $resp->add($rowsUR[0]["nombre"]);
        return $resp;
    }

    public function getToolBar() {
        $toolBar = new StyledToolBar();
        $toolBar->addItem($this->getBaseUrl() . "&action=addform", StyledToolBar::ICON_NEW, $this->getAddNewObjectLabel());

        $toolBar->addItem("?option=menu_productos",StyledToolBar::ICON_BACK, "Volver");

        return $toolBar;
    }
}

class ProductoLogicFacade implements BusinessLogicFacade {

    var $parent_id;
    public function ProductoLogicfacade($parent_id) {
        $this->parent_id=$parent_id;
    }

    public function delete($key) {
        try {
            $producto = $this->find($key);
            if ($producto instanceof Producto) {
                DaoMgr::getDao()->delete($producto, $producto->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function find($key) {
        $object = new Producto();
        $object->setCodigo($key);
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
            $pc = new Producto();
            $pc = DaoMgr::getDao()->getObject($pc, $row);
            $vector->add($pc);
        }
        return $vector;
    }

    private function createQuery($filters, $withLimit) {
        $where = " 1 = 1 and id_categoria=".$this->parent_id;
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
                $where .= " and  (codigo like '%" . $token . "%')  ";
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
        return "select * from producto";
    }

    public function getSampleElement() {
        return new Producto();
    }

    public function insert($object) {
        try {
            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
            $detalle=new Detalle_producto();
            //$detalle->setId_detalle_producto(-1);
            $detalle->setCodigo_producto($object->getCodigo());
            $detalle->setNombre($_REQUEST["nombre"]);
            $detalle->setDescripcion($_REQUEST["descripcion"]);
            DaoMgr::getDao()->insert($detalle, $detalle->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object) {
        try {
            if($object->getImagen()==""){
                $query="select imagen from producto where codigo='".$object->getCodigo()."'";
                $rowsUR=DaoMgr::getDao()->executeQuery($query);
                $object->setImagen($rowsUR[0]["imagen"]);
            }
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());
            $query="select id_detalle_producto from detalle_producto where codigo_producto='".$object->getCodigo()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $detalle=new Detalle_producto();
            $detalle->setId_detalle_producto($rowsUR[0]["id_detalle_producto"]);
            $detalle->setCodigo_producto($object->getCodigo());
            $detalle->setNombre($_REQUEST["nombre"]);
            $detalle->setDescripcion($_REQUEST["descripcion"]);
            DaoMgr::getDao()->update($detalle, $detalle->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function validateObjectPreAdd($object) {
        $producto = $object;
        $vector = new Vector();
        /*if($producto->getNombre()==NULL || $producto->getNombre()=="") {
            $error=new ObjectValidatorException("producto","Falto el nombre");
            $vector->addElement($error);
        }*/


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

class AdminProducto extends ComtorAbstractAdministrable {
    var $parent_id;
    public function AdminProducto($parent_id) {
        $this->parent_id=$parent_id;

    }
    public function getController() {
        return new ProductoController($this->parent_id);
    }
}

class Detalle_producto extends DBElement {

    var $id_detalle_producto;
    var $codigo_producto;
    var $nombre;
    var $descripcion;


    public function getTableDescriptor() {
        $td = new TableDescriptor("detalle_producto");

        $td->addField("id_detalle_producto","id_detalle_producto", false);
        $td->addField("codigo_producto","codigo_producto", true);
        $td->addField("nombre","nombre", true);
        $td->addField("descripcion","descripcion", true);

        $td->setFindable("id_detalle_producto", true);
        $td->setUpdateable("id_detalle_producto", false);
        $td->setInsertable("id_detalle_producto", false);

        return $td;
    }

    public function getId_detalle_producto() {
        return $this->id_detalle_producto;
    }

    public function setId_detalle_producto($c) {
        $this->id_detalle_producto = $c;
    }

    public function getCodigo_producto() {
        return $this->codigo_producto;
    }

    public function setCodigo_producto($c) {
        $this->codigo_producto = $c;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($c) {
        $this->nombre = $c;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($c) {
        $this->descripcion = $c;
    }
}
?>
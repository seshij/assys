<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");
include_once("classes/logs_classes.inc.php");


class Factura extends DBElement  {

    var $numero;
    var $cliente;
    var $razon;
    var $valorbase;
    var $valorservicio;
    var $valorproducto;
    var $reteservicio;
    var $reteproducto;
    var $reteica;
    var $reteiva;
    var $iva;
    var $total;

    public function getTableDescriptor() {
        $td = new TableDescriptor("factura");

        $td->addField("numero","numero",false);
        $td->addField("cliente","cliente",true);
        $td->addField("razon","razon",true);
        $td->addField("valorbase","valorbase",false);
        $td->addField("valorservicio","valorservicio",false);
        $td->addField("valorproducto","valorproducto",false);
        $td->addField("reteservicio","reteservicio",false);
        $td->addField("reteproducto","reteproducto",false);
        $td->addField("reteica","reteica",false);
        $td->addField("reteiva","reteiva",false);
        $td->addField("iva","iva",false);
        $td->addField("total","total",false);


        $td->setFindable("numero", true);
        $td->setUpdateable("numero", false);
        $td->setInsertable("numero", true);


        return $td;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($n) {
        $this->numero = $n;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function setCliente($c) {
        $this->cliente = $c;
    }

    public function getRazon() {
        return $this->razon;
    }

    public function setRazon($c) {
        $this->razon = $c;
    }

    public function getValorbase() {
        return $this->valorbase;
        
    }

    public function setValorbase($v) {
        $this->valorbase = $v;
    }

    public function getValorproducto() {
        return $this->valorproducto;
    }

    public function setValorproducto($c) {
        $this->valorproducto = $c;
    }
    public function getValorservicio() {
        return $this->valorservicio;
    }

    public function setValorservicio($c) {
        $this->valorservicio = $c;
    }

    public function getIva() {
        return $this->iva;
    }

    public function setIva($i) {
        $this->iva = $i;
    }

    public function getReteica() {
        return $this->reteica;
    }

    public function setReteica($c) {
        $this->reteica = $c;
    }

    public function getReteiva() {
        return $this->reteiva;
    }

    public function setReteiva($c) {
        $this->reteiva = $c;
    }
    public function getReteproducto() {
        return $this->reteproducto;
    }

    public function setReteproducto($c) {
        $this->reteproducto = $c;
    }
    public function getReteservicio() {
        return $this->reteservicio;
    }

    public function setReteservicio($c) {
        $this->reteservicio = $c;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($c) {
        $this->total = $c;
    }
}

class FacturaController extends AbstractComtorFacadeAdministratorController {

    public function getAddFormLabel() {
        return "Adicionar Factura";
    }

    public function getAddNewObjectLabel() {
        return "Nuevo Factura";
    }

    public function getAddPrivilege() {
        return "FACTURA_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar factura.";
    }

    public function getAddedMessage($object) {
        return "El factura ha sido adicionado.";
    }

    public function getConfirmDeleteMessage($object) {
        $ob = $object;
        return "Confirma eliminar el siguiente Factura: " . $ob->getNumero() . " [" . $ob->getRazon(). "]?";
    }

    public function getDeletePrivilege() {
        return "FACTURA_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar factura.";
    }

    public function getDeletedMessage($element) {
        return "El Factura ha sido eliminado.";
    }

    public function getEditFormLabel() {
        return "Editar Factura";
    }

    public function getEditPrivilege() {
        return "FACTURA_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar el Factura";
    }

	public function getViewPrivilege() {
		return "FACTURA_VIEW";
	}

	public function getViewPrivilegeMsg() {
		return "Ud. no posee privilegios para ver las Facturas";
	}
    public function getFormName() {
        return "factura_form";
    }

    public function getLogicFacade() {
        return new FacturaLogicFacade();
    }

    public function getUpdatedMessage($object) {
        return "El Factura ha sido editado.";
    }

    public function initForm($form, $empresa) {
        $form->getForm()->addAttribute("id","facturaform");
        $script=new HtmlScript("Javascript");
        $script->setScript("
            $(document).ready(goFactura);
        ");
        echo $script->getHtml();


        $form->addField("numero", "Numero", new HtmlInputText("numero", "", 35, 100), "Ingrese el N&uacute;mero de factura");
        $form->addField("cliente", "Cliente", new HtmlSelectFromQuery("cliente", "nit", "razon", "cliente","", true), "Seleccione el cliente");
        $form->addField("valorproducto", "Valor de productos", new HtmlInputText("valorproducto", "", 20, 100), "Ingrese el valor de los productos");
        $form->addField("reteproducto", "Retenci&oacute;n a productos %", new HtmlInputText("reteproducto", "3.5", 3, 100), "Ingrese la Retenci&oacute;n para estos productos");
        $form->addField("valorservicio", "Valor de servicios", new HtmlInputText("valorservicio", "", 20, 100), "Ingrese el valor de los servicios");
        $form->addField("reteservicio", "Retenci&oacute;n a los servicios %", new HtmlInputText("reteservicio", "4", 3, 100), "Ingrese la retenci&oacute;n a lo servicios");
        $tre1=new HtmlInputText("valorbase", "", 20, 100);
        $tre1->addAttribute("style","background-color: #dddddd; font-size: 10pt");
        $tre1->addAttribute("readonly", "readonly");
        $form->addField("valorbase", "Valor Base", $tre1, "Este es el valor base");
        $form->addField("reteica", "ReteICA%", new HtmlInputText("reteica", "0.00699", 6, 100), "ReteICA");
        $form->addField("iva", "IVA%", new HtmlInputText("iva", "16", 5, 100), "Ingrese el IVA");
        $form->addField("reteiva", "ReteIVA %", new HtmlInputText("reteiva", "8", 3, 100), "Ingrese el ReteIVA");
        $tre=new HtmlInputText("total", "", 20, 100);
        $tre->addAttribute("style", "background-color: #dddddd; font-size: 10pt");
        $tre->addAttribute("readonly", "readonly");
        $form->addField("total", "Total",$tre , "Este es el total");

    }


    private function loadFactura($ob){
        return $ob;
    }

    public function getDeleteMessage($objectKeys){
        $ob = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar la siguiente Factura: " . $ob->getNumero() . " [" . $ob->getRazon() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("Numero");
        $resp->add("Cliente");

        return $resp;
    }

    public function getKey($obj) {
        $ob = $obj;
        return $ob->getNumero();
    }

    public function getRow($o) {
        $ob = $o;
        $resp = new Vector();
        $resp->add($ob->getNumero());
        $resp->add($ob->getRazon());
        return $resp;
    }

}

class FacturaLogicFacade implements BusinessLogicFacade {

    public function delete($key){
        try {
            $ob = $this->find($key);
            if ($ob instanceof Factura) {
                DaoMgr::getDao()->delete($ob, $ob->getTableDescriptor());
                $logs = new Logs();
                $logs->setCategory("factura");
                $date_time = date("Y-m-d H:i:s");
                $logs->setDate_log($date_time);

                if(isset($_SESSION["user"])){
                    $user=$_SESSION["user"];
                    $logs->setUser($user->getName());
                }

                $logs->setAction("Elimino una factura con numero: ".$ob->getNumero());
                DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function find($key){
        $object = new Factura();
        $object->setNumero($key);
        try {
            return DaoMgr::getDao()->load($object, $object->getTableDescriptor());
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function getObjectsList($filters){
        return $this->createObjectList($filters, false);
    }

    private function createObjectList($filters, $withLimit) {

        $rows = DaoMgr::getDao()-> executeQuery($this->createQuery($filters, $withLimit));
        $vector = new Vector();

        foreach ($rows as $row){
            $pc = new Factura();
            $pc = DaoMgr::getDao()->getObject($pc, $row);
            $vector->add($pc);
        }
        return $vector;
    }

    private function createQuery($filters, $withLimit) {
        $where = " 1 = 1 ";
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
            foreach ($tokens as $token){
                $token = str_replace(" ", "'", $token);
                $where .= " and  (numero like '%" . $token . "%'  or razon like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where;
        if ($withLimit) {
            $query .= " limit 100 ";
        }
        return $query;
    }

    public function getObjectsListForFinder($filters){
        return $this->getObjectsList($filters);
    }

    public function getObjectsListWithParameter($filters){
        return $this->getObjectsListForFinder($filters);
    }

    public function getQueryString() {
        return "select * from factura";
    }

    public function getSampleElement() {
        return new Factura();
    }

    public function insert($object) {

        try {
            $object=$this->normalizeFactura($object);
            
            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());

            $logs = new Logs();
            $logs->setCategory("factura");
            $date_time = date("Y-m-d H:i:s");
            $logs->setDate_log($date_time);

            if(isset($_SESSION["user"])){
                $user=$_SESSION["user"];
                $logs->setUser($user->getName());
            }
            

            $logs->setAction("Inserto un nueva factura con numero: ".$object->getNumero());//." y id: ".$object->getId());


        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object){
        try {
            $object=$this->normalizeFactura($object);
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());

            $logs = new Logs();
            $logs->setCategory("factura");
            $date_time = date("Y-m-d H:i:s");
            $logs->setDate_log($date_time);

            if(isset($_SESSION["user"])){
                $user=$_SESSION["user"];
                $logs->setUser($user->getName());
            }

            $logs->setAction("Actualizo una factura con numero: ".$object->getNumero());//." y id: ".$object->getId());
            DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());

        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    private function normalizeFactura($object){
            $nit=$object->getCliente();
            
            $query="select razon from cliente where nit='".$nit."'";
			$rowsUR=DaoMgr::getDao()->executeQuery($query);
            $object->setRazon($rowsUR[0]['razon']);

            if($object->getValorproducto()==""){
                $object->setValorproducto(0);
            }
            if($object->getReteproducto()==""){
                $object->setReteproducto(0);
            }
            if($object->getValorservicio()==""){
                $object->setValorservicio(0);
            }
            if($object->getReteservicio()==""){
                $object->setReterservicio(0);
            }

            if($object->getReteica()==""){
                $object->setReteica(0);
            }
            if($object->getIva()==""){
                $object->setIva(0);
            }
            if($object->getReteiva()==""){
                $object->setReteiva(0);
            }
        return $object;
    }
    public function validateObjectPreAdd($object) {

        $ob = $object;
        $vector = new Vector();

        if($ob->getNumero()== NULL || $ob->getNumero()==""){
            $error=new ObjectValidatorException("numero","Falto el NUMERO");
            $vector->addElement($error);
        }else{
            $numero=$ob->getNumero();
            $rows=DaoMgr::getDao()->executeQuery("select numero from factura where numero like '%".$numero."%'");
            if(sizeOf($rows)>0){
                $error=new ObjectValidatorException("numero","Ese NUMERO ya existe en el sistema, verifique los datos");
                $vector->addElement($error);
            }
        }
        if($ob->getCliente()=="-1"){
            $error=new ObjectValidatorException("numero","Por favor seleccione una empresa");
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

class AdminFactura extends ComtorAbstractAdministrable {

    public function getController() {
        return new FacturaController();
    }

}

?>
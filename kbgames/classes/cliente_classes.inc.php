<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");
include_once("classes/logs_classes.inc.php");


class Cliente extends DBElement  {

    var $nit;
    var $razon;
    var $direccion;
    var $telefono;
    var $fax;
    var $email;

    public function getTableDescriptor() {
        $td = new TableDescriptor("cliente");

        $td->addField("nit","nit", true);
        $td->addField("razon","razon", true);
        $td->addField("direccion","direccion", true);
        $td->addField("telefono","telefono",true);
        $td->addField("email","email",true);
        $td->addField("fax","fax",true);

        $td->setFindable("nit", true);
        $td->setUpdateable("nit", false);
        $td->setInsertable("nit", true);


        return $td;
    }

    public function getNit() {
        return $this->nit;
    }

    public function setNit($nit) {
        $this->nit = $nit;
    }

    public function getRazon() {
        return $this->razon;
    }

    public function setRazon($razon) {
        $this->razon = $razon;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($Direccion) {
        $this->direccion = $Direccion;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email= $email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($tel) {
        $this->telefono = $tel;
    }
    public function getFax() {
        return $this->fax;
    }

    public function setFax($fax) {
        $this->fax = $fax;
    }
}

class ClienteController extends AbstractComtorFacadeAdministratorController {

    public function getAddFormLabel() {
        return "Adicionar Cliente";
    }

    public function getAddNewObjectLabel() {
        return "Nuevo Cliente";
    }

    public function getAddPrivilege() {
        return "CLIENTE_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar cliente.";
    }

    public function getAddedMessage($object) {
        return "El cliente ha sido adicionado.";
    }

    public function getConfirmDeleteMessage($object) {
        $ob = $object;
        return "Confirma eliminar el siguiente Cliente: " . $ob->getNit() . " [" . $ob->getRazon(). "]?";
    }

    public function getDeletePrivilege() {
        return "CLIENTE_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar cliente.";
    }

    public function getDeletedMessage($element) {
        return "El Cliente ha sido eliminado.";
    }

    public function getEditFormLabel() {
        return "Editar Cliente";
    }

    public function getEditPrivilege() {
        return "CLIENTE_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar el Cliente";
    }
	public function getViewPrivilege() {
		return "CLIENTE_VIEW";
	}

	public function getViewPrivilegeMsg() {
		return "Ud. no posee privilegios para ver los Clientes";
	}
    public function getFormName() {
        return "cliente_form";
    }

    public function getLogicFacade() {
        return new ClienteLogicFacade();
    }

    public function getUpdatedMessage($object) {
        return "El Cliente ha sido editado.";
    }

    public function initForm($form, $empresa) {

        /*$script=new HtmlScript("Javascript");
        $script->setScript("
            $(document).ready(goCliente);
        ");
        echo $script->getHtml();*/
        $form->addField("nit", "NIT", new HtmlInputText("nit", "", 35, 100), "Ingrese el NIT");
        $form->addField("razon", "Raz&oacute;n Social", new HtmlInputText("razon", "", 35, 100), "Ingrese el nombre o Raz&oacute;n Social");
        $form->addField("direccion", "Direcci&oacute;n", new HtmlInputText("direccion", "", 35, 100), "Ingrese la direcci&oacute;n");
        $form->addField("telefono", "Teléfono", new HtmlInputText("telefono", "", 35, 100), "Ingrese el tel&oacute;fono");
        $form->addField("fax", "Fax", new HtmlInputText("fax", "", 35, 100), "Ingrese el fax");
        $form->addField("email", "E-Mail", new HtmlInputText("email", "", 35, 100), "Ingrese el email");
        $form->addField("contactos","Contactos", $this->loadContactDiv($empresa),"Seleccione los contactos");

    }

    private function loadContactDiv($empresa){
        $contactDiv=new HtmlDiv("div_contactos");
        $ncontact=0;
        if($empresa!=NULL){
            $query="select nombre, telefono, celular, email from contacto where nit_empresa='".$empresa->getNit()."'";
            $rowsUR=DaoMgr::getDao()->executeQuery($query);
            $ncontact=sizeOf($rowsUR);
        }


        for($i=0;$i<3;$i++){
            $n_c="";
            $tel_c="";
            $cel_c="";
            $mail_c="";
            if($empresa!=NULL){
                if($ncontact>$i){
                    $n_c=$rowsUR[$i]['nombre'];
                    $tel_c=$rowsUR[$i]['telefono'];
                    $cel_c=$rowsUR[$i]['celular'];
                    $mail_c=$rowsUR[$i]['email'];
                }
            }
            $contactTab=new HtmlTable();
            $contactTab->setWidth("80%");
            $contactTab->addAttribute("border","1");
            $contactTab->setClass("ui-widget-content");
            $th=new HtmlTh("Informaci&oacute;n de Contacto");
            $th->addAttribute("colspan","2");
            $th->setClass("ui-widget-header ui-corner-all");
            $contactTab->getCurrentRow()->addElement($th);
            $contactTab->nextRow();
            $contactTab->addCell("Nombre");
            $intro=new HtmlInputText("n_c_".$i, $n_c, 35, 100);
            $contactTab->addCell($intro);
            $contactTab->nextRow();
            $contactTab->addCell("Telef&oacute;no");
            $intro=new HtmlInputText("tel_c_".$i, $tel_c, 35, 100);
            $contactTab->addCell($intro);
            $contactTab->nextRow();
            $contactTab->addCell("Celular");
            $intro=new HtmlInputText("cel_c_".$i, $cel_c, 35, 100);
            $contactTab->addCell($intro);
            $contactTab->nextRow();
            $contactTab->addCell("Email");
            $intro=new HtmlInputText("mail_c_".$i, $mail_c, 35, 100);
            $contactTab->addCell($intro);
            $contactTab->nextRow();
            $elTd=$contactTab->addCell("<a onClick=confirma(".$i.")>Borrar</a>");
            $elTd->addAttribute("colspan","2");
            $contactTab->nextRow();
            $contactDiv->addElement($contactTab);
        }
        //$contactDiv->addElement($adLink);
        return $contactDiv;
    }
    private function loadCliente($ob){
        return $ob;
    }

    public function getDeleteMessage($objectKeys){
        $ob = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar la siguiente Cliente: " . $ob->getNit() . " [" . $ob->getRazon() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("NIT");
        $resp->add("Raz&oacute;n Social");
        return $resp;
    }

    public function getKey($obj) {
        $ob = $obj;
        return $ob->getNit();
    }

    public function getRow($o) {
        $ob = $o;
        $resp = new Vector();
        $resp->add($ob->getNit());
        $resp->add($ob->getRazon());
        return $resp;
    }

}

class ClienteLogicFacade implements BusinessLogicFacade {

    public function delete($key){
        try {
            $ob = $this->find($key);
            if ($ob instanceof Cliente) {
                DaoMgr::getDao()->delete($ob, $ob->getTableDescriptor());
                $logs = new Logs();
                $logs->setCategory("cliente");
                $date_time = date("Y-m-d H:i:s");
                $logs->setDate_log($date_time);

                if(isset($_SESSION["user"])){
                    $user=$_SESSION["user"];
                    $logs->setUser($user->getName());
                }

                $logs->setAction("Elimino un cliente con nombre: ".$ob->getRazon());//." y id: ".$back_end_user->getId());
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
        $object = new Cliente();
        $object->setNit($key);
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
            $pc = new Cliente();
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
                $where .= " and  (nit like '%" . $token . "%'  or razon like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where . " order by razon";
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
        return "select * from cliente";
    }

    public function getSampleElement() {
        return new Cliente();
    }

    public function insert($object) {

        try {

            DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
            $this->addContacts($object->getNit(),false);
            $logs = new Logs();
            $logs->setCategory("cliente");
            $date_time = date("Y-m-d H:i:s");
            $logs->setDate_log($date_time);

            if(isset($_SESSION["user"])){
                $user=$_SESSION["user"];
                $logs->setUser($user->getName());
            }

            $logs->setAction("Inserto un nuevo cliente con nombre: ".$object->getRazon());
            DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());

        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function update($object){
        try {
            DaoMgr::getDao()->update($object, $object->getTableDescriptor());
            $this->addContacts($object->getNit(),true);

            $logs = new Logs();
            $logs->setCategory("cliente");
            $date_time = date("Y-m-d H:i:s");
            $logs->setDate_log($date_time);

            if(isset($_SESSION["user"])){
                $user=$_SESSION["user"];
                $logs->setUser($user->getName());
            }

            $logs->setAction("Actualizo un cliente con nombre: ".$object->getRazon());//." y id: ".$object->getId());
            DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());

        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }
    private function addContacts($nit,$isEdit){
        if($isEdit){
            $query="delete from contacto where nit_empresa='".$nit."'";
            DaoMgr::getDao()->execute($query);
        }
        $i=0;
        while($i<3){
            if(isset($_REQUEST["n_c_".$i]) && $_REQUEST["n_c_".$i]!=""){
                $contact=new Contacto();
                $contact->setId(-1);
                $contact->setNombre($_REQUEST["n_c_".$i]);
                $contact->setTelefono($_REQUEST["tel_c_".$i]);
                $contact->setCelular($_REQUEST["cel_c_".$i]);
                $contact->setEmail($_REQUEST["mail_c_".$i]);
                $contact->setNit_empresa($nit);
                DaoMgr::getDao()->insert($contact, $contact->getTableDescriptor());
            }
            $i++;
        }
    }

    public function validateObjectPreAdd($object) {

        $ob = $object;
        $vector = new Vector();

        if($ob->getNit()== NULL || $ob->getNit()==""){
            $error=new ObjectValidatorException("nit","Falto el NIT");
            $vector->addElement($error);
        }else{
            $nit=$ob->getNit();
            $rows=DaoMgr::getDao()->executeQuery("select razon from cliente where nit like '%".$nit."%'");
            if(sizeOf($rows)>0){
                $error=new ObjectValidatorException("nit","Ese NIT ya existe en el sistema, verifique los datos");
                $vector->addElement($error);
            }
        }


        if($ob->getRazon()== NULL || $ob->getRazon()==""){
            $error=new ObjectValidatorException("razon","Falto el Raz&oacute;n");
            $vector->addElement($error);
        }
        return $vector;
    }

    public function validateObjectPreDelete($object) {
        $vector = new Vector();
        return $vector;
    }

    public function validateObjectPreEdit($ob) {
        $vector = new Vector();
       if($ob->getRazon()== NULL || $ob->getRazon()==""){
            $error=new ObjectValidatorException("razon","Falto el Raz&oacute;n");
            $vector->addElement($error);
        }
        return $vector;
    }
}

class AdminCliente extends ComtorAbstractAdministrable {

    public function getController() {
        return new ClienteController();
    }

}

class Contacto extends DBElement  {

    var $id;
    var $nombre;
    var $telefono;
    var $celular;
    var $nit_empresa;
    var $email;

    public function getTableDescriptor() {
        $td = new TableDescriptor("contacto");

        $td->addField("id","id", true);
        $td->addField("nombre","nombre", true);
        $td->addField("celular","celular", true);
        $td->addField("telefono","telefono",true);
        $td->addField("email","email",true);
        $td->addField("nit_empresa","nit_empresa",true);

        $td->setFindable("id", true);
        $td->setUpdateable("id", false);
        $td->setInsertable("id", false);


        return $td;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getCelular() {
        return $this->Celular;
    }

    public function setCelular($cel) {
        $this->celular = $cel;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($tel) {
        $this->telefono = $tel;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getNit_empresa() {
        return $this->nit_empresa;
    }

    public function setNit_empresa($nit_empresa) {
        $this->nit_empresa = $nit_empresa;
    }
}
?>
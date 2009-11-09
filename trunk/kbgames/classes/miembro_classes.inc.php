<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");
include_once("classes/logs_classes.inc.php");


class Miembro extends DBElement {

    var $id_miembro;
    var $nombres;
    var $apellidos;
    var $tipo_id;
    var $email;
    var $direccion;
    var $telefono;
    var $celular;
    var $ciudad;
    var $fecha_nacimiento;
    var $sexo;

    public function getTableDescriptor() {
        $td = new TableDescriptor("miembro");

        $td->addField("id_miembro","id_miembro", true);
        $td->addField("nombres","nombres", true);
        $td->addField("apellidos","apellidos", true);
        $td->addField("tipo_id","tipo_id", true);
        $td->addField("email","email", true);
        $td->addField("direccion","direccion", true);
        $td->addField("telefono","telefono", true);
        $td->addField("celular","celular", true);
        $td->addField("ciudad","ciudad", true);
        $td->addField("fecha_nacimiento","fecha_nacimiento", true);
        $td->addField("sexo","sexo", true);

        $td->setFindable("id_miembro", true);
        $td->setUpdateable("id_miembro", false);
        $td->setInsertable("id_miembro", true);

        return $td;
    }

    public function getId_miembro() {
        return $this->id_miembro;
    }

    public function setId_miembro($c) {
        $this->id_miembro = $c;
    }

    public function getNombres() {
        return $this->nombres;
    }

    public function setNombres($c) {
        $this->nombres = $c;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function setApellidos($c) {
        $this->apellidos = $c;
    }

    public function getTipo_id() {
        return $this->tipo_id;
    }

    public function setTipo_id($c) {
        $this->tipo_id = $c;
    }
    public function getEmail() {
        return $this->email;
    }

    public function setEmail($c) {
        $this->email = $c;
    }
    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($c) {
        $this->direccion = $c;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($c) {
        $this->telefono = $c;
    }
    public function getCelular() {
        return $this->celular;
    }

    public function setCelular($c) {
        $this->celular = $c;
    }
    public function getCiudad() {
        return $this->ciudad;
    }

    public function setCiudad($c) {
        $this->ciudad = $c;
    }
    public function getFecha_nacimiento() {
        return $this->fecha_nacimiento;
    }

    public function setFecha_nacimiento($c) {
        $this->fecha_nacimiento = $c;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($c) {
        $this->sexo = $c;
    }
}

class MiembroController extends AbstractComtorFacadeAdministratorController {
    public function MiembroController() {
    }
    public function getAddFormLabel() {
        return "Adicionar  Miembro";
    }

    protected function getBaseUrl() {
        return "?option=" . $this->getOption();
    }
    public function getAddNewObjectLabel() {
        return "Nuevo Miembro";
    }

    public function getAddPrivilege() {
        return "INMUEBLE_ADD";
    }

    public function getAddPrivilegeMsg() {
        return "Ud. no posee privilegios para adicionar Miembro.";
    }

    public function getAddedMessage($object) {
        return "El Miembro ha sido adicionado.";
    }

    public function getConfirmDeleteMessage($object) {
        $cat = $object;
        return "Confirma eliminar el siguiente Miembro: " . $cat->getId_miembro(). "]?";
    }

    public function getDeletePrivilege() {
        return "INMUEBLE_DELETE";
    }

    public function getDeletePrivilegeMsg() {
        return "Ud. no posee privilegios para eliminar Miembro.";
    }

    public function getDeletedMessage($element) {
        return "El Miembro ha sido eliminado.";
    }

    public function getEditFormLabel() {
        return "Editar Miembro";
    }

    public function getEditPrivilege() {
        return "INMUEBLE_EDIT";
    }

    public function getEditPrivilegeMsg() {
        return "Ud. no posee privilegios para editar Miembro";
    }
    public function getViewPrivilege() {
        return "INMUEBLE_VIEW";
    }

    public function getViewPrivilegeMsg() {
        return "Ud. no posee privilegios para ver Miembro";
    }

    public function getFormName() {
        return "miembro_form";
    }

    public function getLogicFacade() {
        return new MiembroLogicFacade();
    }

    public function getUpdatedMessage($object) {
        return "El Miembro ha sido editado.";
    }

    public function initForm($form, $miembro) {

        $form->getForm()->addAttribute("id",$this->getFormName());
        $form->getForm()->addAttribute("class","frmjuego");

        $form->addMessage("Todos los campos son requeridos");

        $selectc=new HtmlSelect("tipo_id");
        $selectc->addOption(" ","Tipo Documento");
        $selectc->addOption("C.C.","Cedula Ciudadania");
        $selectc->addOption("T.I.","Tarjeta Identidad");
        $form->addField("tipo_id","Tipo de Documento", $selectc,"Seleccione el tipo de documento");

        $cod=new HtmlInputText("id_miembro", "", 50, 200);
        $form->addField("id_miembro", "Numero Documento", $cod, "Ingrese el Numero de Documento del  de Miembro");
        if($_REQUEST["action"]=="editform") {
            $cod->addAttribute("disabled",true);
        }

        $form->addField("nombres", "Nombres", new HtmlInputText("nombres", "", 50, 200), "Ingrese los Nombres del Miembro");
        $form->addField("apellidos", "Apellidos", new HtmlInputText("apellidos","",50,200), "Ingrese los Apellidos del Miembro");
        $form->addField("fecha_nacimiento", "Fecha de Nacimiento", new HtmlInputText("fecha_nacimiento", "", 50, 200), "Ingrese el nombre de la empresa desarrolladora del miembro");
        $form->addField("email", "Email", new HtmlInputText("email", "", 50, 200), "Ingrese el nombre de la empresa desarrolladora del miembro");
        $form->addField("direccion", "Direcci&oacute;n", new HtmlInputText("direccion", "", 50, 200), "Ingrese la fecha en que salio o saldra el miembro");
        $form->addField("telefono", "Tel&eacute;fono", new HtmlInputText("telefono", "", 50, 200), "Ingrese el numero de ejemplares que existe en inventario");
        $form->addField("celular", "Celular", new HtmlInputText("celular", "", 50, 200), "Ingrese el precio del miembro");
        $form->addField("ciudad", "Ciudad", new HtmlInputText("ciudad", "", 50, 200), "Ingrese la ciudad de origen");

        $cont=new HtmlContainer();
        $intro1 =new HtmlRadio("sexo","masc","Masculino");
        $cont->addElement($intro1);
        $intro2=new HtmlRadio("sexo","fem","Femenino");
        $cont->addElement($intro2);
        $label=new HtmlTag("label");
        $label->addAttribute("for","sexo");
        $label->addAttribute("class","error");
        $label->addTextElement(" * campo requerido");
        $cont->addElement($label);
        $this->setRadioButtonState($intro1,$intro2);
        $form->addField("sexo","Sexo",$cont,"Seleccione el sexo");

    }

    private function setRadioButtonState(&$rb1,&$rb2) {
        if($_REQUEST['key']!=NULL) {
            $dato=DaoMgr::getDao()->executeQuery("select sexo from miembro where id_miembro='".$_REQUEST['key']."'");
            if($dato[0]['sexo']=='masc') {
                $rb1->addAttribute("checked","true");
            }else {
                $rb2->addAttribute("checked","true");
            }
        }
    }

    private function loadmiembro($cat) {
        return $cat;
    }

    public function getDeleteMessage($objectKeys) {
        $miembro = $this->getLogicFacade().find($objectKeys);
        return "Confirma eliminar el siguiente Miembro : " . $miembro->getId_miembro() . "]?";
    }

    public function getHeaders() {
        $resp = new Vector();
        $resp->add("Identificaci&oacute;n");
        $resp->add("Nombres");
        $resp->add("Apellidos");
        return $resp;
    }

    public function getKey($obj) {
        $miembro = $obj;
        return $miembro->getId_miembro();
    }

    public function getRow($o) {
        $miembro = $o;
        $resp = new Vector();
        $resp->add($miembro->getId_miembro());
        $resp->add($miembro->getNombres());
        $resp->add($miembro->getApellidos());
        return $resp;
    }
}

class MiembroLogicFacade implements BusinessLogicFacade {

    public function delete($key) {
        try {
            $ob = $this->find($key);
            if ($ob instanceof Miembro) {
                DaoMgr::getDao()->delete($ob, $ob->getTableDescriptor());
                return true;
            }
            return false;
        }
        catch (Exception $exception) {
            throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
        }
    }

    public function find($key) {
        $object = new Miembro();
        $object->setId_miembro($key);
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
            $pc = new Miembro();
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
            foreach ($tokens as $token) {
                $token = str_replace(" ", "'", $token);
                $where .= " and  (id_miembro like '%" . $token . "%'  or nombres like '%" . $token . "%')  ";
            }
        }
        $query = $this->getQueryString() . " where " . $where . " order by apellidos";
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
        return "select * from miembro";
    }

    public function getSampleElement() {
        return new Miembro();
    }

    public function insert($object) {

        try {
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
        $ob = $object;
        $vector = new Vector();
        return $vector;
    }

    public function validateObjectPreDelete($object) {
        $vector = new Vector();
        return $vector;
    }

    public function validateObjectPreEdit($ob) {
        $vector = new Vector();
        return $vector;
    }
}

class AdminMiembro extends ComtorAbstractAdministrable {
    public function getController() {
        return new MiembroController();
    }

}
?>
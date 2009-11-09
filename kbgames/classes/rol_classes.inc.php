<?php


include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");



class Rol extends DBElement  {

	var $id;
	var $name;


	public function getTableDescriptor() {
		$td = new TableDescriptor("rol");

		$td->addField("id","id", false);
		$td->addField("name","name", true);

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

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

}

class RolController extends AbstractComtorFacadeAdministratorController {

	public function getAddFormLabel() {
		return "Adicionar Rol";
	}

	public function getAddNewObjectLabel() {
		return "Nuevo Rol";
	}

	public function getAddPrivilege() {
		return "ROL_ADD";
	}

	public function getAddPrivilegeMsg() {
		return "Ud. no posee privilegios para adicionar Rol.";
	}

	public function getAddedMessage($object) {
		return "El Rol ha sido adicionado.";
	}

	public function getConfirmDeleteMessage($object) {
		$rol = $object;
		return "Confirma eliminar el siguiente Rol: " . $rol->getId() . " [" . $rol->getName(). "]?";
	}

	public function getDeletePrivilege() {
		return "ROL_DELETE";
	}

	public function getDeletePrivilegeMsg() {
		return "Ud. no posee privilegios para eliminar Rols.";
	}

	public function getDeletedMessage($element) {
		return "El Rol ha sido eliminado.";
	}

	public function getEditFormLabel() {
		return "Editar Rol";
	}

	public function getEditPrivilege() {
		return "ROL_EDIT";
	}

	public function getEditPrivilegeMsg() {
		return "Ud. no posee privilegios para editar el Rol";
	}

	public function getViewPrivilege() {
		return "ROL_VIEW";
	}

	public function getViewPrivilegeMsg() {
		return "Ud. no posee privilegios para ver los Roles";
	}

	public function getFormName() {
		return "rol_form";
	}

	public function getLogicFacade() {
		return new RolLogicFacade();
	}

	public function getUpdatedMessage($object) {
		return "El Rol ha sido editado.";
	}

	public function initForm($form, $rol) {


		$form->addInputHidden("id","-1");

		$form->addField("name", "Nombre", new HtmlInputText("name", "", 50, 200), "Ingrese el Nombre");
		$rows=DaoMgr::getDao()->executeQuery("select * from can order by id, category");
		$form->addInputHidden("qcan",sizeOf($rows));
		$canTable=$this->loadCanTable($rol, $rows);
		$form->addField("params", "Permisos:", $canTable, "Seleccione los permisos");


	}

	private function loadCanTable($rol, $rows){
		$canTable= new HtmlTable();
		$canTable->setClass("comtorbox");
		$actuaCat="";
		$ini=true;

		if($rol!=NULL){
			$rowsRC=DaoMgr::getDao()->executeQuery("select * from rol_can where rol_id=".$rol->getId());
		}

		$i=0;
		foreach($rows as $row){
			$elCan=new Can();
			$elCan=DaoMgr::getDao()->getObject($elCan, $row);

			$canId=$elCan->getId();
			$canName=$elCan->getName();
			$canCat=$elCan->getCategory();

			if($canCat!=$actuaCat){
				$actuaCat=$canCat;
				if($ini){
					$ini=false;
				}else{
					$canTable->nextRow();
				}
				$elTd=$canTable->addCell($canCat);
				$elTd->addAttribute("colspan", "3");
				$elTd->addAttribute("class","rolheader");
				$canTable->nextRow();
			}

			$chbx=new HtmlCheckbox("can".$i, $canId, $canName);
			if($rol!=NULL){
				foreach($rowsRC as $rowRC){
					$rol_can=new Rol_can();
					$rol_can=DaoMgr::getDao()->getObject($rol_can, $rowRC);
					if($rol_can->getCan_id() == $canId){
						$chbx->addAttribute("checked", "");
					}


				}
			}
			$canTable->addCell($chbx);
			if(($i+1)%3==0){
				$canTable->nextRow();
			}
			$i++;
		}
		$canTable->nextRow();
		return $canTable;
	}

	private function loadRol($rol){
		return $rol;
	}

	public function getDeleteMessage($objectKeys){
		$rol = $this->getLogicFacade().find($objectKeys);
		return "Confirma eliminar el siguiente Rol: " . $rol->getId() . " [" . $rol->getName() . "]?";
	}

	public function getHeaders() {
		$resp = new Vector();
		$resp->add("Id");
		$resp->add("Nombre");

		return $resp;
	}

	public function getKey($obj) {
		$rol = $obj;
		return $rol->getId();
	}

	public function getRow($o) {
		$rol = $o;
		$resp = new Vector();
		$resp->add($rol->getId());
		$resp->add($rol->getName());

		return $resp;
	}


}

class RolLogicFacade implements BusinessLogicFacade {

	public function delete($key){
		try {
			$rol = $this->find($key);
			if ($rol instanceof Rol) {
				DaoMgr::getDao()->delete($rol, $rol->getTableDescriptor());

				$logs = new Logs();
				$logs->setCategory("rol");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Elimino un rol con nombre: ".$rol->getName());//." y id: ".$back_end_user->getId());
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
		$object = new Rol();
		$object->setId($key);
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
			$rol = new Rol();
			$rol = DaoMgr::getDao()->getObject($rol, $row);
			$vector->add($rol);
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
				$where .= " and  (id like '%" . $token . "%'  or name like '%" . $token . "%')  ";
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
		return "select * from rol";
	}

	public function getSampleElement() {
		return new Rol();
	}

	public function insert($object) {
		try {
			DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
			$rows=DaoMgr::getDao()->executeQuery("select id from rol where name='".$object->getName()."'");
			$paId=$rows[0]["id"];
			if(isset($_REQUEST["qcan"])){
				$qcan=$_REQUEST["qcan"];
				for($i=0;$i<$qcan;$i++){
					if(isset($_REQUEST["can".$i])){
						$rol_can=new Rol_can();
						$rol_can->setCan_id($_REQUEST["can".$i]);
						$rol_can->setRol_id($paId);
						DaoMgr::getDao()->insert($rol_can, $rol_can->getTableDescriptor());


					}
				}
				
				$logs = new Logs();
				$logs->setCategory("rol");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Inserto un rol con nombre: ".$object->getName());//." y id: ".$back_end_user->getId());
				DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
			}
		}

		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function update($object){
		try {
			DaoMgr::getDao()->update($object, $object->getTableDescriptor());
			DaoMgr::getDao()->execute("delete from rol_can where rol_id=".$object->getId());
			if(isset($_REQUEST["qcan"])){
				$qcan=$_REQUEST["qcan"];
				for($i=0;$i<$qcan;$i++){
					if(isset($_REQUEST["can".$i])){
						$rol_can=new Rol_can();
						$rol_can->setCan_id($_REQUEST["can".$i]);
						$rol_can->setRol_id($object->getId());
						DaoMgr::getDao()->insert($rol_can, $rol_can->getTableDescriptor());


					}
				}
				$logs = new Logs();
				$logs->setCategory("rol");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Actualizo un rol con nombre: ".$object->getName());//." y id: ".$back_end_user->getId());
				DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
			}
		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function validateObjectPreAdd($object) {
		$rol = $object;
		$vector = new Vector();

		if($rol->getName()== NULL || $rol->getName()==""){
			$error=new ObjectValidatorException("name","Falto el nombre");
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

class AdminRol extends ComtorAbstractAdministrable {

	public function getController() {
		return new RolController();
	}

}

class Can extends DBElement{
	var $id;
	var $name;
	var $category;
	public function getTableDescriptor() {
		$td = new TableDescriptor("can");

		$td->addField("id","id", true);
		$td->addField("name","name", true);
		$td->addField("category","category", true);

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

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name=$name;
	}

	public function getCategory(){
		return $this->category;
	}
	public function setCategory($cat){
		$this->category=$cat;
	}
}

class Rol_can extends DBElement{
	var $rol_id;
	var $can_id;
	public function getTableDescriptor() {
		$td = new TableDescriptor("rol_can");

		$td->addField("rol_id","rol_id", false);
		$td->addField("can_id","can_id", true);
		$td->setFindable("rol_id", true);
		$td->setInsertable("rol_id", true);
		$td->setUpdateable("rol_id", false);
		$td->setFindable("can_id", true);
		$td->setInsertable("can_id", true);
		$td->setUpdateable("can_id", false);

		return $td;
	}

	public function getRol_id() {
		return $this->rol_id;
	}

	public function setRol_id($rol_id) {
		$this->rol_id= $rol_id;
	}

	public function getCan_id() {
		return $this->can_id;
	}

	public function setCan_id($can_id) {
		$this->can_id= $can_id;
	}

}
?>
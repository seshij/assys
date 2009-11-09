<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");



class Logs extends DBElement  {

	var $id;
	var $category;
	var $date_log;
	var $user;
	var $action;

	public function getTableDescriptor() {
		$td = new TableDescriptor("logs");

		$td->addField("id","id", false);
		$td->addField("category","category", true);
		$td->addField("date_log","date_log", true);
		$td->addField("user","user",true);
		$td->addField("action","action",true);

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

	public function getCategory() {
		return $this->category;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function getDate_log() {
		return $this->date_log;
	}

	public function setDate_log($date_log) {
		$this->date_log = $date_log;
	}

	public function getUser() {
		return $this->user;
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function getAction() {
		return $this->action;
	}

	public function setAction($action) {
		$this->action = $action;
	}


}

class LogsController extends AbstractComtorFacadeAdministratorController {

	public function getAddFormLabel() {
		return "Adicionar Logs";
	}

	public function getAddNewObjectLabel() {
		return "Nuevo Logs";
	}

	public function getAddPrivilege() {
		return "LOGS_ADD";
	}

	public function getAddPrivilegeMsg() {
		return "Ud. no posee privilegios para adicionar Logs.";
	}

	public function getAddedMessage($object) {
		return "El Logs ha sido adicionado.";
	}

	public function getConfirmDeleteMessage($object) {
		$logs = $object;
		return "Confirma eliminar el siguiente Logs: " . $logs->getId() . " [" . $logs->getName(). "]?";
	}

	public function getDeletePrivilege() {
		return "LOGS_DELETE";
	}

	public function getDeletePrivilegeMsg() {
		return "Ud. no posee privilegios para eliminar Logss.";
	}

	public function getDeletedMessage($element) {
		return "El Logs ha sido eliminado.";
	}

	public function getEditFormLabel() {
		return "Editar Logs";
	}

	public function getEditPrivilege() {
		return "LOGS_EDIT";
	}

	public function getEditPrivilegeMsg() {
		return "Ud. no posee privilegios para editar el Logs";
	}

	public function getViewPrivilege() {
		return "LOGS_VIEW";
	}

	public function getViewPrivilegeMsg() {
		return "Ud. no posee privilegios para ver los Logs";
	}

	public function getFormName() {
		return "logs_form";
	}

	public function getLogicFacade() {
		return new LogsLogicFacade();
	}

	public function getUpdatedMessage($object) {
		return "El Logs ha sido editado.";
	}

	public function initForm($form, $logs) {


		$form->addInputHidden("id","-1");
		$form->addField("name", "Nombre", new HtmlInputText("name", "", 50, 200), "Ingrese el Nombre");
		$form->addField("description", "Descripcion", new HtmlInputText("description", "", 50, 200), "Ingrese la descripcion");
		$form->addField("publish_begin","Frecha de inicio de publicación", new HtmlDateText($this->getFormName(),"publish_begin",true),"ayuda");
		$form->addField("publish_end","Fecha de fin de publicación", new HtmlDateText($this->getFormName(),"publish_end",true),"ayuda");


	}

	private function loadLogs($logs){
		return $logs;
	}

	public function getDeleteMessage($objectKeys){
		$logs = $this->getLogicFacade().find($objectKeys);
		return "Confirma eliminar el siguiente Logs: " . $logs->getId() . " [" . $logs->getName() . "]?";
	}

	public function getHeaders() {
		$resp = new Vector();
		$resp->add("Categoria");
		$resp->add("Fecha");
		$resp->add("usuario");
		$resp->add("Accion");

		return $resp;
	}

	public function getKey($obj) {
		$logs = $obj;
		return $logs->getId();
	}

	public function getRow($o) {
		$logs = $o;
		$resp = new Vector();
		$resp->add($logs->getCategory());
		$resp->add($logs->getDate_log());
		$resp->add($logs->getUser());
		$resp->add($logs->getAction());
			

		return $resp;
	}

	public function getActions($obj) {
		return "";
	}
	
	public function getToolBar() {
		$toolBar = new StyledToolBar();
		//$toolBar->addItem($this->getBaseUrl() . "&action=deleteform&key=", StyledToolBar::ICON_EXCEL, "Limpiar log");
		return $toolBar;
	}


}

class LogsLogicFacade implements BusinessLogicFacade {

	public function delete($key){
		try {
			$logs = $this->find($key);
			if ($logs instanceof Logs) {
				DaoMgr::getDao()->delete($logs, $logs->getTableDescriptor());

				$logs = new Logs();
				$logs->setCategory("logs");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Elimino un contenido : ".$logs->getName());//." y id: ".$object->getId());
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
		$object = new Logs();
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
			$logs = new Logs();
			$logs = DaoMgr::getDao()->getObject($logs, $row);
			$vector->add($logs);
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
				$where .= " and  (user like '%" . $token . "%'  or action like '%" . $token . "%')  ";
			}
		}
		$query = $this->getQueryString() . " where " . $where.  " order by date_log desc";
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
		return "select * from logs";
	}

	public function getSampleElement() {
		return new Logs();
	}

	public function insert($object) {
		try {
			DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
				
			$logs = new Logs();
			$logs->setCategory("logs");
			$date_time = date("Y-m-d H:i:s");
			$logs->setDate_log($date_time);

			if(isset($_SESSION["user"])){
				$user=$_SESSION["user"];
				$logs->setUser($user->getName());
			}

			$logs->setAction("Inserto un contenido : ".$object->getName());//." y id: ".$object->getId());
			DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function update($object){
		try {
			DaoMgr::getDao()->update($object, $object->getTableDescriptor());
				
			$logs = new Logs();
			$logs->setCategory("logs");
			$date_time = date("Y-m-d H:i:s");
			$logs->setDate_log($date_time);

			if(isset($_SESSION["user"])){
				$user=$_SESSION["user"];
				$logs->setUser($user->getName());
			}

			$logs->setAction("Actualizo un contenido : ".$object->getName());//." y id: ".$object->getId());
			DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function validateObjectPreAdd($object) {
		$logs = $object;
		$vector = new Vector();

		if($logs->getName()== NULL || $logs->getName()==""){
			$error=new ObjectValidatorException("name","Falto el nombre");
			$vector->addElement($error);
		}

		if($logs->getDescription()== NULL || $logs->getDescription()==""){
			$error=new ObjectValidatorException("description","Falto la descripcion");
			$vector->addElement($error);
		}

		if($logs->getPublish_begin()== NULL || $logs->getPublish_begin()==""){
			$error=new ObjectValidatorException("publish_begin","Falto la fecha de inicio");
			$vector->addElement($error);
		}

		if($logs->getPublish_end()== NULL || $logs->getPublish_end()==""){
			$error=new ObjectValidatorException("publish_end","Falto la fecha de fin");
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

class AdminLogs extends ComtorAbstractAdministrable {

	public function getController() {
		return new LogsController();
	}

}

?>
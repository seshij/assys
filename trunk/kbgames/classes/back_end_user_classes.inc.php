<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");
include_once("classes/logs_classes.inc.php");
include_once("classes/rol_classes.inc.php");


class Back_end_user extends DBElement  {

	var $login;
	var $name;
	var $last_name;
	var $passwd;
	var $passwdConfirm;
	var $mail;
	var $document;


	public function getTableDescriptor() {
		$td = new TableDescriptor("back_end_user");

		$td->addField("login","login", true);
		$td->addField("name","name", true);
		$td->addField("last_name","last_name", true);
		$td->addField("passwd","passwd",true);
		$td->addField("mail","mail",true);
		$td->addField("document","document",true);

		$td->setFindable("login", true);
		$td->setUpdateable("login", false);
		$td->setInsertable("login", true);


		return $td;
	}

	public function getLogin() {
		return $this->login;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getLast_name() {
		return $this->last_name;
	}

	public function setLast_name($last_name) {
		$this->last_name = $last_name;
	}


	public function getPasswd() {
		return $this->passwd;
	}

	public function setPasswd($passwd) {
		$this->passwd = $passwd;
	}

	public function getPasswdConfirm() {
		return $this->passwdConfirm;
	}

	public function setPasswdConfirm($passwdConfirm) {
		$this->passwdConfirm = $passwdConfirm;
	}
	public function getMail() {
		return $this->mail;
	}

	public function setMail($mail) {
		$this->mail= $mail;
	}

	public function getDocument() {
		return $this->document;
	}

	public function setDocument($document) {
		$this->document = $document;
	}
}

class BackEndUserController extends AbstractComtorFacadeAdministratorController {

	public function getAddFormLabel() {
		return "Adicionar Back_end_user";
	}

	public function getAddNewObjectLabel() {
		return "Nuevo Back_end_user";
	}

	public function getAddPrivilege() {
		return "BACKUSER_ADD";
	}

	public function getAddPrivilegeMsg() {
		return "Ud. no posee privilegios para adicionar usuario.";
	}

	public function getAddedMessage($object) {
		return "El usuario ha sido adicionado.";
	}

	public function getConfirmDeleteMessage($object) {
		$back_end_user = $object;
		return "Confirma eliminar el siguiente Back_end_user: " . $back_end_user->getLogin() . " [" . $back_end_user->getName(). "]?";
	}

	public function getDeletePrivilege() {
		return "BACKUSER_DELETE";
	}

	public function getDeletePrivilegeMsg() {
		return "Ud. no posee privilegios para eliminar Users.";
	}

	public function getDeletedMessage($element) {
		return "El Back_end_user ha sido eliminado.";
	}

	public function getEditFormLabel() {
		return "Editar Back_end_user";
	}

	public function getEditPrivilege() {
		return "BACKUSER_EDIT";
	}

	public function getEditPrivilegeMsg() {
		return "Ud. no posee privilegios para editar el Back_end_user";
	}
	public function getViewPrivilege() {
		return "BACKUSER_VIEW";
	}

	public function getViewPrivilegeMsg() {
		return "Ud. no posee privilegios para ver los Usuarios Administradores";
	}

	public function getFormName() {
		return "back_end_user_form";
	}

	public function getLogicFacade() {
		return new BackEndUserLogicFacade();
	}

	public function getUpdatedMessage($object) {
		return "El Back_end_user ha sido editado.";
	}

	public function initForm($form, $user) {

		$form->addField("login", "Login", new HtmlInputText("login", "", 35, 100), "Ingrese el login");
		if(isset($_REQUEST['key'])){
			$user->setPasswd("");
		}
		$form->addField("passwd", "Password", new HtmlInputPassword("passwd", "", 35, 100), "Ingrese el password");
		$form->addField("passwdConfirm","Confirmacion Password", new HtmlInputPassword("passwdConfirm", "", 35, 100), "Ingrese la confirmacion del password");

		$form->addField("name", "Nombres", new HtmlInputText("name", "", 35, 100), "Ingrese el nombre");
		$form->addField("last_name", "Apellidos", new HtmlInputText("last_name", "", 35, 100), "Ingrese el Apellido");
		$form->addField("mail", "Mail", new HtmlInputText("mail", "", 35, 100), "Ingrese el mail");
		$form->addField("document", "Documento", new HtmlInputText("document", "", 35, 100), "Ingrese el documento");
		//$form->addField("rol", "Rol", new HtmlInputText("rol","",35,50), "Ingrese el rol");
		$rows=DaoMgr::getDao()->executeQuery("select * from rol");
		$form->addInputHidden("qrol",sizeOf($rows));
		$form->addField("rol", "Rol", $this->loadRolTable($rows,$user), "Ingrese el rol");



	}

	public function loadRolTable($rows, $user){

		$tableCheckBox = new HtmlTable();
		$tableCheckBox->setClass("comtorbox");

		if($user!=NULL){
			$query="select * from user_rol where user_id='".$user->getLogin()."'";
			$rowsUR=DaoMgr::getDao()->executeQuery($query);
		}

		$i = 0;
		foreach($rows as $row){

			$rol = new Rol();
			$rol = DaoMgr::getDao()->getObject($rol,$row);
			$id_rol = $rol->getId();
			$name_rol = $rol->getName();
			$chbx=new HtmlCheckbox("rol".$i,$id_rol,"$name_rol");
			if($user!=NULL){
				$userId=$user->getLogin();
				foreach($rowsUR as $rowUR){
					$user_rol=new User_rol();
					$user_rol=DaoMgr::getDao()->getObject($user_rol, $rowUR);
					if($user_rol->getRol_id() == $id_rol){
						$chbx->addAttribute("checked", "");
					}
				}
			}
			$tableCheckBox->addCell($chbx);
			if(($i+1)%2==0){
				$tableCheckBox->nextRow();
			}
			$i++;
		}
		if(($i+1)%2==0){
			$tableCheckBox->addCell();
			$tableCheckBox->nextRow();
		}

		return $tableCheckBox;
	}

	private function loadUser($back_end_user){
		return $back_end_user;
	}

	public function getDeleteMessage($objectKeys){
		$back_end_user = $this->getLogicFacade().find($objectKeys);
		return "Confirma eliminar la siguiente Back_end_user: " . $back_end_user->getLogin() . " [" . $back_end_user->getName() . "]?";
	}

	public function getHeaders() {
		$resp = new Vector();
		$resp->add("Login");
		$resp->add("Nombre");
		$resp->add("Apellido");
		return $resp;
	}

	public function getKey($obj) {
		$back_end_user = $obj;
		return $back_end_user->getLogin();
	}

	public function getRow($o) {
		$back_end_user = $o;
		$resp = new Vector();
		$resp->add($back_end_user->getLogin());
		$resp->add($back_end_user->getName());
		$resp->add($back_end_user->getLast_name());

		return $resp;
	}

}

class BackEndUserLogicFacade implements BusinessLogicFacade {

	public function delete($key){
		try {
			$back_end_user = $this->find($key);
			if ($back_end_user instanceof Back_end_user) {
				DaoMgr::getDao()->delete($back_end_user, $back_end_user->getTableDescriptor());

				$logs = new Logs();
				$logs->setCategory("back_end_user");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Elimino un usuario con nombre: ".$back_end_user->getName());//." y id: ".$back_end_user->getId());
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
		$object = new Back_end_user();
		$object->setLogin($key);
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
			$pc = new Back_end_user();
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
				$where .= " and  (login like '%" . $token . "%'  or name like '%" . $token . "%')  ";
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
		return "select * from back_end_user";
	}

	public function getSampleElement() {
		return new Back_end_user();
	}

	public function insert($object) {

		try {

			if((strcmp($object->getPasswd(), $_REQUEST['passwdConfirm'])) == 0){

				$passwd_encript = md5($object->getPasswd());
				$object->setPasswd($passwd_encript);
				DaoMgr::getDao()->insert($object, $object->getTableDescriptor());
				if(isset($_REQUEST['qrol'])){
					$qrol=$_REQUEST["qrol"];
					for($i=0;$i<$qrol;$i++){
						if(isset($_REQUEST["rol".$i])){
							$user_rol=new User_rol();
							$user_rol->setRol_id($_REQUEST["rol".$i]);
							$user_rol->setUser_id($object->getLogin());
							DaoMgr::getDao()->insert($user_rol, $user_rol->getTableDescriptor());
						}
					}

				}




				$logs = new Logs();
				$logs->setCategory("back_end_user");
				$date_time = date("Y-m-d H:i:s");
				$logs->setDate_log($date_time);

				if(isset($_SESSION["user"])){
					$user=$_SESSION["user"];
					$logs->setUser($user->getName());
				}

				$logs->setAction("Inserto un nuevo usuario con nombre: ".$object->getName());//." y id: ".$object->getId());
				DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());
			}
			else{
				echo "el password no coincide";
			}

		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function update($object){
		try {

			if(($_REQUEST['passwd'] != "") && ($_REQUEST['passwdConfirm'] != "") ){
				if(strcmp(($_REQUEST['passwd']),($_REQUEST['passwdConfirm']))==0){
					$passwd_encript = md5($object->getPasswd());
					$object->setPasswd($passwd_encript);
					DaoMgr::getDao()->update($object, $object->getTableDescriptor());
					$sql = "delete from user_rol where user_id='".$object->getLogin()."'";
					DaoMgr::getDao()->execute($sql);
				}
				else{
					echo "el password no coincide!!!";
				}
			}
			else{
				$login = $object->getLogin();
				$rows = DaoMgr::getDao()->executeQuery("select passwd from back_end_user where login ='$login' ");
				$passwd = $rows [0]['passwd'];
				$object->setPasswd($passwd);
				var_dump($object);
				DaoMgr::getDao()->update($object, $object->getTableDescriptor());
				$sql = "delete from user_rol where user_id='".$object->getLogin()."'";
				DaoMgr::getDao()->execute($sql);

			}




			if(isset($_REQUEST['qrol'])){
				$qrol=$_REQUEST["qrol"];
				for($i=0;$i<$qrol;$i++){
					if(isset($_REQUEST["rol".$i])){
						$user_rol=new User_rol();
						$user_rol->setRol_id($_REQUEST["rol".$i]);
						$user_rol->setUser_id($object->getLogin());
						DaoMgr::getDao()->insert($user_rol, $user_rol->getTableDescriptor());
					}
				}

			}

			$logs = new Logs();
			$logs->setCategory("back_end_user");
			$date_time = date("Y-m-d H:i:s");
			$logs->setDate_log($date_time);

			if(isset($_SESSION["user"])){
				$user=$_SESSION["user"];
				$logs->setUser($user->getName());
			}

			$logs->setAction("Actualizo un usuario con nombre: ".$object->getName());//." y id: ".$object->getId());
			DaoMgr::getDao()->insert($logs,$logs->getTableDescriptor());

		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}

	public function validateObjectPreAdd($object) {

		$back_end_user = $object;
		$vector = new Vector();
		/*
		 if($back_end_user->getName()== NULL || $back_end_user->getShort_name()==""){
			$error=new ObjectValidatorException("short_name","Falto el nombre corto");
			$vector->addElement($error);
			}

			if($back_end_user->getName()== NULL || $back_end_user->getName()==""){
			$error=new ObjectValidatorException("name","Falto el nombre");
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

class AdminBackEndUser extends ComtorAbstractAdministrable {

	public function getController() {
		return new BackEndUserController();
	}

}

class User_rol extends DBElement {
	var $rol_id;
	var $user_id;
	public function getTableDescriptor() {
		$td = new TableDescriptor("user_rol");

		$td->addField("rol_id","rol_id", false);
		$td->addField("user_id","user_id", true);
		$td->setFindable("rol_id", true);
		$td->setInsertable("rol_id", true);
		$td->setUpdateable("rol_id", false);
		$td->setFindable("user_id", true);
		$td->setInsertable("user_id", true);
		$td->setUpdateable("user_id", false);

		return $td;
	}

	public function getRol_id() {
		return $this->rol_id;
	}

	public function setRol_id($rol_id) {
		$this->rol_id= $rol_id;
	}

	public function getUser_id() {
		return $this->user_id;
	}

	public function setUser_id($user_id) {
		$this->user_id= $user_id;
	}

}


?>
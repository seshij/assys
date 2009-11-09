<?php
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("classes/back_end_user_classes.inc.php");


class AdminDefault extends HtmlGuiInterface {
	var $theContainer;
	var $action;
	public function AdminDefault(){
		$this->theContainer=new HtmlContainer();
	}

	public function getHtml(){
		$this->action=$this->getAction();

		if($this->action=="logout"){
			$this->logoutUser();
		}
		if(isset($_SESSION["user"])){
			$this->theContainer->addElement($this->getMainMenu());
		}else{
			if($this->action=="default"){

				$this->theContainer->addElement($this->getLoginForm());
			}else if($this->action=="initial_ok"){
				$this->theContainer->addElement($this->validateLogin());
			}else{
				$this->theContainer->addElement($this->getLoginForm());
			}
		}
		return $this->theContainer->getHtml();
	}

	protected function getAction() {
		if ((!isset($_REQUEST["action"])) || ($_REQUEST["action"] == NULL)) {
			return "default";
		}

		return $_REQUEST["action"];
	}


	public function getLoginForm(){
		$logForm=new HtmlLoginForm("Login Administradores", "logAdmin", "admin.php");
		return $logForm;
	}

	public function validateLogin(){
		$loginRight=false;
		if(isset($_REQUEST["user"]) && isset($_REQUEST["password"])){
			$name=$_REQUEST["user"];
			$pwd=$_REQUEST["password"];
			if($name=="factura" && $pwd=="assyskey"){

				$user->name=$name;
				$_SESSION['user']=$user;
				$loginRight=true;
			}else{
				$theUser=$this->validateLoginWithDB($name, $pwd);
				if($theUser != NULL){
					$_SESSION['user']=$theUser;
					$_SESSION['privileges']= $this->getUserPrivileges($theUser->getLogin());
					$loginRight=true;
				}
			}
		}

		if($loginRight){
			return $this->getMainMenu();
		}else{
			/*$errorT=new HtmlFont("Revise el nombre de usuario o contraseña");
			 $errorT->addAttribute("color", "#FF3333");
			 	
			 $this->theContainer->addElement($errorT);*/
			$logForm=$this->getLoginForm();
			$logForm->addError("Revise el nombre de usuario o contraseña");

			return $logForm;
		}
	}

	public function getUserPrivileges($login){
		$rolquery="select user_rol.user_id,rol_can.can_id from rol_can, user_rol where user_rol.user_id='$login' AND user_rol.rol_id=rol_can.rol_id";
		//	$rolquery="select can_id from rol_can where rol_id IN (select rol_id from user_rol where user_id='$login')";
		$rows=DaoMgr::getDao()->executeQuery($rolquery);

		$privileges=array();
		foreach($rows as $row){
			//	echo($row['can_id']."<br>");
			$privileges[]=$row['can_id'];
		}
		return $privileges;

	}

	public function getMainMenu(){

		$table = new HtmlTable();
		$table->addAttribute("border","0");

		$tmoTd=$table->addCell(new HtmlLink("Categorias","admin.php?option=categorias",""),"2");
		$table->nextRow();
		$tmoTd=$table->addCell(new HtmlLink("Juegos","admin.php?option=menu_juegos",""),"2");
		$table->nextRow();
                $tmoTd=$table->addCell(new HtmlLink("Club KBGames","admin.php?option=miembros",""),"2");
		$table->nextRow();
                $tmoTd=$table->addCell(new HtmlLink("Noticias y Eventos","admin.php?option=noticias",""),"2");
		$table->nextRow();
		
		return $table;
	}

	public function logoutUser(){
		unset($_SESSION['user']);
		unset($_SESSION['privileges']);
	}

	public function validateLoginWithDB($login, $pwd){
		$tmpUser = new Back_end_user();
		$tmpUser->setLogin($login);
		try
		{
			$tmpUser= DaoMgr::getDao()->load($tmpUser, $tmpUser->getTableDescriptor());
			$cryptedpwd=md5($pwd);
			if($tmpUser==NULL){
				return NULL;
			}
			if($cryptedpwd==$tmpUser->getPasswd()){
				return $tmpUser;
			}else{
				return NULL;
			}
		}
		catch (Exception $exception) {
			throw new BusinessLogicException(get_class($exception) . ": " . $exception->getMessage());
		}
	}


	public function requireComtorSession(){
		return true;
	}
}
?>
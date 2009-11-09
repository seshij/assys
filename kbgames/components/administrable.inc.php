<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");


//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlGuiInterface
//--------------------------------------------------------------------------------------------------------------------//
abstract class HtmlGuiInterface {

	const COMTOR_SESSION = "user";
	const COMTOR_USER_PRIVILEGES = "privileges";

	public function HtmlGuiInterface(){
		// Empty Constructor
	}

	public abstract function getHtml();
	public abstract function requireComtorSession();

	public function createComtorSession($object){
		$_SESSION[HtmlGuiInterface::COMTOR_SESSION] = $object;
	}

	public function destroyComtorSession(){
		unset($_SESSION[HtmlGuiInterface::COMTOR_SESSION]);
	}

	protected function redirect($url) {
		header("Location: " . $url);
	}

	public function getData($request, $servletResponse) {
		// Empty Method
}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorAbstractAdministrable
//--------------------------------------------------------------------------------------------------------------------//
abstract class ComtorAbstractAdministrable extends HtmlGuiInterface {

	public abstract function getController();

	public function getHtml() {
		$viewer = new ComtorAdministratorViewer($this->getController());
		return $this->getHtmlFromViewer($viewer);
	}

	protected function getHtmlFromViewer($viewer) {
		$html = new HtmlContainer();
		$html->addElement($viewer);
		return $html->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorAbstractViewer
//--------------------------------------------------------------------------------------------------------------------//
abstract class ComtorAbstractViewer implements HtmlElement {

	private $option;

	public function ComtorAbstractViewer ($option) {
		$this->init($option);
	}

	protected function init($option) {
		if ($option == NULL) {
			$this->setOption($this->calculateOption());
		} else {
			$this->setOption($option);
		}
	}

	public function getOption() {
		return $this->option;
	}

	public function setOption($option) {
		$this->option = $option;
	}

	protected function calculateOption() {
		if ((!isset($_REQUEST["option"])) || ($_REQUEST["option"] == NULL)) {
			return "default";
		}
		return $_REQUEST["option"];
	}

	protected function getAction() {
		if ((!isset($_REQUEST["action"])) || ($_REQUEST["action"] == NULL)) {
			return "default";
		}
		return $_REQUEST["action"];
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorAbstractViewer
//--------------------------------------------------------------------------------------------------------------------//
class ComtorAdministratorViewer extends ComtorAbstractViewer {

	private $controller;
	var $moduleUrl;

	public function ComtorAdministratorViewer($controller, $option = NULL) {
		$this->ComtorAbstractViewer($option);
		$this->initViewer($controller);
	}

	protected function initViewer($controller) {
		$this->controller = $controller;
		$this->controller->setOption($this->getOption());
		$this->moduleUrl = "admin.php?option=" . $this->getOption();
	}

	public function getHtml() {
		$action = $this->getAction();
		if ($action == "default") {
			return $this->defaultAction();
		}
		else if ($action == "addform") {
			return $this->actionAddForm();
		}
		else if ($action == "add") {
			return $this->actionProcessAddForm();
		}
		else if ($action == "editform") {
			return $this->actionEditForm();
		}
		else if ($action == "edit") {
			return $this->actionProcessEditForm();
		}
		else if ($action == "deleteform") {
			return $this->actionDeleteForm();
		}
		else if ($action == "delete") {
			return $this->actionProcessDeleteForm();
		}
		else if ($action == "viewform") {
			return $this->actionViewForm();
		}
		else if ($action == "clearfilter") {
			$controller_name = $_REQUEST["controller_name"];
			if (isset($_SESSION[controller_name . "_filter"])) {
				unset($_SESSION[controller_name . "_filter"]);
			}
			return $this->defaultAction();
		}
		else {
			return $this->actionOther($action);
		}
	}

	private function actionViewForm() {
		try {
			$key = $this->getObjectKey();
			return $this->controller->getViewForm($key)->getHtml();
		}
		catch (ComtorDaoException $ex) {
			return $ex->getMessage();
		}
		catch (Exception $ex) {
			return $ex->getMessage();
		}
	}

	private function actionProcessDeleteForm() {
		try {
			return $this->controller->processDeleteForm($this->getObjectKey());
		}
		catch (ComtorDaoException $e) {
			return $e->__toString();
		}
		catch (ComtorAdministrableHtmlException $e) {
			return $e->__toString();
		}
		catch (Exception $e) {
			return $e->__toString();
		}
	}

	private function actionProcessAddForm() {
		try {
			return $this->controller->processAddForm();
		}
		catch (Exception $e) {	
			return "Error de Base de Datos al adicionar ".$e->getMessage();
			//return $e->getOriginalMessage();
		}
	}

	private function actionProcessEditForm() {
		try {
			return $this->controller->processEditForm();
		}
		catch (Exception $e) {
			return "Error de Base de Datos al editar".$e->getMessage();;
		}
	}

	public function actionOther($action) {
		return $this->controller->getActionOther($action);
	}

	public function actionAddForm() {
		return $this->controller->getAddForm()->getHtml();
	}

	private function actionEditForm() {
		try {
			$key = $this->getObjectKey();
			return $this->controller->getEditForm($key)->getHtml();
		}
		catch (ComtorDaoException $ex) {
			return $ex->getMessage();
		}
		catch (Exception $ex) {
			return $ex->getMessage();
		}
	}

	private function actionDeleteForm() {
		$box = NULL;
		$form = NULL;
		try {
			$box = $this->controller->getDeleteForm($this->getObjectKey());
			if ($box == NULL) {
				$form = new FormInAStyledBox("Delete?", $this->moduleUrl, "POST");
				$form->addMessage($this->controller->getDeleteMessage($this->getObjectKey()));
				$form->addInputHidden("action", "delete");
				$form->addInputHidden("key", $this->getObjectKey());
				$form->addButtonsForDelete("Delete");
			}
			else {
				$form = $box;
			}
		}
		catch (Exception $exception) {
			// TODO
		}
		return $form->getHtml();
	}

	private function getObjectKey(){
		if (!isset($_REQUEST["key"]) || $_REQUEST["key"] == NULL) {
			throw new Exception("KEY NOT SENDED");
		}
		return $_REQUEST["key"];
	}

	public function defaultAction() {

        if (!Security::can($this->controller->getViewPrivilege())) {
			$a=ComtorAdministratorControllerHelper::continueForm("admin.php", $this->controller->getViewPrivilegeMsg());
			return $a->getHtml();
		}

		$elements = $this->getObjectList();
		$main = new HtmlContainer();
		$toolbar = $this->controller->getToolBar();
		$table = $this->getQueryResultTable($elements);
		$searchBar=$this->controller->getSearchBar();

		$main->addElement($searchBar);
		$main->addElement("<br>\n");
		$main->addElement($toolbar);
		$main->addElement("<br>\n");
		$main->addElement($table);
		$main->addElement("<br>\n");

		$pagebar = $this->controller->getPagingBar();
		$main->addElement($pagebar);
		$main->addElement("<br>\n");
		//$main->addElement(new NavBarTable(moduleUrl, $elements->size()));
		return $main->getHtml();
	}

	protected function getObjectList() {
		return $this->controller->getObjectsList();
	}

	private function getQueryResultTable($elements) {
		$table = new StyledBigTable();

		for ($i = 0; $i < $this->controller->getHeaders()->size(); $i++) {
			$table->addHeader($this->controller->getHeaders()->elementAt($i));
		}
		$table->addHeader(" ");

		$step = 20;
		$current = 0;
		try {
			if (isset($_REQUEST["page"])){
				$page = intval($_REQUEST["page"]);

			} else {
				$page = 1;
			}
			$current=($page-1)*20;
		}
		catch (Exception $e) {
			$step = 20;
			$current = 0;
		}
		$nextCurrent = $current + $step;
		$limit = ($nextCurrent > $elements->size()) ? $elements->size() : $nextCurrent;
		for ($i = $current; $i < $limit; $i++) {
			$ele = $elements->get($i);
			$row = $this->controller->getRow($ele);
			$row->add($this->controller->getActions($ele));
			$table->addRow($row);

		}

		return $table;
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorException
//--------------------------------------------------------------------------------------------------------------------//
class ComtorException extends Exception{

	public function ComtorException($message) {
		parent::__construct($message,03);	
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorDaoException
//--------------------------------------------------------------------------------------------------------------------//
class ComtorDaoException extends ComtorException {

	public function ComtorDaoException($message) {
		$this->ComtorException($message);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: ComtorAdministratorController
//--------------------------------------------------------------------------------------------------------------------//
interface ComtorAdministratorController {

	public function getObjectsList();
	public function getHeaders();
	public function getRow($o);
	public function getActions($obj);
	public function getKey($obj);
	public function getToolBar();
	public function setOption($option);
	public function getOption();
	public function processAddForm();
	public function processEditForm();
	public function processDeleteForm($key);
	public function getAddForm();
	public function getEditForm($key);
	public function getViewForm($key);
	public function getDeleteMessage($objectKeys);
	public function initObjectFromRequest($obj);
	public function getActionOther($action);
	public function getDeleteForm($key);
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: AbstractComtorAdministratorController
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorAdministratorController implements ComtorAdministratorController {

	private $option;

	public function setOption($option) {
		$this->option = $option;
	}

	public function getOption() {
		return $this->option;
	}

	protected function getBaseUrl() {
		return "?option=" . $this->getOption();
	}

	protected function getEditIcon($moduleUrl, $ele) {
		$actionIcon = new ActionIcon($moduleUrl . "&amp;action=editform&amp;key=" . $this->getKey($ele), ActionIcon::ICON_EDIT, "Edit");
		return $actionIcon->getHtml();
	}

	protected function getDeleteIcon($moduleUrl, $ele) {
		$deleteIcon = new ActionIcon($moduleUrl . "&amp;action=deleteform&amp;key=" . $this->getKey($ele), ActionIcon::ICON_DELETE,
			"Delete"); 
		return $deleteIcon->getHtml();
	}

	protected function getViewIcon($moduleUrl, $ele) {
		$viewIcon = new ActionIcon($moduleUrl . "&amp;action=viewform&amp;key=" . $this->getKey($ele), ActionIcon::ICON_VIEW, "View");
		return $viewIcon->getHtml();
	}

	public function getFilter() {
		return ComtorFilterHelper::getFilter(get_class($this));
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorFilterHelper
//--------------------------------------------------------------------------------------------------------------------//
class ComtorFilterHelper {

	public static function getValue($name, $controller) {
		$value = NULL;
		$attrName = $controller . "_" . $name;
		if (isset($_REQUEST[$name])) {
			if($_REQUEST[$name]==""){
				$value=NULL;
				unset($_SESSION[$attrName]);
			}else{
				$value = $_REQUEST[$name];
			}
		}

		if ($value == NULL) {
			$sessionValue = NULL;
			if (isset($_SESSION[$attrName])) {
				$sessionValue = $_SESSION[$attrName];
			}
			if ($sessionValue != NULL) {
				$value = $sessionValue;
			}
		}
		else {
			$_SESSION[$attrName]  =  $value;
		}
		return $value;
	}

	public static function getFilter($controller) {
		return ComtorFilterHelper::getValue("filter", $controller);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: AbstractComtorFacadeAdministratorController
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorFacadeAdministratorController extends AbstractComtorAdministratorController {

	public abstract function getConfirmDeleteMessage($object);
	public abstract function getDeletedMessage($element);
	public abstract function getLogicFacade();
	public abstract function getAddNewObjectLabel();
	public abstract function getUpdatedMessage($object);
	public abstract function initForm($form, $object);
	public abstract function getEditFormLabel();
	public abstract function getAddedMessage($object);
	public abstract function getAddFormLabel();
	public abstract function getAddPrivilege();
	public abstract function getAddPrivilegeMsg();
	public abstract function getEditPrivilege();
	public abstract function getEditPrivilegeMsg();
	public abstract function getDeletePrivilege();
	public abstract function getDeletePrivilegeMsg();
	public abstract function getViewPrivilege();
	public abstract function getViewPrivilegeMsg();
	public abstract function getFormName();

	public function getPagingBar(){
		$pagingTable=new HtmlTable();
		$pagingTable->setClass("pagebar");
		$pagingTable->addAttribute("border","0");
		$rows=$this->getObjectsList();
		$total=$rows->size();
		$linksContainer=new HtmlContainer();
		$intervals=ceil($total/20);
		for($i=0;$i<$intervals;$i++){
			$page=$i+1;
			$pageLink= new HtmlLink("$page", $this->getBaseUrl()."&page=$page");
			$linksContainer->addElement($pageLink);
		}

		$pagingTable->addCell($linksContainer);
		$pagingTable->nextRow();
		return $pagingTable;
	}

	public function getSearchBar(){
		$searchForm=new HtmlForm($this->getBaseUrl(), "POST");
		$searchVal=" ";
		if(isset($_SESSION[get_class($this).'_filter'])){
			$searchVal=$_SESSION[get_class($this).'_filter'];
		}
		$searchForm->addElement(new HtmlInputText("filter",$searchVal));
		$searchForm->addElement(new HtmlButton(HtmlButton::SUBMIT_BUTTON, "buscar", "Buscar"));
		return $searchForm;
	}

	public function getObjectFromRequest(){
		$object = $this->getLogicFacade()->getSampleElement();
		$this->initObjectFromRequest($object);
		return $object;
	}

	public function createForm($typeForm, $title) {
		return new FormInAStyledBox($title, $this->getBaseUrl(), AdministrableAddEditForm::METHOD_POST);
	}

	public function redirectByCancel(){
		header("Location: " . $this->getBaseUrl());
	}

	public function createFormWithErrors($object, $label, $errors, $formType) {
		$form = $this->createForm($formType, $label);
		$form->setName($this->getFormName());
		$this->initForm($form, NULL);
		HtmlFormHelper::initFormFormObjectWithErrors($form, $object, $errors);
		switch ($formType) {
			case AdministrableAddEditForm::ADD_FORM:
				$form->addBasicButtons("Add", "Cancel");
				$form->addInputHidden("action", "add");
				break;
			case AdministrableAddEditForm::EDIT_FORM:
				HtmlFormHelper::setFormEditFromObject($form, $object);
				$form->addBasicButtons("Edit", "Cancel");
				$form->addInputHidden("action", "edit");
				break;
		}
		return $form;
	}

	public function getActionOther($action) {
		return ComtorAdministratorControllerHelper::continueFormMessage($this->getBaseUrl(), "Other Action: " . action);
	}

	public function getActions($obj) {
		$editIcon = $this->getEditIcon($this->getBaseUrl(), $obj);
		$deleteIcon = $this->getDeleteIcon($this->getBaseUrl(), $obj);
		return $editIcon . "&nbsp" . $deleteIcon;
	}

	public function getDeleteForm($key){
		if (!Security::can($this->getDeletePrivilege())) {
			return ComtorAdministratorControllerHelper::continueForm($this->getBaseUrl(), $this->getDeletePrivilegeMsg());
		}
		try {
			$object = $this->getLogicFacade()->find($key);
			$vector = $this->getLogicFacade()->validateObjectPreDelete($object);
			if ($vector->size() > 0) {
				$htmlUl = new HtmlUl();
				$exceptionArray = $vector->toArray();
				foreach ($exceptionArray as $objectValidatorException) {
					$htmlUl->addElement(new HtmlLi($objectValidatorException->getDescription()));
				}
				return ComtorAdministratorControllerHelper::continueForm($this->getBaseUrl(), $htmlUl->getHtml());
			}

			$form = $this->createForm(AdministrableAddEditForm::DELETE_FORM, "Delete");
			$form->setName($this->getFormName());
			$form->addMessage($this->getConfirmDeleteMessage($object));
			$form->addInputHidden("action", "delete");
			$form->addInputHidden("key", $key);
			$form->addBasicButtons("Delete", "Cancel");
			return $form;
		}
		catch (Exception $e) {
			throw new ComtorDaoException(get_class($e) . ": " . $e->getMessage());
		}
	}

	public function getObjectsList() {

		try {
			$filterList = $this->getFilterList();
			return $this->getLogicFacade()->getObjectsList($filterList);
		}
		catch (BusinessLogicException $exception) {
			return new Vector();
		}
	}

	public function getFilterList() {
		$vector = new Vector();
		$vector->add(new ComtorObjectListFilter("filter", ComtorFilterHelper::getFilter(get_class($this))));
		return $vector;
	}

	public function getToolBar() {
		$toolBar = new StyledToolBar();
		$toolBar->addItem($this->getBaseUrl() . "&action=addform", StyledToolBar::ICON_NEW, $this->getAddNewObjectLabel());
		return $toolBar;
	}

	//public function
	public function initObjectFromRequest($obj){
		RequestHelper::initObjectFromRequest($obj);
	}

	public function processAddForm(){
		$cancel = NULL;
		if (isset($_REQUEST["cancel"])) {
			$cancel = $_REQUEST["cancel"];
		}
		if ($cancel != NULL) {
			return $this->redirectByCancel();
		}

		$object = $this->getObjectFromRequest();
		$errors = $this->getLogicFacade()->validateObjectPreAdd($object);
		if ($errors->size() > 0) {
			return $this->createFormWithErrors($object, $this->getAddFormLabel(), $errors, AdministrableAddEditForm::ADD_FORM)->getHtml();
		}
		$this->getLogicFacade()->insert($object);
		return ComtorAdministratorControllerHelper::continueFormMessage($this->getBaseUrl(), $this->getAddedMessage($object));
	}

	public function processEditForm(){
		$cancel = NULL;
		if (isset($_REQUEST["cancel"])) {
			$cancel = $_REQUEST["cancel"];
		}
		if ($cancel != NULL) {
			return $this->redirectByCancel();
		}

		$object = $this->getObjectFromRequest();
		$errors = $this->getLogicFacade()->validateObjectPreEdit($object);
		if ($errors->size() > 0) {
			return $this->createFormWithErrors($object, $this->getEditFormLabel(), $errors, AdministrableAddEditForm::EDIT_FORM)->getHtml();
		}
		$this->getLogicFacade()->update($object);
		return ComtorAdministratorControllerHelper::continueFormMessage($this->getBaseUrl(), $this->getUpdatedMessage($object));
	}

	public function processDeleteForm($key){
		$cancel = NULL;
		if (isset($_REQUEST["cancel"])) {
			$cancel = $_REQUEST["cancel"];
		}
		if ($cancel != NULL) {
			return $this->redirectByCancel();
		}

		$object = $this->getLogicFacade()->find($key);
		$this->getLogicFacade()->delete($key);
		return ComtorAdministratorControllerHelper::continueFormMessage($this->getBaseUrl(), $this->getDeletedMessage($object));
	}

	public function getAddForm() {
		if (!Security::can($this->getAddPrivilege())) {
			return ComtorAdministratorControllerHelper::continueForm($this->getBaseUrl(), $this->getAddPrivilegeMsg());
		}

		$form = $this->createForm(AdministrableAddEditForm::ADD_FORM, $this->getAddFormLabel());
		$form->setName($this->getFormName());
		$this->initForm($form, NULL);
		$form->addInputHidden("action", "add");
		$form->addBasicButtons("Add", "Cancel");
		return $form;
	}

	public function getEditForm($key){
		if (!Security::can($this->getEditPrivilege())) {
			return ComtorAdministratorControllerHelper::continueForm($this->getBaseUrl(), $this->getEditPrivilegeMsg());
		}

		try {
			$object = $this->getLogicFacade()->find($key);
			$form = $this->createForm(AdministrableAddEditForm::EDIT_FORM, $this->getEditFormLabel());
			$form->setName($this->getFormName());
			$this->initForm($form, $object);
			$this->fillFormOnEdit($object, $form);
			$form->addInputHidden("action", "edit");
			$form->addBasicButtons("Edit", "Cancel");
			return $form;
		}
		catch (Exception $e) {
			throw new ComtorDaoException(get_class($e) . ": " . $e->getMessage());
		}
	}

	public function getViewForm($key){
		$form = new FormInAStyledBox("View Form", $this->getBaseUrl(), AdministrableAddEditForm::METHOD_POST);
		$form->addMessage("View Form for this Element is not implemented yet.");
		$form->addButton("ok", "Continue");
		return $form;
	}

	protected function fillFormOnEdit($object, $form) {
		HtmlFormHelper::initFormFromObject($form, $object);
		HtmlFormHelper::setFormEditFromObject($form, $object);
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: BusinessLogicException
//--------------------------------------------------------------------------------------------------------------------//
class BusinessLogicException extends ComtorException {

	public function BusinessLogicException($message) {
		$this->ComtorException($message);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: BusinessLogicFacade
//--------------------------------------------------------------------------------------------------------------------//
interface BusinessLogicFacade {

	public function find($key);
	public function delete($key);
	public function insert($object);
	public function update($object);
	public function getObjectsList($filters);
	public function getObjectsListWithParameter($filters);
	public function getObjectsListForFinder($filters);
	public function getSampleElement();
	public function validateObjectPreAdd($object);
	public function validateObjectPreEdit($object);
	public function validateObjectPreDelete($object);
	public function getQueryString();
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: Security
//--------------------------------------------------------------------------------------------------------------------//
class Security {

public static function can($privilege) {
		$privileges = $_SESSION[HtmlGuiInterface::COMTOR_USER_PRIVILEGES];

		for($i=0;$i<sizeof($privileges);$i++){
			if($privileges[$i]==$privilege){
				return true;
			}else if(strpos($privilege, "ADD")!=false && $privileges[$i]=="ALL_ADD"){
				return true;
			}else if(strpos($privilege, "EDIT")!=false && $privileges[$i]=="ALL_EDIT"){
				return true;
			}else if(strpos($privilege, "DELETE")!=false && $privileges[$i]=="ALL_DELETE"){
				return true;
			}else if(strpos($privilege, "VIEW")!=false && $privileges[$i]=="ALL_VIEW"){
				return true;
			}
		}

		return false;

	return true;
	}

	private static function readPrivileges($privilege, $privileges) {

		if ($privileges->can("ALL")) {
			return true;
		}
		return $privileges->can($privilege);

		return true;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: UserPrivileges
//--------------------------------------------------------------------------------------------------------------------//
class UserPrivileges {

	private $login = NULL;
	private $privileges = NULL;

	public function UserPrivileges($login) {
		$this->login = $login;
		$this->privileges = new Vector();
	}

	public function addPrivilege($privilege) {
		if (!$this->contains($privilege)) {
			$this->privileges->add($privilege);
		}
	}

	public function can($privilege) {
		return $this->contains($privilege);
	}

	private function contains($privilege) {
		$priv_array = $this->privileges->toArray();
		foreach ($priv_array as $p) {
			if ($p == $privilege) {
				return true;
			}
		}
		return false;
	}

	public function getLogin() {
		return $this->login;
	}

	public function toString() {
		$buffer = "";
		$priv_array = $this->privileges->toArray();
		foreach ($priv_array as $p) {
			$buffer .= "[" . $p  . "]";
		}
		return $buffer;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ObjectValidatorException
//--------------------------------------------------------------------------------------------------------------------//
class ObjectValidatorException {
	private $id;
	private $description;

	public function ObjectValidatorException($id, $description) {
		$this->id = $id;
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlFormHelper
//--------------------------------------------------------------------------------------------------------------------//

class HtmlFormHelper {

	public static function initFormFormObjectWithErrors($form, $object, $errors) {
		$clazz = new ReflectionClass(get_class($object));
		$methods = $clazz->getMethods();
		for ($i = 0; $i < count($methods); $i++) {
			$method = $methods[$i];
			if (StringFunctions::startsWith($method->getName(), "get") && $method->getNumberOfParameters() == 0) {
				$variableName = $method->getName();
				$variableName = strtolower($variableName);
				$variableName = substr($variableName, 3);
				$r = NULL;

				$r = $method->invoke($object, array());
				if ($r != NULL) {
					$value = $r;
					$f = $form->getHtmlElement($variableName);
					if ($f != NULL) {
						if ($f instanceof HtmlFormElement) {
							$ff = $f;
							$ff->setValue($value);
						}
					}
				}

				if (HtmlFormHelper::hasErrorMessage($variableName, $errors)) {
					if ($form instanceof FormInAStyledBox) {
						$box = $form;
						$box->createErrorMessage($variableName . "_error", HtmlFormHelper::getErrorMessage($variableName, $errors));
					}
				}
				else if (HtmlFormHelper::hasVisibleErrorMessage($variableName, $errors)) {
					if ($form instanceof FormInAStyledBox) {
						$box = $form;
						$box->createErrorMessage($variableName . "_visible_error", HtmlFormHelper::getErrorMessage($variableName
						. "_visible", $errors));
					}
				}
			}
			else if (StringFunctions::startsWith($method->getName(), "is") && $method->getNumberOfParameters() == 0) {
				$variableName = $method->getName();
				$variableName = strtolower($variableName);
				$variableName = substr($variableName, 3);
				$r = NULL;

				$r = $method->invoke($object, array());
				$value = $r;
				$f = $form->getHtmlElement($variableName);
				if ($f != NULL) {
					if ($f instanceof HtmlFormElement) {
						$ff = $f;
						$ff->setValue($value);
					}
				}

				if (HtmlFormHelper::hasErrorMessage($variableName, $errors)) {
					if ($form instanceof FormInAStyledBox) {
						$box = $form;
						$box->createErrorMessage($variableName . "_error", HtmlFormHelper::getErrorMessage($variableName, $errors));
					}
				}
				else if (HtmlFormHelper::hasVisibleErrorMessage($variableName, $errors)) {
					if ($form instanceof FormInAStyledBox) {
						$box = $form;
						$box->createErrorMessage($variableName . "_visible_error", HtmlFormHelper::getErrorMessage($variableName
						. "_visible", $errors));
					}
				}
			}
		}
	}

	private static function hasVisibleErrorMessage($variableName, $errors) {
		return HtmlFormHelper::hasErrorMessage($variableName . "_visible", $errors);
	}

	public static function getErrorMessage($variableName, $errors) {
		$err_array = $errors->toArray();
		foreach ($err_array as $exception) {
			if ($variableName == $exception->getId()) {
				return $exception->getDescription();
			}
		}
		return "Error";
	}

	public static function hasErrorMessage($variableName, $errors) {
		$err_array = $errors->toArray();
		foreach ($err_array as $exception) {
			if ($variableName == $exception->getId()) {
				return true;
			}
		}
		return false;
	}

	public static function initFormFromObject($form, $obj) {
		$clazz = new ReflectionClass(get_class($obj));
		$methods = $clazz->getMethods();

		for ($i = 0; $i < count($methods); $i++) {
			$method = $methods[$i];
			if (StringFunctions::startsWith($method->getName(), "get") && $method->getNumberOfParameters() == 0) {
				$variableName = $method->getName();
				$variableName = strtolower($variableName);
				$variableName = substr($variableName, 3);

				$r = NULL;

				$r = $method->invoke($obj, array());
				if ($r != NULL) {
					$value = $r;
					if(!is_object($value)){
					}
					$f = $form->getHtmlElement($variableName);
					if ($f != NULL) {
						if ($f instanceof HtmlFormElement) {
							$ff = $f;

							$ff->setValue($value);
							$attr = $ff->attributes;
						}
					}
				}
			}
			else if (StringFunctions::startsWith($method->getName(), "is") && $method->getNumberOfParameters() == 0) {
				$variableName = $method->getName();
				$variableName = strtolower($variableName);
				$variableName = substr($variableName, 4);

				$r = NULL;
				$r = $method->invoke($obj, array());
				$value = $r;
				$f = $form->getHtmlElement($varibleName);
				if ($f != NULL) {
					if ($f instanceof HtmlFormElement) {
						$ff = $f;
						$ff->setValue($value);
					}
				}
			}
		}
	}

	public static function setFormEditFromObject($form, $object) {
		try {
			$autoDescriptor = $object;
			$daoDescriptor = $autoDescriptor->getTableDescriptor();
			$findFields = $daoDescriptor->getFindFields();
			foreach ($findFields as $comtorJDBCField) {
				$fieldName = $comtorJDBCField->getFieldName();

				$htmlInput = $form->getHtmlElement($fieldName);
				if ($htmlInput != NULL) {
					$htmlInput->setReadOnly(false);
				}
			}
		}
		catch (Exception $e) {
			return;
		}
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: RequestHelper
//--------------------------------------------------------------------------------------------------------------------//
class RequestHelper {

	public static function  initObjectFromRequest($obj) {
		$clazz = new ReflectionClass(get_class($obj));
		$methods = $clazz->getMethods();
		$errors = new Vector();

		for ($i = 0 ; $i < count($methods); $i++){
			$method = $methods[$i];

			// the method name starts with set (is a setter) and the number of parameters is 1
			if (StringFunctions::startsWith($method->getName(), "set") && $method->getNumberOfParameters() == 1) {
				$variableName = $method->getName();
				$variableName = strtolower($variableName);
				$variableName = substr($variableName, 3);

				$argumentos = array();
				try {
					if (isset($_REQUEST[$variableName])){
						$parameter = $_REQUEST[$variableName];
							
						if ($parameter == NULL){

						}
						else {
							//$argumentos[0] = $parameter;
							//$method->invoke($obj, $argumentos);
							$method->invoke($obj, $parameter);
						}
					}

				} catch (Exception $e) {

					$errors->add("Error en el formato " . $varibleName);
				}
			}
		}

		return $errors;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorObjectListFilter
//--------------------------------------------------------------------------------------------------------------------//
class ComtorObjectListFilter {

	private $id;
	private $value;

	public function ComtorObjectListFilter($id, $value) {
		$this->id = $id;
		$this->value = $value;
	}

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function getValue() {
		return $this->value;
	}

	public function setValue($value) {
		$this->value = $value;
	}

	public function toString() {
		return "[" . $this->getId() . ":" . $this->getValue() . "]";
	}

}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ComtorAdministratorControllerHelper
//--------------------------------------------------------------------------------------------------------------------//
class ComtorAdministratorControllerHelper {

	public  static function continueFormMessage($baseUrl, $message) {
		return ComtorAdministratorControllerHelper::continueForm($baseUrl, $message)->getHtml();
	}

	public static function continueForm($baseUrl, $message) {
		$form = new FormInAStyledBox("Info", $baseUrl, "POST");
		$form->addRowInOneCell(new HtmlText($message, false));
		$form->addButton("continue", "Continue");
		return $form;
	}

	public static function continueFormBigMessage($baseUrl, $message, $option = NULL, $action = NULL, $key = NULL) {
		return continueBigForm($message, $option, $action, $key)->getHtml();
	}

	public static function continueBigForm($message, $option, $action, $key) {
		$form = new FormInAStyledBox("Info", "?option="  . option . "&action="  . action . "&key=" . key, "POST");
		$form->addRowInOneCell(new HtmlText($message, false));
		$form->addButton("continue", "Continue");
		return $form;
	}

	public static function getGenericUploadFileForm($baseUrl) {
		$form = new HtmlForm($baseUrl, "POST");
		$form->addAttribute("enctype", "multipart/form-data");
		$form->addElement(new HtmlText("File:"));
		$form->addElement(new HtmlInputFile("upload_file"));
		$form->addElement(new HtmlInputHidden("action", "upload"));
		$form->addElement(new HtmlButton(HtmlButton::SUBMIT_BUTTON, "upload_button", "Enviar"));
		return $form;
	}

	public static function getGenericFilterForm($baseUrl, $filter) {
		$form = new HtmlForm($baseUrl,"POST");
		$form->addElement(new HtmlText("Filter:"));
		$form->addElement(new HtmlInputText("filter",$filter,25,25));
		return $form;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// CLASES FORM & POST
//--------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------//
// Clase: AbstractComtorFormValidateAction
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorFormValidateAction implements ComtorFormValidateAction {

	private $form;
	private $option;
	private $finishUrl;

	public function AbstractComtorFormValidateAction($option) {
		$this->option = $option;
	}

	public function fillFormFromRequest() {


		$parameterNames = array_keys($_REQUEST);
		foreach ($parameterNames as $paramName) {
			$htmlElement = $this->form->getHtmlElement(strtolower($paramName));
			if ($htmlElement != NULL && ($htmlElement instanceof HtmlFormElement)) {
				$paramValue = $_REQUEST[$paramName];

				$htmlElement->setValue($paramValue);
			}
		}
	}

	public function fillFormFromRequestWithErrors($errors) {


		$parameterNames = array_keys($_REQUEST);
		foreach ($parameterNames as $paramName) {
			$htmlElement = $this->form->getHtmlElement(strtolower($paramName));
			if ($htmlElement != NULL && ($htmlElement instanceof HtmlFormElement)) {
				$paramValue = $_REQUEST[$paramName];
				$htmlElement->setValue($paramValue);
				if (HtmlFormHelper::hasErrorMessage($paramName, $errors)) {
					$form->createErrorMessage($paramName . "_error", HtmlFormHelper::getErrorMessage($paramName, $errors));
				}
			}
		}
	}

	public function getForm() {
		return $this->form;
	}

	public function getEmptyForm() {
		return ($form = $this->createForm());
	}

	protected abstract function createForm();

	public function getOption() {
		return "?option=" . $this->option;
	}

	public function getFinishUrl() {
		return $this->finishUrl;
	}

	public function setFinishUrl($finishUrl) {
		$this->finishUrl = $finishUrl;
	}

	public function processView(){
		return "Process View";
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: ComtorFormValidateAction
//--------------------------------------------------------------------------------------------------------------------//
interface ComtorFormValidateAction {

	public function getEmptyForm();
	public function fillFormAtInit();
	public function fillFormFromRequest();
	public function validateRequest();
	public function processForm();
	public function getFinishUrl();
	public function setFinishUrl($finishUrl);
	public function fillFormFromRequestWithErrors($errors);
	public function processView();
}

//--------------------------------------------------------------------------------------------------------------------//
// Clase: AbstractComtorReportFormAction
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorReportFormAction extends AbstractComtorFormValidateAction {

	public function AbstractComtorReportFormAction($factory_option) {
		if (is_string($factory_option)) {
			$this->AbstractComtorFormValidateAction($factory_option);
		} else if (is_object($factory_option)) {
			$this->AbstractComtorFormValidateAction(get_class($factory_option));
		} else {
			$this->AbstractComtorFormValidateAction("ERROR");
		}
	}

	public function fillFormAtInit() {
		// -----------------------------------------------------//
		// EMPTY METHOD
		// -----------------------------------------------------//
	}

	public function processForm(){
		$container = new HtmlContainer();

		$toolBar = $this->getStyledToolBar();
		if ($toolBar != NULL) {
			$container->addElement($toolBar);
		}

		$container->addElement("<br>");

		$table = $this->buildTable();
		$container->addElement($table);

		return $container->getHtml();
	}

	private function buildTable() {
		$headers = $this->getTableHeaders();
		$objectList = $this->buildObjectListFromForm();
		$content = $this->toContent($objectList);
		$table = new ComtorReportHtmlTable($headers, $content);
		return $table;
	}

	private function toContent($objectList) {
		$content = new Vector();
		$obj_array = $objectList->toArray();
		foreach ($obj_array as $object) {
			$row = $this->buildTableRow($object);

			$content->add($row);
		}
		return $content;
	}

	// ----------------------------------------------------------------------------------------------------------------//
	// ABSTRACT METHODS
	// ----------------------------------------------------------------------------------------------------------------//

	protected abstract function buildTableRow($object);
	protected abstract function getTableHeaders();
	protected abstract function buildObjectListFromForm();
	protected abstract function getStyledToolBar();
}

//--------------------------------------------------------------------------------------------------------------------//
// Clase: ComtorReportHtmlTable
//--------------------------------------------------------------------------------------------------------------------//
class ComtorReportHtmlTable extends StyledBigTable {

	private $headers;
	private $content;

	public function ComtorReportHtmlTable($headers, $content) {
		$this->StyledBigTable();
		$this->headers = $headers;
		$this->content = $content;
		$this->buildReport();
	}

	private function buildReport() {
		$this->buildHeaders();
		$this->buildContent();
	}

	private function buildHeaders() {
		$vector = new Vector();
		$array_headers = $this->headers->toArray();
		foreach ($array_headers as $header) {
			$vector->add($header);
		}
		$this->setHeaders($vector);
	}

	private function buildContent() {
		$array_content = $this->content->toArray();
		foreach ($array_content as $contentRow) {
			$this->addRow($contentRow);
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Clase: ComtorFormValidateActionPageFactory
//--------------------------------------------------------------------------------------------------------------------//
abstract class ComtorFormValidateActionPageFactory extends HtmlGuiInterface {

	public function getHtml() {
		$viewer = new ComtorActionViewer($this->getFormAction());
		$html = new HtmlContainer();
		$html->addElement($viewer);
		return $html->getHtml();
	}

	public abstract function getFormAction();
}

//--------------------------------------------------------------------------------------------------------------------//
// Clase: ComtorActionViewer
//--------------------------------------------------------------------------------------------------------------------//
class ComtorActionViewer extends ComtorAbstractViewer {

	private $formAction = null;

	public function ComtorActionViewer($formAction) {
		$this->ComtorAbstractViewer(NULL);
		$this->init($formAction);
	}

	protected function init($formAction) {
		$this->formAction = $formAction;
	}

	public function getHtml() {
		// Hay que averiguar si se presiono el boton ok

		if (!isset($_REQUEST["ok"])) {
			return $this->processDefault();
		} else {
			if(isset($_REQUEST["action"])){
				$action = $_REQUEST["action"];
				if ($action != NULL) {
					if ($action == "view") {
						return $this->processView();
					}
				}
			}
			return $this->processForm();
		}
	}

	protected function processView() {
		try {
			return $this->formAction->processView();
		}
		catch (ComtorException $exception) {
			return $exception->getMessage();
		}
	}

	private function processForm() {
		try {
			$errors = $this->formAction->validateRequest();
			if ($errors->size() > 0) {
				$emptyForm = $this->formAction->getEmptyForm();
				$this->formAction->fillFormAtInit();
				$this->formAction->fillFormFromRequestWithErrors($errors);
				return $emptyForm->getHtml();
			}
			return $this->formAction->processForm();
		}
		catch (ComtorException $exception) {
			return $exception->getMessage();;
		}
	}

	private function processDefault() {
		$emptyForm = $this->formAction->getEmptyForm();
		$this->formAction->fillFormAtInit();
		return $emptyForm->getHtml();
	}
}



//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlCodesFinder
//--------------------------------------------------------------------------------------------------------------------//
class HtmlCodesFinder extends HtmlContainer implements HtmlFormElement {

	private $text = NULL;

	public function HtmlCodesFinder($formName, $name_visible, $name_hidden, $include, $clazz, $parameter = "",
	$helperLink = "", $textHelperLink = "", $urlIcon = "") {
		$this->HtmlContainer();
		$this->init($formName, $name_visible, $name_hidden, $include, $clazz, $parameter, $helperLink, $textHelperLink, $urlIcon);
	}

	private function init($formName, $name_visible, $name_hidden, $include, $clazz, $parameter,
	$helperLink, $textHelperLink, $urlIcon) {
		$this->text = $this->addElement(new HtmlInputText($name_visible));
		$this->text->addAttribute("size", "50");
		$this->text->addAttribute("readonly", NULL);
		$htmlLink = new HtmlLink("", "", "images/ico_search.gif");
		$htmlLink->addAttribute("onclick", "findValues('" . $formName . "','" . $name_visible . "','" . $name_hidden . "','"
		. $clazz . "', '" . $parameter . "', '" . $include . "'); return false;");
		$this->addElement($htmlLink);
		if (trim($helperLink) != "") {
			$helpLink = $this->createHelpLink($helperLink, $textHelperLink, $urlIcon);
			$this->addElement($helpLink);
		}
	}

	private function createHelpLink($helperLink, $textHelperLink, $urlIcon) {
		if (trim($textHelperLink) == "") {
			$textHelperLink = "Create";
		}
		$htmlLink = NULL;
		if (trim($urlIcon) != "") {
			$htmlLink = new HtmlLink("", "", $urlIcon);
		}
		else {
			$htmlLink = new HtmlLink("", "");
		}
		$htmlLink->addAttribute("titile", $textHelperLink);
		$htmlLink->addAttribute("onclick", "goToHelpLink('" . helperLink . "');return false;");
		return $htmlLink;
	}

	public function getName() {
		return $this->text->getName();
	}

	public function getValue() {
		return $this->text->getValue();
	}

	public function setEnable($enable) {
		$this->text->setEnable($enable);
	}

	public function setName($name) {
		$this->text->setName($name);
	}

	public function setValue($value) {
		$this->text->setAttribute("value", $value);
	}

	public function addAttribute($atr, $value) {
		$this->text->addAttribute($atr, $value);
	}

}

//--------------------------------------------------------------------------------------------------------------------//
// Class: AbstractComtorFinderFactory
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorFinderFactory extends HtmlGuiInterface {

	private $parameter = NULL;

	public abstract function getFacade();

	public function getHtml() {
		try {
			$container = new HtmlContainer();
			$container->addElement($this->createFilterForm());
			$container->addElement("<br>");
			$container->addElement($this->createStyledBigTable());
			return $container->getHtml();
		}
		catch (BusinessLogicException $e) {
			return $e->getMessage();
		}
	}

	protected function createFilters() {
		$filters = new Vector();
		$keywords = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : "";
		$filters->add(new ComtorObjectListFilter("filter", $keywords));
		if ($this->parameter != NULL) {
			$filters->add(new ComtorObjectListFilter("parameter", $this->parameter));
		}
		return $filters;
	}

	protected function createFilterForm() {
		$keyword = isset($_REQUEST["keyword"]) ? $_REQUEST["keyword"] : NULL;
		$keyword = ($keyword == NULL) ? "" : $keyword;
		$form = new HtmlForm(NULL, "POST");
		$form->addElement(new HtmlText("Filter:"));
		$form->addElement(new HtmlInputText("keyword", $keyword));
		return $form;
	}

	protected function getFinderHeaders() {
		$resp = $this->getHeaders();
		$resp->add("");
		return $resp;
	}

	protected abstract function getHeaders();

	protected function createStyledBigTable(){
		$styledTable = new StyledBigTable();

		$data = $this->getFacade()->getObjectsListForFinder($this->createFilters());

		$styledTable->setHeaders($this->getFinderHeaders());
		$data_array = $data->toArray();
		foreach ($data_array as $object) {
			$row = $this->getRow($object);
			$link = $this->getLink($object);
			$row->add($link->getHtml());
			$styledTable->addRow($row);
		}
		return $styledTable;
	}

	protected function getLink($object) {
		$link = new HtmlLink("Select", "");
		$link->addAttribute("onclick", "parent.close(); insert_title('" . $this->getTitleId($object) . "'); return false;");
		return $link;
	}

	protected abstract function getTitleId($object);

	protected abstract function getRow($object);

	public function requireComtorSession() {
		return true;
	}

	public function getParameter() {
		return $this->parameter;
	}

	public function setParameter($parameter) {
		$this->parameter = $parameter;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: AbstractComtorFindersFactory
//--------------------------------------------------------------------------------------------------------------------//
abstract class AbstractComtorFindersFactory extends AbstractComtorFinderFactory {

	protected function getLink($object) {
		$onclick = "parent.close(); insert_values('" . $this->getValueToShow($object) . "','"
		. $this->getValueToHide($object) . "'); return false;";

		$link = new HtmlLink("Select", "", NULL, $onclick);
		return $link;
	}

	protected abstract function getValueToHide($object);

	protected abstract function getValueToShow($object);
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: CodeFinderFactory
//--------------------------------------------------------------------------------------------------------------------//
class CodeFinderFactory extends HtmlGuiInterface {

	private $option;

	public function setOption($option){
		$this->option = $option;
	}

	public function getHtml() {
		$finder_name = (isset($_REQUEST["finder_name"])) ? $_REQUEST["finder_name"] : "";
		$finder_parameter = (isset($_REQUEST["finder_parameter"])) ? $_REQUEST["finder_parameter"] : "";

		try {
			$clazz = new ReflectionClass($finder_name);
			$guiInterface = $clazz->newInstance();
			if ($guiInterface instanceof AbstractComtorFindersFactory) {
				$guiInterface->setParameter($finder_parameter);
			}
			return $guiInterface->getHtml();
		}
		catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function requireComtorSession() {
		return true;
	}
}

?>
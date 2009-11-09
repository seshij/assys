<?php
include_once("adodb/adodb.inc.php" );
include_once("adodb/adodb-exceptions.inc.php");

/**
 * Variable global para administrar las conexiones hechas a la base de datos
 * @global integer $daoMgrInstance
 */
global $daoMgrInstance;
$daoMgrInstance = NULL;

/**
 * DESCRIPCION DE LA CLASE QUE ADMINISTRA LA CONEXION A LA BASE DE DATOS
 * @name DaoMgr
 * @author comtor
 * @package comtor
 */
class DaoMgr {

	var $daos;
	/**
	 * Constructor
	 */
	function DaoMgr(){
		$daos = array();
		//$this->daos["mysql"] = new Dao("mysql", "127.0.0.1", "ControlEntregas", "calabaza","ControlEntregas");
		$this->daos["mysql"] = new Dao("mysql", "localhost", "userkbgames", "kr4k4key","kbgamesdb");
                //$this->daos["mysql"] = new Dao("mysql", "localhost", "dbxad_userkbgame", "kr4k4key","dbxadmin_kbgames");
	}

	/**
	 * obtiene una apuntador a instancia de la conexion. es decir que la modifica directamente
	 */
	function &getInstance(){
		global $daoMgrInstance;
		if($daoMgrInstance == NULL){
			$daoMgrInstance = new DaoMgr();
		}
		return $daoMgrInstance;
	}

	/**
	 * obtiene una apuntador a la conexion ADO con mysql. es decir que la modifica directamente
	 */
	function &getDao($daoType="mysql"){
		$daoMgr = DaoMgr::getInstance();
		$dao =  $daoMgr->daos[$daoType];
		return $dao;
	}

}

/**
 * CLASE QUE ADMINISTRA LA CONEXION ADO A LA BASE DE DATOS
 * @name Dao
 * @author comtor
 * @package comtor
 */
class Dao {
	/**
	 * @var driver utilizado para la conexi�n
	 */
	var $dbDriver;
	var $dbHost;
	var $dbUser;
	var $dbPassword;
	var $dbName;
	var $dbErrors;

	/**
	 * Constructor
	 * @param $dbUser usuario de conexi�n
	 */
	function Dao($dbDriver, $dbHost, $dbUser, $dbPassword, $dbName){
		$this->dbDriver = $dbDriver;
		$this->dbHost = $dbHost;
		$this->dbUser = $dbUser;
		$this->dbPassword = $dbPassword;
		$this->dbName = $dbName;
		$this->dbErrors = "";
	}

	/**
	 * SE CONECTA A LA BASE DE DATOS
	 */
	function &connect(){
		$db = ADONewConnection($this->dbDriver);
		$db->Connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
		return $db;
	}

	/**
	 * Ejecuta una sentencia sql en la base de datos
	 */
	function execute($sql){
		//echo "sql_execute: ".$sql;
		$db = $this->connect();
		$this->dbErrors = "";
		try{
			$esto=$db->Execute($sql);
		}catch(exception $e){
			throw new Exception("Error ejecutando sql ". $e->getMessage());
			//echo "Error en base de datos: ".$e->getMessage();
		}
		/*
		 if ($db->Execute($sql) === false) {
			echo "<!-- Error ejecutando SQL: ".$db->ErrorMsg()."-->";
			echo "<!-- SQL: ".$sql."-->";
			$this->dbErrors = $db->ErrorMsg();
			}*/
	}

	/**
	 * Ejecuta una sentencia sql en la base de datos
	 */
	function executeQuery($sql){
//echo "sql:".$sql;
		$db = $this->connect();

		$db->SetFetchMode(ADODB_FETCH_ASSOC);

		try{
			$rs = $db->Execute($sql);
			$this->dbErrors = "";
			if (!$rs){
				echo "<!-- Error ejecutando SQL: ".$db->ErrorMsg()."-->";
				$this->dbErrors = $db->ErrorMsg();
			}
			//else if ($rs === FALSE){
			//    echo "<!-- Error ejecutando SQL: ".$db->ErrorMsg()."-->";
			//}
			else{
				//var_dump($rs->GetRows());
				return $rs->GetRows();
			}
		}catch(exception $e){
			//echo $e->getMessage();
			throw new Exception("Error ejecutando sql ". $e->getMessage());
		}

	}

	/**
	 * Realiza una incercion en una tabla
	 */
	function insert($elem, $td){
		$fieldNames = $td->getDbFieldNames();
		$strFields = "";
		$strValues = "";
		$isFirst = true;
		foreach($fieldNames as $fieldName){
			$dbField = $td->getField($fieldName);
			if ( $dbField->isInsertable() ){
				if ( !$isFirst ) {
					$strFields .= ", ";
					$strValues .= ", ";
				}else {
					$isFirst = false;
				}
					
				$strFields .= $dbField->getFieldName();
				$strValues .= $dbField->getValue($elem);
			}
		}
		$sql = "insert into ".$td->getTableName()." (".$strFields.") values (".$strValues.")";
		//echo "<br>insert sql <br>".$sql."<br>";
		$this->execute($sql);
	}

	/**
	 * Realiza una actualizacion a un objeto
	 */
	function update($elem, $td){
		$strFindableFields = $this->getFindableWhere($elem, $td);
		$strUpdateableFields = $this->getUpdateable($elem, $td);

		$sql = "update ".$td->getTableName().
			" set ". $strUpdateableFields.
			" where ". $strFindableFields;
			//echo "sql: ".$sql;
		$this->execute($sql);
	}

	/**
	 * Borra un registro de la base de datos
	 */
	function delete($elem, $td){
		$strFields = $this->getFindableWhere($elem, $td);
		$strFields = trim($strFields);
		$sql = "delete from ".$td->getTableName();
		if(strlen($strFields) > 0){
			$sql .= " where ". $strFields;
		}
		
		$this->execute($sql);
	}

	/**
	 * Obtiene un objeto a partir de un query
	 */
	function getObject($elem, $resultRow){
		$newElem = $elem;
		$td = $newElem->getTableDescriptor();
		$fieldNames = $td->getDbFieldNames();
		foreach($fieldNames as $fieldName){
			$dbField = $td->getField($fieldName);
			$objFieldName = $dbField->getName();
			if(isset($resultRow[$fieldName])){
				$newElem->$objFieldName = $resultRow[$fieldName];
			}
		}
		return $newElem;
	}

	/**
	 * Carga un objeto con datos de un query
	 */
	function load($elem, $td){
		$strFields = $this->getFindableWhere($elem, $td);
		$sql = "select * from ".$td->getTableName()." where ".$strFields;
		$result = $this->executeQuery($sql);

		if(count($result) <= 0){
			return null;
		}
		else{
			return $this->getObject($elem, $result[0]);
		}
	}

	/**
	 * Obtiene un elemento filtrado por una condicion where
	 */
	function &getFindableWhere($elem, $td) {
		$fieldNames = $td->getDbFieldNames();
		$strFields = "";
		$isFirst = true;
		foreach($fieldNames as $fieldName){
			$dbField = $td->getField($fieldName);
			if ( $dbField->isFindable() ){
				if ( !$isFirst ) {
					$strFields .= " and ";
				}else {
					$isFirst = false;
				}
					
				$strFields .= $dbField->getFieldName();
				$strFields .= "=";
				$strFields .= $dbField->getValue( $elem );
			}
		}
		return $strFields;
	}

	/**
	 * Actualiza un objeto con datos de un query
	 */
	function &getUpdateable($elem, $td) {
		$fieldNames = $td->getDbFieldNames();
		$strFields = "";
		$isFirst = true;
		foreach($fieldNames as $fieldName){
			$dbField = $td->getField($fieldName);
			if ( $dbField->isUpdateable() ){
				if ( !$isFirst ) {
					$strFields .= ", ";
				}else {
					$isFirst = false;
				}
					
				$strFields .= $dbField->getFieldName();
				$strFields .= "=";
				$strFields .= $dbField->getValue( $elem );
			}
		}
		return $strFields;
	}

	/**
	 * Obtiene el ultimo registro insertado
	 */
	function &lastInsertId($td){
		$sql = "SELECT MAX(id) as id FROM ".$td->table;
		$result = $this->executeQuery($sql);
		if(count($result) <= 0){
			return 0;
		}else{
			return intval($result[0]['id']);
		}

	}

	/**
	 * Obtiene el ultimo campo($field) insertado
	 */
	function &lastInsertField($table, $field){
		$sql = "SELECT MAX(".$field.") as id FROM ".$table;
		$result = $this->executeQuery($sql);
		if(count($result) <= 0){
			return 0;
		}
		else{
			$last = intval($result[0]['id']);
			return $last;
		}

	}

	/**
	 * inicializa un objeto a partir de una fila
	 */
	function &initObjectFromRow(&$elem, $td, $row){
		$fieldNames = $td->getDbFieldNames();
		foreach($fieldNames as $fieldName){
			$dbField = $td->getField($fieldName);
			$objFieldName = $dbField->objFieldName;
			$elem->$objFieldName = $row[$dbField->dbFieldName];
		}
	}
}

/**
 * CLASE QUE ADMINISTRA los datos de una tabla de la base de datos
 * @name TableDescriptor
 * @author comtor
 * @package comtor
 */
class TableDescriptor {
	/**
	 * nombre de la tala
	 * @var string
	 */
	var $table;
	/**
	 * arreglo de los campos de la tabla
	 * @var array
	 */
	var $dbFields;

	/**
	 * Constructor
	 * @return array
	 */
	function TableDescriptor($table){
		$this->table = $table;
		$this->dbFieldNames = array();
	}

	/**
	 * Obtiene el nombre de la tabla
	 * @return string
	 */
	function &getTableName(){
		return $this->table;
	}

	/**
	 * Adiciona un campo a la tabla
	 * @return DBField
	 */
	function addField($objFieldName, $dbFieldName, $quoted = false){
		$this->dbFields[$dbFieldName] = new DBField($objFieldName, $dbFieldName,$quoted);
	}

	/**
	 * Obtiene los nombres de los campos de la tabla
	 * @return array
	 */
	function getDbFieldNames(){
		return array_keys($this->dbFields);
	}

	/**
	 * Obtiene el valor de un campo a partir de su nombre
	 * @return DBField {$this->dbFields[$dbFieldName]}
	 */
	function &getField($dbFieldName){
		return $this->dbFields[$dbFieldName];
	}

	function setInsertable($fieldName, $insertable){
		$field =& $this->getField($fieldName);
		$field->setInsertable($insertable);
	}

	function setUpdateable($fieldName, $updateable){
		$field =& $this->getField($fieldName);
		$field->setUpdateable($updateable);
	}

	function setFindable($fieldName, $findable){
		$field =& $this->getField($fieldName);
		$field->setFindable($findable);
	}

	// New Method.
	function &getFindFields(){
		$findFields = array();
		$names = $this->getDbFieldNames();
		$limit = count($names);
		for ($i = 0; $i < $limit; $i++){
			$field = $this->getField($names[$i]);
			if ($field->isFindable()) {
				$findFields[] = $field;
			}

		}
		return $findFields;
	}
}

/**
 * CLASE QUE ADMINISTRA el campo de una tabla de la base de datos
 * @name DBField
 * @author comtor
 * @package comtor
 */
class DBField {

	var $objFieldName;
	var $dbFieldName;
	var $help;
	var $quoted;
	var $insertable;
	var $updateable;
	var $selectable;
	var $findable;
	var $visible;

	/**
	 * Constructor
	 */
	function DBField($objFieldName, $dbFieldName, $quoted){
		$this->objFieldName = $objFieldName;
		$this->dbFieldName = $dbFieldName;
		$this->quoted = $quoted;
		$this->help = "";
		$this->insertable = true;
		$this->updateable = true;
		$this->selectable = true;
		$this->findable   = false;
		$this->visible    = false;
	}

	/**
	 * Metodo set para colocar el campo en estado de insercion
	 */
	function setInsertable($insertable){
		$this->insertable = $insertable;
	}

	/**
	 * Metodo set para colocar el campo en estado de actualizacion
	 */
	function setUpdateable($updateable){
		$this->updateable = $updateable;
	}

	/**
	 * Metodo set para colocar el campo en estado de seleccion
	 */
	function setSelectable($selectable){
		$this->selectable = $selectable;
	}

	/**
	 * Metodo set para colocar el campo en estado de busqueda
	 */
	function setFindable($findable){
		$this->findable = $findable;
	}

	/**
	 * Metodo set para colocar el campo en estado de visible o invisible
	 */
	function setVisible($visible){
		$this->visible = $visible;
	}

	/**
	 * Obtiene el nombre de un campo
	 */
	function getFieldName(){
		return $this->dbFieldName;
	}

	/**
	 * Obtiene el nombre de un campo
	 */
	function getName(){
		return $this->objFieldName;
	}

	function isQuoted(){
		return $this->quoted;
	}

	function isInsertable(){
		return $this->insertable;
	}

	function isUpdateable(){
		return $this->updateable;
	}

	function isSelectable(){
		return $this->selectable;
	}

	function isFindable(){
		return $this->findable;
	}

	function isVisible(){
		return $this->visible;
	}

	/**
	 * Obtiene ayuda
	 */
	function getHelp(){
		return $this->help;
	}

	/**
	 * Obtiene el valor de un objeto
	 * @param $elem
	 */
	function getValue($elem){
		$fieldName = $this->getName();
		$val = $elem->$fieldName;
		if (is_array($val)) {
			$val = $val[0];
		}
		if($this->quoted){
			return "'".$val."'";
		}else{
			return $val;
		}
	}
}

/**
 * CLASE que administra un elemento de base de datos. Todas las clases que se conectan
 * a la base de datos deben heredar de esta clase
 * @name DElement
 * @author comtor
 * @package comtor
 */
class DBElement {

	/*
	 * Este metodo debe ser implementado por los objetos que extiendan
	 * la clase DBElement
	 */

	function getTableDescriptor(){
	}
}
?>

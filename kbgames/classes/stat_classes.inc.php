<?
include_once("lib/html.inc.php");
include_once("components/administrable.inc.php");
include_once("lib/dao.inc.php");

class Stat extends DBElement{
	var $id;
	var $ipcliente;
	var $sessionid;
	var $fecha;
	var $hora;
	var $url;
	var $componente;
	var $descripcion;
	var $browser;
	var $referer;
	var $pais;

	public function getTableDescriptor() {
		$td = new TableDescriptor("stat");
		$td->addField("id","id",false);
		$td->addField("ipcliente","ipcliente",true);
		$td->addField("sessionid","sessionid",true);
		$td->addField("fecha","fecha",true);
		$td->addField("hora","hora",true);
		$td->addField("url","url",true);
		$td->addField("componente","componente",true);
		$td->addField("descripcion","descripcion",true);
		$td->addField("browser","browser",true);
		$td->addField("referer","referer",true);
		$td->addField("pais","pais",true);

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
	public function getIpcliente() {
		return $this->ipcliente;
	}
	public function setIpcliente($ipcliente) {
		$this->ipcliente = $ipcliente;
	}
	public function getSessionid() {
		return $this->sessionid;
	}
	public function setSessionid($sessionid) {
		$this->sessionid = $sessionid;
	}
	public function getFecha() {
		return $this->fecha;
	}
	public function setFecha($fecha) {
		$this->fecha = $fecha;
	}
	public function getHora() {
		return $this->hora;
	}
	public function setHora($hora) {
		$this->hora = $hora;
	}
	public function getUrl() {
		return $this->url;
	}
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getComponente() {
		return $this->componente;
	}
	public function setComponente($componente) {
		$this->componente = $componente;
	}
	public function getDescripcion() {
		return $this->descripcion;
	}
	public function setDescripcion($descripcion) {
		$this->descripcion = $descripcion;
	}
	public function getBrowser() {
		return $this->browser;
	}
	public function setBrowser($browser) {
		$this->browser = $browser;
	}
	public function getReferer() {
		return $this->referer;
	}
	public function setReferer($referer) {
		$this->referer = $referer;
	}
	
	public function getPais() {
		return $this->pais;
	}
	public function setPais($pais) {
		$this->pais = $pais;
	}
}

?>
<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/content_classes.inc.php");

class ContentFEController extends HtmlGuiInterface {
	var $theContainer;
	public function ContentFEController(){
		$this->theContainer=new HtmlContainer();
	}

	public function getHtml() {
		if(isset($_REQUEST['myId'])){
			$this->getContentDetail($_REQUEST['myId']);
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getContentDetail($id){
		$content=new Content();
		
		$content->setId($id);

		$content=DaoMgr::getDao()->load($content,$content->getTableDescriptor());
		
		$laTabla=new HtmlTable();
		$laTabla->setClass("listTable");
		$elTd=$laTabla->addCell($content->getNombre());
		$elTd->setClass("seccionHead");
		$laTabla->nextRow();		
		$elTd=$laTabla->addCell($content->getContenido());
		$elTd->setClass("seccionContent");
		$laTabla->nextRow();
		$this->theContainer->addElement($laTabla);
	}
	

	
}
?>
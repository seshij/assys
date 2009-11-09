<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/news_classes.inc.php");

class NewsFEController extends HtmlGuiInterface {
	var $theContainer;
	public function NewsFEController(){
		$this->theContainer=new HtmlContainer();
	}

	public function getHtml() {
		if(isset($_REQUEST['myId'])){
			$this->getNewsDetail($_REQUEST['myId']);
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getNewsDetail($id){
		$content=new News();
		$content->setId($id);

		$content=DaoMgr::getDao()->load($content,$content->getTableDescriptor());
		
		$laTabla=new HtmlTable();
		$laTabla->setClass("listTable");
		$elTd=$laTabla->addCell($content->getTitle());
		$elTd->setClass("seccionHead");
		$laTabla->nextRow();		
		$elTd=$laTabla->addCell($content->getContent());
		$elTd->setClass("seccionContent");
		$laTabla->nextRow();
		$this->theContainer->addElement($laTabla);
	}
	

	
}
?>
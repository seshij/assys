<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/categoria_classes.inc.php");

class CategoriaFEController extends HtmlGuiInterface {
	var $theContainer;

	public function CategoriaFEController(){
		$this->theContainer=new HtmlContainer();
	}

	public function getHtml() {
		if(isset($_SESSION['lang'])){
			$this->getCategoryList($_SESSION['lang']);
		}else{
			$this->getCategoryList("eng");
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getCategoryList($lang){
		$query="select * from category ORDER by nombre ";
		$bigtable=new HtmlTable();
		$bigtable->setClass("listTable");
		$seccionTD=$bigtable->addCell("NUESTROS PRODUCTOS");
		$seccionTD->setClass("seccionHead");
		$bigtable->nextRow();
		$contentTr=$bigtable->getCurrentRow();
		$contentTr->setClass("content");
		$rows = DaoMgr::getDao()-> executeQuery($query);
		$vector = new Vector();
		$i=1;

		foreach ($rows as $row){
			$new = new Categoria();
			$new = DaoMgr::getDao()->getObject($new, $row);
			$catNombre=$new->getNombre();
			$catId=$new->getId();
			$catLink=new HtmlLink($catNombre,"?option=subcategoria&parent=$catId", "images/frontend/puntos2.png");
			$catLink->setClass("listlink");
			$elTd=$bigtable->addCell($catLink);
			$elTd->setClass("linkcell");
			$bigtable->nextRow();
			
		}
		
		$this->theContainer->addElement($bigtable);
	}
}

?>
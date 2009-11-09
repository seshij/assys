<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/subcategoria_classes.inc.php");

class SubcategoriaFEController extends HtmlGuiInterface {
	var $theContainer;
	var $myFather;
	var $myFatherName;

	public function SubcategoriaFEController($father){
		$this->theContainer=new HtmlContainer();
		$this->myFather=$father;
		$bd=DaoMgr::getDao()->executeQuery("select nombre from category where id=".$father);
		$this->myFatherName=$bd[0]['nombre'];
	}

	public function getHtml() {
		
			$this->getSubcategoryList();
		
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getSubcategoryList(){

		$query="select * from subcategory WHERE cat_id=".$this->myFather." ORDER by nombre ";

		$bigtable=new HtmlTable();
		$bigtable->setClass("listTable");
		$seccionTD=$bigtable->addCell($this->myFatherName);
		$seccionTD->setClass("seccionHead");
		$bigtable->nextRow();
		
		$rows = DaoMgr::getDao()-> executeQuery($query);
		$vector = new Vector();
		foreach ($rows as $row){
			$new = new Subcategoria();
			$new = DaoMgr::getDao()->getObject($new, $row);
			$catNombre=$new->getNombre();
			$catId=$new->getId();
			$catLink=new HtmlLink($catNombre,"?option=producto&parent=$catId","images/frontend/puntos2.png");
			$catLink->setClass("listlink");
			$elTd=$bigtable->addCell($catLink);
			$elTd->setClass("linkcell");
			$bigtable->nextRow();

		}

		$this->theContainer->addElement($bigtable);
	}
}
?>
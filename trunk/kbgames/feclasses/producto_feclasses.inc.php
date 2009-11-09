<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/producto_classes.inc.php");

class ProductoFEController extends HtmlGuiInterface {
	var $theContainer;
	var $parent;

	public function ProductoFEController($myFather){
		$this->theContainer=new HtmlContainer();
		$this->parent=$myFather;
	}

	public function getHtml() {
		if(isset($_REQUEST['myId'])){
			$this->getProductoDetail($_REQUEST['myId']);
		}else{
			$this->getProductList();
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getProductoDetail($id){
		$prod=new Producto();
		$prod->setId($id);

		$prod=DaoMgr::getDao()->load($prod,$prod->getTableDescriptor());
		
		$laTabla=new HtmlTable();
		$laTabla->setClass("listTable");
		$elTd=$laTabla->addCell($prod->getNombre());
		$elTd->setClass("seccionHead");
		$laTabla->nextRow();		
		$elTd=$laTabla->addCell($prod->getDescripcion());
		$elTd->setClass("seccionContent");
		$laTabla->nextRow();
		$this->theContainer->addElement($laTabla);
	}
	
	public function getProductList(){
		$rows=DaoMgr::getDao()->executeQuery("select * from producto where subcat_id=".$this->parent);
		$laTabla=new HtmlTable();
		$laTabla->setClass("listTable");
		$elTd=$laTabla->addCell($this->getParentName());
		$elTd->setClass("seccionHead");
		$laTabla->nextRow();
		foreach($rows as $row){
			$catLink=new HtmlLink($row['nombre'],"?option=producto&parent=".$this->parent."&myId=".$row[id], "images/frontend/puntos2.png");
			$elTd=$laTabla->addCell($catLink);
			$catLink->setClass("listlink");
			$elTd->setClass("linkcell");
			$laTabla->nextRow();
		}
		$this->theContainer->addElement($laTabla);
		
	}
	
	public function getParentName(){
		$rows=DaoMgr::getDao()->executeQuery("select * from subcategory where id=".$this->parent);
		return $rows[0]['nombre'];
		
	}
	
}
?>
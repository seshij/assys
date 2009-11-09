<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");

class AnunlistFEController extends HtmlGuiInterface {
	var $theContainer;
	var $myFather;
	var $myFatherName;

	public function AnunlistFEController($father){
		$this->theContainer=new HtmlContainer();
		$this->myFather=$father;
		$bd=DaoMgr::getDao()->executeQuery("select nombre from subcategoria where id=".$father);
		$this->myFatherName=$bd[0]['nombre'];
	}

	public function getHtml() {
		if(isset($_SESSION['lang'])){
			$this->getAnunList($_SESSION['lang']);
		}else{
			$this->getAnunList("eng");
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getAnunList($lang){

		//$query="select anunciante.id, anunciante.nombre, anunciante.desc_corta from anunciante LEFT JOIN anuncia_subcat ON anuncia_subcat.subcat_id=".$this->myFather." where anunciante.idioma='".$lang."' order by anunciante.nombre";

		$query=" select id, nombre, desc_corta from anunciante where id in (select anuncia_id from anuncia_subcat where subcat_id=$this->myFather) AND idioma='$lang' ORDER BY nombre ";
		$bigtable=new HtmlTable();
		$catNameTd=$bigtable->addCell($this->myFatherName);
		$catNameTd->setClass("seccionHead");
		$bigtable->nextRow();
		$contentTr=$bigtable->getCurrentRow();
		$contentTr->setClass("content");
		$rows = DaoMgr::getDao()-> executeQuery($query);
		$vector = new Vector();
		$elDiv= new HtmlDiv("anunciaList");
		$elDiv->setClass("anunciantes");
		$newTable = new HtmlTable();
		$newTable->setWidth("95%");
		foreach ($rows as $row){
			$aId=$row['id'];
			$aNombre=$row['nombre'];
			$aDesc=$row['desc_corta'];
			$catLink=new HtmlLink("<strong>".$aNombre."</strong>&nbsp;".$aDesc,"index.php?option=anuncia&myid=".$aId,"images/frontend/mapac.png");
			$catLink->setClass("anunLink");
			$adiv=new HtmlDiv("anun".$aId);
			$adiv->setClass("anunciaDiv");
			$adiv->addElement($catLink);
			$tempTd = $newTable->addCell($adiv);
			$newTable->nextRow();

		}
		$elDiv->addElement($newTable);
		$bigtable->addCell($elDiv);
		$bigtable->nextRow();
		$this->theContainer->addElement($bigtable);
	}
}
?>
<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");


class MSPFEController extends HtmlGuiInterface {
	var $theContainer;

	public function MSPFEController(){
		$this->theContainer=new HtmlContainer();

	}

	public function getHtml() {
		$this->getMSPForm();
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getMSPForm(){
		$bigtable=new HtmlTable();
		$bigtable->setClass("listTable");
		$catNameTd=$bigtable->addCell("MSP");
		$catNameTd->setClass("seccionHead");
		$bigtable->nextRow();

		$rows=DaoMgr::getDao()->executeQuery("select * from ciudades WHERE id IN (SELECT DISTINCT (ciudad) FROM msp) order by nombre");
		foreach($rows as $row){
			$bigtable->addCell($row['nombre']);
			$bigtable->nextRow();
		}

		$bigtable->addCell(new HtmlDiv("mspdiv"), HtmlTd::VALIGN_CENTER);
		$bigtable->nextRow();
		$this->theContainer->addElement($bigtable);
	}
}
?>
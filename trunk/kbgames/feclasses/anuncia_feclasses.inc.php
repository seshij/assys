<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");
include_once ("classes/anunciante_classes.inc.php");

class AnunciaFEController extends HtmlGuiInterface {
	var $theContainer;
	var $myId;

	public function AnunciaFEController($myId){
		$this->theContainer=new HtmlContainer();
		$this->myId=$myId;
	}

	public function getHtml() {
		if(isset($_SESSION['lang'])){
			$this->getAnunciante($_SESSION['lang']);
		}else{
			$this->getAnunciante("eng");
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getAnunciante($lang){

		if($lang=="esp"){
			$t1="Contacto:";
			$t2="Tel&eacute;fono:";
			$t3="Fax:";
			$t4="Direcci&oacute;n:";
			$t5="Ciudad:";
			$t6="Correo-e:";
			$t7="P&aacute;gina Web:";
		}else if($lang=="eng"){
			$t1="Contact:";
			$t2="Phone:";
			$t3="Fax:";
			$t4="Address:";
			$t5="City:";
			$t6="E-mail:";
			$t7="Website:";
		}

		//$query="select anunciante.id, anunciante.nombre, anunciante.desc_corta from anunciante LEFT JOIN anuncia_subcat ON anuncia_subcat.subcat_id=".$this->myFather." where anunciante.idioma='".$lang."' order by anunciante.nombre";
		$anuncia=new Anunciante();
		$anuncia->setId($this->myId);

		$anuncia=DaoMgr::getDao()->load($anuncia, $anuncia->getTableDescriptor());
		$bigtable=new HtmlTable();
		$bigtable->setClass("anuncia");
		$catNameTd=$bigtable->addCell($anuncia->getNombre());
		$catNameTd->setClass("seccionHead");
		$catNameTd->addAttribute("colspan","2");
		$bigtable->nextRow();
		$contentTr=$bigtable->getCurrentRow();
		$contentTr->setClass("content");
		//$anuncia=new Anunciante();
		$contentTable=new HtmlTable();
		$contentTable->setClass("anuncia");
		//$contentTable->addAttribute("border","1");


		if(!file_exists($anuncia->getLogo()) || $anuncia->getLogo()==""){
			$logo=new HtmlImg("images/clientes/sinLogo.png");
		}else{
			$logo=new HtmlImg($anuncia->getLogo());
		}
		$tdt=$contentTable->addCell($logo);
		$dataTable=new HtmlTable();
		$dataTable->setClass("anuncia");
		$tdt=$dataTable->addCell($t1);
		$tdt->setClass("anunciaDato");
		$dataTable->addCell($anuncia->getContacto());
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t2);
		$tdt->setClass("anunciaDato");
		$dataTable->addCell($anuncia->getTelefono());
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t3);
		$tdt->setClass("anunciaDato");
		$dataTable->addCell($anuncia->getFax());
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t4);
		$tdt->setClass("anunciaDato");
		$dataTable->addCell($anuncia->getDireccion());
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t5);
		$tdt->setClass("anunciaDato");
		$dataTable->addCell($anuncia->getCiudad());
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t6);
		$tdt->setClass("anunciaDato");
		$mailLink=new HtmlLink($anuncia->getEmail(),"mailto:".$anuncia->getEmail());
		$mailLink->setClass("anunLink");
		$dataTable->addCell($mailLink);
		$dataTable->nextRow();
		$tdt=$dataTable->addCell($t7);
		$tdt->setClass("anunciaDato");
		$webLink=new HtmlLink($anuncia->getWebsite(),"http://".$anuncia->getWebsite());
		$webLink->setTarget("_blank");
		$webLink->setClass("anunLink");
		$dataTable->addCell($webLink);
		$dataTable->nextRow();

		$tdt=$contentTable->addCell($dataTable);
		$tdt->addAttribute("width","75%");
		$contentTable->nextRow();
		$descC=new HtmlContainer();
		if(!file_exists($anuncia->getFoto()) || $anuncia->getFoto()==""){
			$imagen=new HtmlImg("images/clientes/sinImagen.png");
		}else{
			$imagen=new HtmlImg($anuncia->getFoto());
		}

		$imagen->addAttribute("align","right");
		$imagen->addAttribute("hspace","10");
		$descC->addElement($imagen);

		$tdt=$descC->addElement(new HtmlText($anuncia->getDesc_larga()));
		$tdt=$contentTable->addCell($descC);
		$tdt->addAttribute("colspan","2");

		$contentTable->nextRow();
		$bigtable->addCell($contentTable);
		$bigtable->nextRow();
		$this->theContainer->addElement($bigtable);
	}
}
?>
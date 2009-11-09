<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");


class AboutFEController extends HtmlGuiInterface {
	var $theContainer;

	public function AboutFEController(){
		$this->theContainer=new HtmlContainer();

	}

	public function getHtml() {
		if(isset($_SESSION['lang'])){
			$this->getAbout($_SESSION['lang']);
		}else{
			$this->getAbout("eng");
		}
		return $this->theContainer->getHtml();
	}

	public function requireComtorSession() {
		return true;
	}

	public function getAbout($lang){
		if($lang=="eng"){
			$title="ABOUT US";
			$txt="¡Welcome to our Directory!<br><br>
				 Our goal is to provide you with the most complete information about Colombian companies that offer products and services for export, including the necessary data for you to establish direct contact with Colombian suppliers.<br><br>
					We don’t act as intermediaries and we don’t charge for the contact information.<br><br>
					<strong>MadeInColombiaForExport.com</strong><br>
					<strong>Telephone: 800-4247 (Bogotá, Colombia) </strong><br> 
					<strong>Email: info@madeincolombiaforexport.com </strong><br><br> 
					Language problems? If for any reason you need assistance communicating with suppliers, let us know and we’ll help you in matters such as obtaining product information, requesting quotes or anything else that you may need. ";
			
		}else{
			$title="ACERCA DE NOSOTROS";
			$txt="¡Bienvenidos a nuestro Directorio!<br><br>
				  Nuestro objetivo es brindarle la más completa información de empresas colombianas con productos y servicios para exportación, incluyendo los datos necesarios para que usted pueda hacer contacto directo con los diferentes proveedores colombianos.<br><br>
					No somos intermediarios y no cobramos por la información de contacto.<br><br>
					<strong>MadeInColombiaForExport.com</strong><br>
					<strong>Teléfono: 800-4247 (Bogotá, Colombia)</strong><br> 
					<strong>Correo-e: info@madeincolombiaforexport.com</strong><br><br> 
					¿Problemas de lenguaje? Si por cualquier razón usted necesita ayuda comunicándose con los proveedores, háganoslo saber y lo podremos ayudar en asuntos tales como conseguir información de producto, solicitar cotizaciones o cualquier otra cosa que se presente.";
				
		}


		$bigtable=new HtmlTable();
		$catNameTd=$bigtable->addCell(strtoupper($title));
		$catNameTd->setClass("seccionHead");
		$bigtable->nextRow();
		$contentTr=$bigtable->getCurrentRow();
		$contentTr->setClass("content");
		$elTd=$bigtable->addCell($txt);
		$elTd->setClass("about");
		$bigtable->nextRow();
		$this->theContainer->addElement($bigtable);
	}
}
?>
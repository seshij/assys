<?php
include_once ("lib/html.inc.php");
include_once ("lib/util.inc.php");
include_once("components/administrable.inc.php");

class ContactFEController extends HtmlGuiInterface {
	var $theContainer;
	var $elMensaje;
	public function ContactFEController(){
		$this->theContainer=new HtmlContainer();
		$this->elMensaje="";
	}

	public function getHtml() {
		if(isset($_REQUEST['ok'])){
			if($this->sendMail()){
				$this->elMensaje="Mensaje enviado";
			}else{
				$this->elMensaje="Error en el envio";
			}
		}
		$this->getContactForm();

		return $this->theContainer->getHtml();
	}

	public function sendMail(){
		$dia=date("m.d.Y");
		$hora=date("H:i:s");
		
		$nombre =$_POST["name"];
		$subject =$_POST["subject"];
		$email =$_POST["email"];
		$message=$_POST["message"];
		$para=$_POST['dirigido'];

		// asunto
		$asunto = 'Contacto WEB';

		// mensaje
		$mensaje = "El dia $dia a las $hora escribieron desde el portal \r\n
		Nombre: $nombre\r\n
		Correo-e: $email\r\n
		Asunto: $subject\r\n
		Mensaje: $message\r\n
			
		Este correo fue autogenerado por el portal www.isec.com.co\r\n";

		$cabeceras .= 'To: ISEC Ltda<'.$para.'>' . "\r\n";
		$cabeceras .= 'From: PORTAL<portal@isec.com.co>'."\r\n\n";

		// Enviarlo
		if(mail($para, $asunto, $mensaje, $cabeceras)){
			return true;
		}else{
			return false;
		}
	}

	public function requireComtorSession() {
		return true;
	}

	public function getContactForm(){

		$title="Cont&aacute;ctenos";
		$t1="Nombre";
		$t2="Correo-e";
		$t3="Asunto";
		$t4="Mensaje";
		$b1="Enviar";


		$bigtable=new HtmlTable();
		$bigtable->addAttribute("align", "center");
		$bigtable->setClass("listTable");
		$header=$bigtable->addCell($title);

		$header->setClass("seccionHead");
		$bigtable->nextRow();
		$contentTr=$bigtable->getCurrentRow();
		$contentTr->setClass("content");
		$elForm=new HtmlComtorForm();
		$formtable=$elForm->getTableForm();
		if($this->elMensaje!=""){
			$msg=new HtmlP($this->elMensaje);
			$msg->setClass("error");
			$td=$formtable->addCell($msg);
			$td->addAttribute("colspan","2");
			$formtable->nextRow();
		}
		$elForm->addAttribute("name", "contact");
		$elForm->addAttribute("action", "index.php?option=contact");
		$elForm->addAttribute("method", "POST");
		$elSel=$elForm->addOptionsBox("Dirigido a", "dirigido");
		
				
		$rows=DaoMgr::getDao()->executeQuery("select * from contactos order by nombre");
		foreach($rows as $row){
			$elSel->addOption($row['email'],$row['nombre']);
		}
		$elForm->addInputText($t1,"name");
		$elForm->addInputText($t2,"email");
		$elForm->addInputText($t3,"subject");
		$txta=$elForm->addTextArea($t4,"message");
		$txta->addAttribute("cols","30");
		$txta->addAttribute("rows","7");

		$elForm->addButton("ok",$b1,HtmlButton::SUBMIT_BUTTON);

		$bigtable->addCell($elForm,HtmlTd::VALIGN_CENTER);
		$bigtable->nextRow();


		$this->theContainer->addElement($bigtable);
	}
}
?>
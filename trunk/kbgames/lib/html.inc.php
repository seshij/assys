<?php
//echo "(html) util.inc.php<br>";
include_once("util.inc.php");
//include_once("../fckeditor/fckeditor.php") ;
//--------------------------------------------------------------------------------------------------------------------//
// Class: Attribute
//--------------------------------------------------------------------------------------------------------------------//
class Attribute {

	private $name;
	private $value;

	function Attribute($name = "", $value = "") {
		$this->name = $name;
		$this->value = $value;
	}

	function getName() {
		return $this->name;
	}

	function setName($name) {
		$this->name = $name;
	}

	function getValue() {
		return $this->value;
	}

	function setValue($value) {
		$this->value = $value;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: HtmlElement
//--------------------------------------------------------------------------------------------------------------------//
interface HtmlElement {
	public function getHtml();
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlContainer
//--------------------------------------------------------------------------------------------------------------------//
class HtmlContainer implements HtmlElement {

	protected $elements;

	function HtmlContainer() {

		$this->elements = new Vector();
	}

	function getHtml() {
		$inside = "";

		for ($i = 0; $i < $this->elements->size(); $i++) {
			$element = $this->elements->elementAt($i);
			if ($element instanceof HtmlElement) {
				$inside = $inside . $element->getHtml();
			}
		}
		return $inside;
	}

	function addElement($e) {

		if ($this->validate($e)) {

			$this->elements->addElement($e);
		}
		return $e;
	}

	// Debe ser redefinido en todos las clases que extienden a HtmlTag
	function validate($e) {
		return true;
	}

	function addTextElement($text) {
		$this->addElement(new HtmlText($text, false));
	}

	function getElements() {
		return $this->elements;
	}

	function setElements($elements) {
		$this->elements = $elements;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlSimpleTag
//--------------------------------------------------------------------------------------------------------------------//
class HtmlSimpleTag implements HtmlElement {

	var $tag;
	var $attributes;

	function HtmlSimpleTag($tag) {
		$this->tag = $tag;
		$this->attributes = new Vector();
	}

	function getHtml() {
		$html = "<" . $this->tag;
		for($i = 0; $i < $this->attributes->size(); $i++) {
			$temp = $this->attributes->elementAt($i);

			if ($temp->getValue() != NULL){
				$html = $html . " " . $temp->getName() . "=\"" . $temp->getValue() . "\"";
			} else{
				$html = $html . " " . $temp->getName()  . " ";
			}
		}

		$html = $html . "/>\n";
		return $html;
	}

	function addAttribute($atr, $value) {
		for ($i = 0; $i < $this->attributes->size(); $i++){
			$attribute = $this->attributes->get($i);
			$name = $attribute->getName();
			if ($name == $atr) {
				$this->attributes->replace($i, new Attribute($atr, $value));
				return;
			}
		}
		$this->attributes->addElement(new Attribute($atr, $value));
	}

	function setAttribute($atr, $value) {
		for ($i = 0; $i < $this->attributes->size(); $i++){
			$attribute = $this->attributes->get($i);
			$name = $attribute->getName();
			if ($name == $atr) {
				$this->attributes->replace($i, new Attribute($atr, $value));
				return true;
			}
		}
		return false;
	}

	function getAttributeValue ($attr){
		for($i = 0; $i < $this->attributes->size(); $i++){
			$attribute = $this->attributes->get($i);
			$name = $attribute->getName();
			if ($name == $attr) {
				$value = $attribute->getValue();
				return $value;
			}
		}
		return NULL;
	}

	function removeAttribute($atr) {
		return $this->attributes->removeElement(new Attribute($atr,""));
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlText
//--------------------------------------------------------------------------------------------------------------------//
class HtmlText implements HtmlElement {

	private $text;
	private $encode = true;

	function HtmlText($text, $encode = false) {
		$this->text = $text;
		$this->encode = $encode;
	}

	function getHtml() {
		if (!$this->encode){
			return $this->text;
		}
		else{
			return HtmlText::HTMLEntityEncode($this->text);
		}
	}

	public static function HTMLEntityEncode($s) {
		$buf = "";
		for ($i = 0; $i < strlen($s); $i++){
			$c = $s[$i];
			if ( $c>='a' && $c<='z' || $c>='A' && $c<='Z' || $c>='0' && $c<='9'  || $c == '_' ||
			$c == ','|| $c == ';'|| $c == '.'||  $c == ':' || $c == '?' ||  $c == '\'' || $c == '"' || $c == '|' || $c == '~' ||  $c == '!' ||
			$c == '+' ||	$c == '-' ||	$c == '*' ||	$c == '/' || $c == '%' || $c == '=' ||
			$c == '@' ||  $c == '#'||  $c == '$'||  $c == '\"'||
			$c == '('|| $c == ')'|| $c == '['||  $c == ']' ){
				$buf = $buf . $c;
			}
			else if ($c == ' '){
				$buf = $buf . "&nbsp;";
			}
			else if ($c == '&'){
				$buf = $buf . "&amp;";
			}
			else{
				$buf = $buf . "&#" . ord($c) . ";";
			}
		}
		//var_dump($buf);
		return $buf;
	}

	function getText() {
		return $this->text;
	}

	function setText($text) {
		$this->text = $text;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlBr
//--------------------------------------------------------------------------------------------------------------------//
class HtmlBr extends HtmlSimpleTag {
	function HtmlBr() {
		$this->HtmlSimpleTag("br");
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTag
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTag extends HtmlContainer {

	var $tag;
	var $attributes;

	public function HtmlTag($tag) {
		$this->HtmlContainer();
		$this->tag = $tag;
		$this->attributes = new Vector();
	}

	public function getHtml() {
		$html = $this->beginTag() . HtmlContainer::getHtml() . $this->endTag();
		return $html;
	}

	public function beginTag() {
		$begin = "<" . $this->tag;

		for($i = 0; $i < $this->attributes->size(); $i++) {
			$temp = $this->attributes->elementAt($i);
			if ($temp->getValue() != NULL){
				$begin = $begin . " " . $temp->getName() . "=\"" . $temp->getValue() . "\"";
			}
			else{
				$begin = $begin . " " . $temp->getName()  . " ";
			}
		}

		$begin = $begin . ">\n";
		return $begin;
	}

	public function endTag() {
		return "</". $this->tag. ">\n";
	}

	public function addAttribute($atr, $value) {
		if($this->attributes->contains(new Attribute($atr, $value))) {
			$this->setAttribute($atr, $value);
		}
		else{
			$this->attributes->addElement(new Attribute($atr, $value));
		}
	}

	public function getAttributeValue($attr){
		for($i = 0; $i < $this->attributes->size(); $i++){
			$attribute = $this->attributes->get($i);
			$name = $attribute->getName();
			if ($name == $attr) {
				$value = $attribute->getValue();
				return $value;
			}
		}
		return NULL;
	}

	public function setAttribute($atr, $value) {
		$i = $this->attributes->indexOf(new Attribute($atr, $value));
		if($i == -1){
			return false;
		}
		else {
			$a = $this->attributes->elementAt($i);
			$a->setValue($value);
			return true;
		}
	}

	public function removeAttribute($atr) {
		return $this->attributes->removeElement(new Attribute($atr,""));
	}

	public function setClass($sclass) {
		$this->addAttribute("class", $sclass);
	}

	public function setEnable($enable) {
		if (!$enable){
			$this->addAttribute("disabled", NULL);
		}
		else{
			$this->removeAttribute("disabled");
		}
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlDiv
//--------------------------------------------------------------------------------------------------------------------//
class HtmlDiv extends HtmlTag{

	public function HtmlDiv($id){
		$this->HtmlTag("div");
		$this->addAttribute("id", $id);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlP
//--------------------------------------------------------------------------------------------------------------------//
class HtmlP extends HtmlTag{

	public function HtmlP($text){
		$this->HtmlTag("p");
		$this->addElement(new HtmlText($text));

	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlP
//--------------------------------------------------------------------------------------------------------------------//
class HtmlFont extends HtmlTag{

	public function HtmlFont($text){
		$this->HtmlTag("font");
		$this->addElement(new HtmlText($text));

	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlDiv
//--------------------------------------------------------------------------------------------------------------------//
class HtmlErrors extends HtmlContainer {

	private $numErrors;

	public function HtmlErrors(){
		$this->HtmlContainer();
		$this->numErrors = 0;
	}

	public function addError($error) {
		$fontError=new HtmlFont($error);
		$fontError->setClass("error");
		$this->addElement($fontError);
		$this->addElement(new HtmlBr());
		$this->numErrors++;
	}

	public function getNumErrors() {
		return $this->numErrors;
	}
}

class HtmlB extends HtmlTag{
	public function HtmlB($content = NULL){
		 $this->HtmlTag("B");
		 if ($content != NULL){
		 	$this->addElement($content);	
		 }
	}
}
//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlForm
//--------------------------------------------------------------------------------------------------------------------//
class HtmlForm extends HtmlTag{

	public function HtmlForm($action = "", $method = "GET",$name= NULL,  $encoding=NULL) {
		$this->HtmlTag("form");
		$this->init($action,$method,$encoding,$name);
	}

	public function init($action , $method , $encoding, $name){
		$this->addAttribute("action",$action);
		$this->addAttribute("method",$method);
		if ($encoding != NULL){
			$this->setEncoding($encoding);
		}
		if ($name != NULL){
			$this->addAttribute("name",$name);
		}
	}

	public function setEncoding($encoding) {
		$this->addAttribute("encoding",encoding);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlImg
//--------------------------------------------------------------------------------------------------------------------//
class HtmlImg extends HtmlSimpleTag {

	public function HtmlImg($path) {
		$this->HtmlSimpleTag("img");
		$this->addAttribute("src", $path);
		$this->addAttribute("border", "0");
		$this->addAttribute("hspace", "0");
		$this->addAttribute("vspace", "0");
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlLi
//--------------------------------------------------------------------------------------------------------------------//
class HtmlLi extends HtmlTag {

	private  $text = "";

	public function HtmlLi($text) {
		$this->HtmlTag("li");
		$this->setText($text);
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}

	public function getHtml() {
		$html = HtmlTag::getHtml();
		$html = substr($html, 0,  (strpos($html,">") + 1) . $this->getText() . "\n");
		return $html;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlLink
//--------------------------------------------------------------------------------------------------------------------//
class HtmlLink extends HtmlTag {

	public function HtmlLink($text, $link, $image = NULL, $encode=false , $onclick = NULL) {
		$this->HtmlTag("a");
		$this->addAttribute("href", $link);
		if ($image != NULL) {
			$this->addElement(new HtmlImg($image));
		}
		if ($onclick != NULL) {
			$this->addAttribute("onclick", $onclick);
		}
		$this->addElement(new HtmlText($text, $encode));
	}

	public function setTarget($target){
		$this->addAttribute("target", $target);

	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: HtmlList
//--------------------------------------------------------------------------------------------------------------------//
interface HtmlList {
	const  CIRCLE_TYPE = "circle";
	const  SQUARE_TYPE = "square";
	const  DISC_TYPE = "disc";
	public function getType();
	public function setType($type);
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlOl
//--------------------------------------------------------------------------------------------------------------------//
class HtmlOl extends HtmlTag implements HtmlList{

	public function HtmlOl() {
		$this->HtmlTag("ol");
	}

	public function getType() {
		return $this->getAttributeValue("type");
	}

	public function setType($type) {
		$this->addAttribute("type", $type);
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlPage
//--------------------------------------------------------------------------------------------------------------------//
class HtmlPage extends HtmlTag {

	var $head;
	var $body;

	public function HtmlPage($pageTitle) {
		$this->HtmlTag("html");
		$this->head = new HtmlTag("head");
		$this->body = new HtmlTag("body");
		$title = new HtmlTag("title");
		$title->addElement(new HtmlText($pageTitle));
		$this->addHeadElement($title);
	}

	public function addHeadElement($e) {
		$this->head->addElement($e);
	}

	public function addElement($e) {
		return $this->body->addElement($e);
	}

	// ESTA FUNCION EN JAVA SE LLAMA include()
	public function pageInclude($e) {
		$this->body->addElement($e);
	}

	public function getHtml() {;
	$html = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\n";
	$html = $html . $this->beginTag() . $this->head->getHtml() . $this->body->getHtml() . $this->endTag();
	return $html;
	}

	public function addStylesheet($file) {
		$style = new HtmlSimpleTag("link");
		$style->addAttribute("href", $file);
		$style->addAttribute("rel","stylesheet");
		$style->addAttribute("type","text/css");
		$this->addHeadElement($style);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlScript
//--------------------------------------------------------------------------------------------------------------------//
class HtmlScript extends HtmlTag {

	var $script;

	public function HtmlScript($language) {
		$this->HtmlTag("script");
		$this->addAttribute("language", $language);
		$this->script = new HtmlText("", false);
		$this->addElement($this->script);
	}

	public function setSrc($src){
		$this->addAttribute("src", $src);
	}
	public function setScript($text) {
		$this->script->setText($text);
	}

	public function getScript() {
		return $this->script->getText();
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTd
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTd extends HtmlTag {

	const VALIGN_LEFT = 1;
	const VALIGN_CENTER = 2;
	const VALIGN_RIGHT = 3;

	public function HtmlTd($t = NULL, $align = HtmlTd::VALIGN_LEFT) {
		$this->HtmlTag("td");
		if ($align == HtmlTd::VALIGN_RIGHT) {
			$this->addAttribute("align", "right");
		}
		else if ($align == HtmlTd::VALIGN_CENTER) {
			$this->addAttribute("align", "center");
		}
		else {
			$this->addAttribute("align", "left");
		}

		if($t != NULL){
			if ($t instanceof HtmlElement) {
				$this->addElement($t);
			} else {
				$this->addElement(new HtmlText($t));
			}
		}
	}

	public function initWithStyleAlign($width, $style, $align) {
		$this->addAttribute("width", $width);
		$this->addAttribute("style", $style);
		$this->addAttribute("align", $align);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTh
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTh extends HtmlTag{

	const VALIGN_LEFT = 1;
	const VALIGN_CENTER = 2;
	const VALIGN_RIGHT = 3;

	public function HtmlTh($t = NULL, $align = HtmlTh::VALIGN_LEFT) {
		$this->HtmlTag("th");
		if ($align == HtmlTh::VALIGN_RIGHT) {
			$this->addAttribute("align", "right");
		}
		else if ($align == HtmlTh::VALIGN_CENTER) {
			$this->addAttribute("align", "center");
		}
		else {
			$this->addAttribute("align", "left");
		}

		if($t != NULL){
			if ($t instanceof HtmlElement) {
				$this->addElement($t);
			} else {
				$this->addElement(new HtmlText($t));
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTr
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTr extends HtmlTag {

	public function HtmlTr() {
		$this->HtmlTag("tr");
	}

	public function validate($e) {
		if ($e instanceof HtmlTd) {
			return true;
		}
		else if ($e instanceof HtmlTh) {
			return true;
		}
		return false;
	}
}

class HtmlCenter extends HtmlTag {
	public function HtmlCenter($content = NULL){
		$this->HtmlTag("center");
		if ($content != NULL){
			$this->addElement($content);
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTable
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTable extends HtmlTag {

	protected $currentRow;

	public function HtmlTable() {
		$this->HtmlTag("table");
		$this->currentRow = new HtmlTr();
	}

	public function addCell($info = NULL, $align = HtmlTd::VALIGN_LEFT) {
		$td = new HtmlTd($info,$align);
		$this->currentRow->addElement($td);
		return $td;
	}

	public function nextRow() {
		$this->addElement($this->currentRow);
		$this->currentRow = new HtmlTr();
	}

	public function validate($e) {
		if ($e instanceof HtmlTr) {
			return true;
		}
		return false;
	}

	public function addRow($row) {
		$tr = $this->currentRow;
		$itRow = $row->iterator();
		while ($itRow->hasNext()) {
			$this->addCell($itRow->next());
		}
		$this->nextRow();
		return $tr;
	}

	public function setWidth($width) {
		$this->addAttribute("width", $width);
	}

	public function getCurrentRow(){
		return $this->currentRow;
	}

	public function getHtml(){
		return HtmlTag::getHtml();
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlUl
//--------------------------------------------------------------------------------------------------------------------//
class HtmlUl extends HtmlTag implements HtmlList{

	public function HtmlUl() {
		$this->HtmlTag("ul");
	}

	public function getType() {
		return $this->getAttributeValue("type");
	}

	public function setType($type) {
		$this->addAttribute("type", $type);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: ImageHeader
//--------------------------------------------------------------------------------------------------------------------//
class ImageHeader extends HtmlTable{

	public function ImageHeader($image, $title){
		$this->addAttribute("width", "100%");
		$this->addAttribute("bgcolor","000099");
		$i =  new HtmlSimpleTag("img");
		$i->addAttribute("src", $image);
		$this->addCell($i);
		$this->addCell($title);
		$this->nextRow();
		$this->addElement($this);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlComtorForm
//--------------------------------------------------------------------------------------------------------------------//
class HtmlComtorForm extends HtmlTag {

	protected $tableForm;
	protected $errors;

	public function HtmlComtorForm() {
		$this->HtmlTag("form");
		$this->tableForm =  new HtmlTable();
		$this->errors = new HtmlErrors();
		$this->addElement($this->errors);
		$this->addElement($this->tableForm);
	}

	function addInputText($name, $id, $value = NULL) {
		$this->tableForm->addCell($name . ": ");
		$inputText = new HtmlInputText($id);
		$this->tableForm->addCell($inputText);
		$this->tableForm->nextRow();
		if ($value != NULL) {
			$inputText->setValue($value);
		}
		return $inputText;

	}

	public function addInputPasswordText($name, $id) {
		$this->tableForm->addCell($name . ": ");
		$inputPassword = new HtmlInputPassword($id);
		$this->tableForm->addCell($inputPassword);
		$this->tableForm->nextRow();
		return $inputPassword;
	}

	public function addText($text) {
		$this->tableForm->addCell($text);
		$this->tableForm->nextRow();
	}

	public function addButton($name, $value, $type) {
		$inputButton = new HtmlButton($type, $name, $value);
		$this->tableForm->addCell($inputButton, HtmlTd::VALIGN_CENTER);
		$this->tableForm->nextRow();
	}

	public function addInputHidden($name, $value) {
		$ih = new HtmlInputHidden($name, $value);
		$this->addElement($ih);
		return $ih;
	}

	public function addFormHeader($titulo, $name, $action, $method, $class="comtorboxheader") {
		$this->addAttribute("name", $name);
		$this->addAttribute("action", $action);
		$this->addAttribute("method", $method);
		$formHead=$this->tableForm->addCell($titulo);
		$formHead->addAttribute("colspan","2");
		$formHead->addAttribute("class",$class);

		$this->tableForm->nextRow();
		return $formHead;
	}

	public function addOptionsBox($label, $name) {
		$this->tableForm->addCell($label . ": ");
		$optionsBox = new HtmlSelect($name);
		$this->tableForm->addCell($optionsBox);
		$this->tableForm->nextRow();
		return $optionsBox;
	}

	public function addTextArea($label, $name) {
		$this->tableForm->addCell($label . ": ");
		$textArea = new HtmlTextArea($name);
		$this->tableForm->addCell($textArea);
		$this->tableForm->nextRow();
		return $textArea;
	}

	public function addError($error) {
		$this->errors->addError($error);
	}

	public function addDateSelecter($label) {
		$this->tableForm->addCell($label . ": ");
		$date = new HtmlDateSelect();
		$this->tableForm->addCell($date);
		$this->tableForm->nextRow();
		return $date;
	}

	public function addCheckbox($label, $name, $value) {
		$hc = new HtmlContainer();
		$check = new HtmlCheckbox($name, $value);
		$hc->addElement($check);
		$hc->addElement(new HtmlText($label));
		$td = $this->tableForm->addCell($hc);
		$td->addAttribute("colspan", "2");
		$this->tableForm->nextRow();
		return $check;
	}

	public function getFormTable(){
		return $this->tableForm;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlCuatroZonas
//--------------------------------------------------------------------------------------------------------------------//
class HtmlCuatroZonas  extends HtmlContainer{

	var $header;
	var $left;
	var $right;
	var $footer;

	public function HtmlCuatroZonas() {
		$this->HtmlContainer();

		$headerTable = new HtmlTable();
		$headerTable->addAttribute("width", "100%");
		$this->header = $headerTable->addCell();
		$headerTable->nextRow();
		$this->addElement($headerTable);

		$contentTable = new HtmlTable();
		$contentTable->addAttribute("width", "100%");

		$tempLeft = $contentTable->addCell();
		$tempLeft->addAttribute("width", "30%");
		$tempLeft->addAttribute("valign", "top");
		$this->left = $tempLeft;

		$tempRight = $contentTable->addCell();
		$tempRight->addAttribute("width", "70%");
		$this->right = $tempRight;
		$contentTable->nextRow();
		$this->addElement($contentTable);

		$footerTable = new HtmlTable();
		$footerTable->addAttribute("width", "100%");
		$this->footer = $footerTable->addCell();
		$this->footer->addAttribute("align", "center");
		$footerTable->nextRow();
		$this->addElement($footerTable);
	}

	public function setFooter($footer) {
		$this->footer = $footer;
	}

	public function setHeader($header) {
		$this->header = $header;
	}

	public function setLeft($left) {
		$this->left = $left;
	}

	public function setRight($right) {
		$this->right = $right;
	}

	public function getFooter() {
		return $this->footer;
	}

	public function getHeader() {
		return $this->header;
	}

	public function getLeft() {
		return $this->left;
	}

	public function getRight() {
		return $this->right;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlDateText
//--------------------------------------------------------------------------------------------------------------------//
class HtmlDateText extends HtmlContainer implements HtmlFormElement{

	var $dateField;
	var $attributes;

	public function HtmlDateText($formName, $name, $withHour) {
		$this->HtmlContainer();

		$this->dateField = $this->addElement(new HtmlInputText($name));
		$this->dateField->addAttribute("readonly", NULL);

		if ($withHour) {
			$this->dateField->addAttribute("size", "20");
		} else {
			$this->dateField->addAttribute("size", "10");
		}
		$script = new HtmlScript("JavaScript");
		$hour = ($withHour == true) ? "true" : "false";
		$scriptTxt = "var cal" . $name
		. " = new calendar1(document.forms['" . $formName
		. "'].elements['" . $name . "']);\n" . "cal" . $name
		. ".year_scroll = true;\n" . "cal" . $name . ".time_comp = "
		. $hour . ";\n";

		$script->setScript($scriptTxt);
		$this->addElement($script);
		$this->addElement(new HtmlLink("", "javascript:cal" . $name . ".popup();","calendar/cal.gif"));

	}

	public function setValue($value) {
		$this->dateField->addAttribute("value", $value);
	}

	public function setId($id) {
		$this->dateField->addAttribute("id", $id);
	}

	public function setFunction($action, $function) {
		$this->dateField->addAttribute($action, $function);
	}

	public function setEditable($isEditable) {
		$this->dateField->setReadOnly(!$isEditable);
	}

	public function getName() {
		return $this->dateField->getName();
	}

	public function getValue() {
		return $this->dateField->getValue();
	}

	public function setEnable($enable) {
		$this->dateField->setEnable($enable);
	}

	public function setName($name) {
		$this->dateField.setName($name);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlDateText
//--------------------------------------------------------------------------------------------------------------------//
class HtmlLoginForm extends HtmlComtorForm{

	public function HtmlLoginForm($title, $name, $action, $toDo="initial_ok",$label_login="Usuario",$label_passwd="Password") {
		$this->HtmlComtorForm();
		$this->addFormHeader($title, $name, $action, "POST");
		$this->addInputText($label_login, "user");
		$this->addInputPasswordText($label_passwd, "password");
		$this->addButton("ok", "Ingresar", HtmlButton::SUBMIT_BUTTON);
		$this->addInputHidden("action", $toDo);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlMessageForm
//--------------------------------------------------------------------------------------------------------------------//
class HtmlMessageForm extends HtmlComtorForm {

	public function HtmlMessageForm($message, $action) {
		$this->HtmlComtorForm();
		$this->addFormHeader("Atencion!", "mensaje", $action, "POST");
		$this->addText($message);
		$this->addButton("end", "Aceptar", HtmlButton::SUBMIT_BUTTON);
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// FORM ELEMENTS
//--------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlButton
//--------------------------------------------------------------------------------------------------------------------//
class HtmlButton extends HtmlSimpleTag {

	const SUBMIT_BUTTON = 1;
	const RESET_BUTTON = 2;
	const CHOOSE_FILE_BUTTON = 3;
	const SCRIPT_BUTTON = 4;

	public function HtmlButton($type, $name, $label) {
		$this->HtmlSimpleTag("input");
		$this->init($type, $name, $label);
	}

	private function init($type, $name, $label) {
		if($type == HtmlButton::SUBMIT_BUTTON) {
			$this->addAttribute("type","submit");
		}else if($type == HtmlButton::RESET_BUTTON) {
			$this->addAttribute("type","reset");
		}
		else if($type == HtmlButton::CHOOSE_FILE_BUTTON) {
			$this->addAttribute("type","file");
		}
		else if($type == HtmlButton::SCRIPT_BUTTON) {
			$this->addAttribute("type", "button");
		}
		$this->addAttribute("name", $name);
                $this->addAttribute("class", $name);
		$this->addAttribute("value",$label);
	}

	public function onClick($value) {
		$this->addAttribute("onClick", $value);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlCheckbox
//--------------------------------------------------------------------------------------------------------------------//
class HtmlCheckbox extends HtmlSimpleTag {

	private $text = "";

	public function HtmlCheckbox($name, $value, $text = NULL) {
		$this->HtmlSimpleTag("input");
		$this->init($name, $value);
		if ($text != NULL) {
			$this->setText($text);
		}
	}

	private function init($name, $value) {
		$this->addAttribute("type", "checkbox");
		$this->addAttribute("name", $name);
		$this->addAttribute("value", $value);
	}

	public function getHtml() {
		$html = HtmlSimpleTag::getHtml();
		$html = substr($html, 0, (strlen($html) - 1));
		return $html . $this->getText() . "\n";
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlDateSelect
//--------------------------------------------------------------------------------------------------------------------//
class HtmlDateSelect extends HtmlContainer {

	private $year;
	private $month;
	private $day;

	public function HtmlDateSelect() {
			
		$this->year = new HtmlSelect("year");
		$this->month = new HtmlSelect("month");
		$this->day = new HtmlSelect("day");

		$years = array("1990","1991","1992","1993","1994","1995","1996","1997","1998");
		$months = array("1","2","3","4","5","6","7","8","9","10","11","12");
		$days = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19","20","21",
				"22","23","24","25","26","27","28","29","30","31");

		$this->year->addOptions($years);
		$this->month->addOptions($months);
		$this->day->addOptions($days);

		$this->addElement($this->year);
		$this->addElement($this->month);
		$this->addElement($this->day);
	}

	public function setDate($year, $month, $day) {
		$this->year->setSelected($year);
		$this->month->setSelected($month);
		$this->day->setSelected($day);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: HtmlFormElement
//--------------------------------------------------------------------------------------------------------------------//
interface HtmlFormElement {

	public function setValue($value);
	public function getValue();

	public function setName($name);
	public function getName();

	public function setEnable($enable);
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlInput
//--------------------------------------------------------------------------------------------------------------------//
class HtmlInput extends HtmlSimpleTag implements HtmlFormElement{

	protected function HtmlInput($type, $name, $value){
		$this->HtmlSimpleTag("input");
		$this->init($type, $name, $value);
	}

	private function init($type, $name, $value) {
		$this->addAttribute("type", $type);
		$this->addAttribute("name", $name);
		$this->addAttribute("value", $value);
	}

	public function setValue($value) {
		$this->addAttribute("value", $value);
	}

	public function getValue(){
		return $this->getAttributeValue("value");
	}

	public function setName($name) {
		$this->addAttribute("name", $name);
	}

	public function getName() {
		return $this->getAttributeValue("value");
	}

	public function setEnable($enable) {
		if (!$enable){
			$this->addAttribute("disabled", NULL);
		}
		else{
			$this->removeAttribute("disabled");
		}
	}

	public function setReadOnly($readOnly) {
		if (!$readOnly){
			$this->addAttribute("readonly", NULL);
		}
		else{
			$this->removeAttribute("readonly");
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlInputFile
//--------------------------------------------------------------------------------------------------------------------//
class HtmlInputFile extends HtmlInput{

	public function HtmlInputFile($name, $value = "", $size = NULL, $maxlength = NULL) {
		$this->HtmlInput("file", $name, $value);
		if($size != NULL){
			$this->addAttribute("size", "" . $size);
		}
		if($maxlength != NULL){
			if ($maxlength >= 0){
				$this->addAttribute("maxlength", "" . $maxlength);
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlInputHidden
//--------------------------------------------------------------------------------------------------------------------//
class HtmlInputHidden extends HtmlInput  {

	public function HtmlInputHidden($name, $value) {
		$this->HtmlInput("hidden", $name, $value);
	}

	public function setId($id) {
		$this->addAttribute("id", $id);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlInputPassword
//--------------------------------------------------------------------------------------------------------------------//
class HtmlInputPassword extends HtmlInput {

	public function HtmlInputPassword($name, $value = "", $size = NULL, $maxlen = NULL) {
		$this->HtmlInput("password", $name, $value);
		$this->init($name , $value , $size, $maxlen);
	}

	private function init($name, $value, $size, $maxlength) {
		$this->addAttribute("type", "password");
		$this->addAttribute("name", $name);
		$this->addAttribute("value", $value);
		if ($size != NULL) {
			$this->addAttribute("size", "" . $size);
		}
		if ($maxlength != NULL) {
			if ($maxlength >= 0 ){
				$this->addAttribute("maxlength", "" . $maxlength);
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlInputText
//--------------------------------------------------------------------------------------------------------------------//
class HtmlInputText extends HtmlInput {

	public function HtmlInputText($name, $value = "", $size = NULL, $maxlength = NULL) {
		$this->HtmlInput("text", $name, $value);
		$this->addAttribute("id", $name);
		if ($size != NULL) {
			$this->addAttribute("size", "" . $size);
		}
		if ($maxlength != NULL) {
			if ($maxlength >= 0 ){
				$this->addAttribute("maxlength", "" . $maxlength);
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlOption
//--------------------------------------------------------------------------------------------------------------------//
class HtmlOption extends HtmlTag {

	var $value;

	public function HtmlOption($value = NULL, $name = NULL) {
		$this->HtmlTag("option");
		if ($value != NULL) {
			$this->value = $value;
			$this->addAttribute("value", $value);
		}
		if ($name != NULL) {
			$this->addElement(new HtmlText($name));
		}
	}


	public function selected() {
		$this->addAttribute("selected", "");
	}

	public function isSelected(){
		$attr = new Attribute("selected", "");
		return ($this->attributes->indexOf($attr) >= 0);
	}

	public function equals($a) {
		return ($this->value == $a.getValue());
	}

	public function getValue() {
		return $this->value;
	}


}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlRadio
//--------------------------------------------------------------------------------------------------------------------//
class HtmlRadio extends HtmlSimpleTag {

	private $text = "";

	public function HtmlRadio($name, $value, $text = NULL) {
		$this->HtmlSimpleTag("input");
		$this->init($name, $value);
		if ($text != NULL) {
			$this->setText($text);
		}
	}

	private function init($name, $value) {
		$this->addAttribute("type", "radio");
		$this->addAttribute("name", $name);
		$this->addAttribute("value", $value);
	}

	public function getHtml() {
		$html = HtmlSimpleTag::getHtml();
		$html = substr($html, 0, (strlen($html) - 1));
		return $html . $this->getText() . "\n";
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlSelect
//--------------------------------------------------------------------------------------------------------------------//
class HtmlSelect extends HtmlTag implements HtmlFormElement {

	public function HtmlSelect($name = NULL) {
		$this->HtmlTag("select");
		if ($name != NULL) {
			$this->setName($name);
		}

	}

	// FIXME Originalmente trae manejo de posicion.
	public function addOption($value, $name) {
		$op = new HtmlOption($value, $name);
		$this->addElement($op);
	}

	public function setSelected($value) {


		for ($i = 0 ; $i < $this->elements->size() ; $i++){
			$optionn = $this->elements->elementAt($i);

			if ($optionn instanceof HtmlOption){
				if($optionn->getValue()== $value){
					$optionn->selected();
				}
			}

		}
		/*
		 $i = $this->elements->indexOf(new HtmlOption($value, ""));
		 if ($i != -1) {
			$op = $this->elements->elementAt($i);
			$op->selected();
			}
			*/
	}

	public function addOptions($options) {
		for ($i = 0; $i < count($options); $i++) {
			$this->addOption($options[$i], $options[$i]);
		}
	}

	public function getName() {
		return $this->getAttributeValue("name");
	}

	public function getValue() {
		$resp = NULL;
		for ($i = 0; $i < $this->elements->size(); $i++) {
			$option = $this->elements->elementAt($i);
			if ($option->isSelected()) {
				return $option->getValue();
			}
		}
		return $resp;
	}

	public function setValue($value) {
		$this->setSelected($value);
	}

	public function setName($name) {
		$this->addAttribute("name", $name);
		$this->addAttribute("id", $name);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTextArea
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTextArea extends HtmlTag implements HtmlFormElement{

	var $text;

	public function HtmlTextArea($name, $text = "", $cols = NULL, $rows = NULL) {
		$this->HtmlTag("textarea");
		$this->addAttribute("name", $name);
		$this->text = new HtmlText($text);
		$this->addElement($this->text);
		if ($cols != NULL) {
			if(is_int($cols)){
				$this->setCols($cols);
			}
		}
		if ($rows != NULL) {
			if(is_int($rows)){
				$this->setRows($rows);
			}
		}
	}

	public function setText($text) {
		$this->text->setText($text);
	}

	public function getText() {
		return $this->text->getText();
	}

	public function setRows($rows) {
		$this->addAttribute("rows", "" . $rows);
	}

	public function setCols($cols) {
		$this->addAttribute("cols", "" . $cols);
	}

	public function setName($name) {
		$this->addAttribute("name", $name);
	}

	public function getName() {
		return $this->getAttributeValue("value");
	}

	public function getValue() {
		return $this->getText();
	}

	public function setValue($value) {
		$this->setText($value);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// ADVANCED ELEMENTS
//--------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------//
// Class: ActionIcon
//--------------------------------------------------------------------------------------------------------------------//
class ActionIcon implements HtmlElement {
	const ICON_NEW = "images/icons/small/document-new.png";
	const ICON_EDIT = "images/icons/small/edit.png";
	const ICON_DELETE = "images/icons/small/user-trash.png";
	const ICON_VIEW = "images/tango/edit-find.png";
	const ICON_HISTORY = "images/tango/history.png";
	const ICON_COPY = "images/tango/edit-copy.png";
	const ICON_EDIT_ID = "images/icons/small/view-refresh.png";

	private $url;
	private $icon;
	private $label;

	public function ActionIcon($url, $icon, $label) {
		$this->url = $url;
		$this->icon = $icon;
		$this->label = $label;
	}

	public function getHtml() {
		return "<a href=\"" . $this->getUrl() . "\" ><img src=\"" . $this->getIcon() . "\" border=\"0\" alt=\""
		. $this->getLabel() . "\" valign=\"center\"></a> " . $this->getLabel();
	}

	public function toString() {
		return $this->getHtml();
	}

	public function getIcon() {
		return $this->icon;
	}

	public function setIcon($icon) {
		$this->icon = icon;
	}

	public function getLabel() {
		return $this->label;
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: CascadeStyledForm
//--------------------------------------------------------------------------------------------------------------------//
class CascadeStyledForm implements HtmlElement {

	var $form;
	var $table;

	public function CascadeStyledForm($action, $method, $enconding) {
		$this->form = new HtmlForm($action, $method);
		$this->form->setEncoding($enconding);
		$this->table = new HtmlTable();
	}

	public function getHtml() {
		return $this->form->getHtml();
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTooltipDiv
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTooltipDiv extends HtmlDiv {

	private $text = "";

	public function HtmlTooltipDiv($id, $text, $classDiv) {
		$this->HtmlDiv($id);
		$this->text = $text;
		$this->addAttribute("class", $classDiv);
		$this->addElement(new HtmlText($text));
	}

	public function getText() {
		return $this->text;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTooltipLink
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTooltipLink extends HtmlLink {

	const HELP_IMG = "images/tango/gnome-help.png";
	const ERROR_IMG = "images/tango/dialog-error.png";

	public function HtmlTooltipLink($text, $image = NULL, $ttipDivId) {
		$this->HtmlLink($text, "#", $image);
		$this->createTooltipLink($ttipDivId);
	}

	private function createTooltipLink($ttipDivId) {
		$this->addAttribute("onmouseout", "popUp(event,'" . $ttipDivId . "')");
		$this->addAttribute("onmouseover", "popUp(event,'" . $ttipDivId . "')");
		$this->addAttribute("onclick", "return false");
	}
}


//--------------------------------------------------------------------------------------------------------------------//
// Class: HtmlTooltip
//--------------------------------------------------------------------------------------------------------------------//
class HtmlTooltip extends HtmlContainer {

	const HELP_CLASS_DIV = "tip";
	const ERROR_CLASS_DIV = "tipErr";

	private $div;
	private $link;

	public function HtmlTooltip($divId, $image = NULL, $linkText, $tooltipText, $classDiv) {
		$this->HtmlContainer();
		$this->div = new HtmlTooltipDiv($divId, $tooltipText, $classDiv);
		$this->link = new HtmlTooltipLink($linkText, $image, $divId);
		$this->addTooltipElements();
	}

	private function addTooltipElements() {
		$this->addElement($this->div);
		$this->addElement($this->link);
	}

	public function getText() {
		return $this->div->getText();
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: IndexedHtmlContainer
//--------------------------------------------------------------------------------------------------------------------//
interface IndexedHtmlContainer {
	public function getHtmlElement($key);
	public function getKeys();
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: AdministrableAddEditForm
//--------------------------------------------------------------------------------------------------------------------//
interface AdministrableAddEditForm extends IndexedHtmlContainer, HtmlElement {
	const EDIT_FORM = 0;
	const ADD_FORM = 1;
	const DELETE_FORM = 2;
	const METHOD_POST = "POST";
	const METHOD_GET = "GET";

	public function setTitle($title);
	public function getTitle();
	public function setAction($action);
	public function getAction();
	public function addField($key, $name, $field, $tooltipText);
	public function addInputHidden($name, $value);
	public function addBasicButtons($labelAction, $labelCancel);
	public function addMessage($message);
	public function addRowInOneCell($element);
	public function addButton($name, $label);
	public function createErrorMessage($errorDivName, $errorMessage);
	public function setName($name);
	public function addSubTitle($subtitle);
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledBox
//--------------------------------------------------------------------------------------------------------------------//
class StyledBox implements HtmlElement{

	private $table;
	private $tdHeader;
	private $tdContainer;
	private $tdFooter;

	public function StyledBox($title){
		$this->table = new HtmlTable();
		$this->table->setClass("comtorbox");

		$this->tdHeader = $this->table->addCell($title);
		$this->table->nextRow();

		$this->tdContainer = $this->table->addCell();
		$this->table->nextRow();

		$this->tdFooter = $this->table->addCell();

		$this->tdHeader->setClass("comtorboxheader");
		$this->tdContainer->setClass("comtorboxcontainer");
		$this->tdFooter->setClass("comtorboxfooter");
	}

	public function addElement($e) {
		$this->tdContainer->addElement($e);
		return $e;
	}

	public function getHtml() {
		return $this->table->getHtml();
	}

	public function getTable() {
		return $this->table;
	}

	public function setTable($table) {
		$this->table = $table;
	}

	public function getTdContainer() {
		return $this->tdContainer;
	}

	public function setTdContainer($tdContainer) {
		$this->tdContainer = $tdContainer;
	}

	public function getTdFooter() {
		return $this->tdFooter;
	}

	public function setTdFooter($tdFooter) {
		$this->tdFooter = $tdFooter;
	}

	public function getTdHeader() {
		return $this->tdHeader;
	}

	public function setTdHeader($tdHeader) {
		$this->tdHeader = $tdHeader;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: FormInAStyledBox
//--------------------------------------------------------------------------------------------------------------------//
class FormInAStyledBox implements AdministrableAddEditForm {

	private $mainBox;
	private $form;
	private $internalTable;
	private $map;
	private $errorDivs;
	private $action;
	private $title;

	public function FormInAStyledBox($title, $action, $method) {
		$this->errorDivs = new Vector();
		$this->form = new HtmlForm($action, $method);
		$this->mainBox = new StyledBox($title);
		$this->mainBox->getTdContainer()->addElement($this->form);
		$this->internalTable = new HtmlTable();
		$this->internalTable->setWidth("100%");
		$this->internalTable->setClass("FormInAStyledBox");
		$this->form->addElement($this->internalTable);
		$this->map = new HashMap();
	}

	public function getForm() {
		return $this->form;
	}

	public function setForm($form) {
		$this->form = $form;
	}

	public function setFormEnctype($enctype){
		$this->form->addAttribute("enctype",$enctype);
	}
	public function getHtml() {
		return $this->mainBox->getHtml();
	}

	public function addRow($error = NULL, $name_Col1, $field_Col2, $tip_Col3 = NULL){
		if ($error == NULL) {
			if(($name_Col1 instanceof HtmlElement) && ($field_Col2 instanceof HtmlElement) && ($tip_Col3 instanceof HtmlElement)){
				$this->addRow_Elements($name_Col1, $field_Col2, $tip_Col3);
			}
		} else {
			if ($tip_Col3 == NULL) {
				if (($error instanceof HtmlElement) && ($name_Col1 instanceof HtmlElement) && ($field_Col2 instanceof HtmlElement)) {
					$tip_Col3 = $field_Col2;
					$field_Col2 = $name_Col1;
					$name_Col1 = $error;
					$error = NULL;
					addRow($error = NULL, $name_Col1, $field_Col2, $tip_Col3);
				}
			} else if ($error instanceof HtmlDiv ) {
				if(($name_Col1 instanceof HtmlElement) && ($field_Col2 instanceof HtmlElement) && ($tip_Col3 instanceof HtmlElement)){
					$this->addRow_WithDiv($error, $name_Col1, $field_Col2, $tip_Col3);
				}
			} else if (is_string($error) && is_string($name_Col1)) {
				if ($tip_Col3 instanceof HtmlTooltip) {
					$this->addRow_WithTooltip($error, $name_Col1, $field_Col2, $tip_Col3);
				} else if (is_string($tip_Col3)) {
					$this->addRow_Standard($error, $name_Col1, $field_Col2, $tip_Col3);
				}
			}
		}
	}

	private function addRow_Elements($col1, $col2, $col3){
		$this->internalTable->addCell();
		$this->addRowData($col1, $col2, $col3);
		$this->internalTable->nextRow();
	}

	private function addRow_WithDiv($errorDiv, $col1, $col2, $col3) {
		$htmlTd = $this->internalTable->addCell($errorDiv);
		$htmlTd->addAttribute("class", "FormInAStyledBox");
		$this->addRowData($col1, $col2, $col3);
		$this->internalTable->nextRow();
		$this->errorDivs->add($errorDiv);
	}

	private function addRow_WithTooltip($key, $name, $field, $tooltip) {
		$this->addRow(new HtmlDiv($key . "_error"), new HtmlText($name), $field, $tooltip);
		$this->internalTable->nextRow();
		$this->map->put($key, new RowElements($name, $field, $tooltip->getText()));
		return $field;
	}

	private function addRow_Standard($key, $name, $field, $tooltipText) {
		$div = new HtmlDiv($key . "_error");
		$text = new HtmlText($name);
		$tip = new HtmlTooltip($key . "_div", HtmlTooltipLink::HELP_IMG, "", $tooltipText, HtmlTooltip::HELP_CLASS_DIV);
		$this->addRow($div, $text, $field, $tip);
		$this->internalTable->nextRow();
		$this->map->put($key, new RowElements($name, $field, $tooltipText));
		return $field;
	}

	private function addRowData($col1, $col2, $col3) {
		$htmlTd1 = $this->internalTable->addCell($col1);
		$htmlTd1->addAttribute("class", "FormInAStyledBox");
		$htmlTd2 = $this->internalTable->addCell($col2);
		$htmlTd2->addAttribute("class", "FormInAStyledBox");
		$htmlTd3 = $this->internalTable->addCell($col3);
		$htmlTd3->addAttribute("class", "FormInAStyledBox");
	}

	public function addMessage($message) {
		$htmlText = new HtmlText($message, false);
		$htmlTd = $this->internalTable->addCell($htmlText);
		$htmlTd->addAttribute("colspan", "4");
		$this->internalTable->nextRow();
	}

	public function addButtonsForAdd($label) {
		$this->addBasicButtons($label, "Cancel");
	}

	public function addButtonsForEdit($label) {
		$this->addBasicButtons($label, "Cancel");
	}

	public function addButtonsForDelete($label) {
		$this->addBasicButtons($label, "Cancel");
	}

	public function addBasicButtons($labelAction, $labelCancel) {
		$td = $this->internalTable->addCell(NULL, HtmlTd::VALIGN_CENTER);
		$td->addAttribute("colspan", "4");
		$td->addElement(new HtmlButton(HtmlButton::SUBMIT_BUTTON, "ok", $labelAction));
		$td->addElement(new HtmlButton(HtmlButton::SUBMIT_BUTTON, "cancel", $labelCancel));
		$this->internalTable->nextRow();
	}

	public function getField($key) {
		if ($this->map->get($key) != NULL) {
			return $this->map->get($key)->getField();
		}
		else {
			return NULL;
		}
	}

	public function getHtmlElement($key) {
		return $this->getField($key);
	}

	public function getKeys() {
		return $this->map->keySet();
	}

	public function addRowInOneCell($ele) {
		$htmlTd = $this->internalTable->addCell($ele);
		$htmlTd->addAttribute("colspan", "4");
		$this->internalTable->nextRow();
		return $htmlTd;
	}

	private function addRowInOneCellForButton($button) {
		$htmlTd = $this->internalTable->addCell($button, HtmlTd::VALIGN_CENTER);
		$htmlTd->addAttribute("colspan", "4");
		$this->internalTable->nextRow();
		return $htmlTd;
	}

	public function addButton($name, $label) {
		$button = new HtmlButton(HtmlButton::SUBMIT_BUTTON, $name, $label);
		$td = $this->addRowInOneCellForButton($button);
		$td->addAttribute("align", "center");
		return $button;
	}

	public function addInputHidden($name, $value) {
		$htmlInputHidden = new HtmlInputHidden($name, $value);
		$htmlInputHidden->setId($name);
		$this->form->addElement($htmlInputHidden);
		$this->map->put($name, new RowElements($name, $htmlInputHidden, ""));
	}

	public function setName($name) {
		$this->form->addAttribute("name", $name);
	}

	public function createErrorMessage($errorDivName, $errorMessage) {

		$error_array = $this->errorDivs->toArray();
		foreach ($error_array as $div) {
			$attributeValue = $div->getAttributeValue("id");
			if (($attributeValue != NULL) && ($attributeValue == $errorDivName)) {
				$tooltip = new HtmlTooltip($errorDivName . "_tt", HtmlTooltipLink::ERROR_IMG, "",$errorMessage, HtmlTooltip::ERROR_CLASS_DIV);

				$div->addElement($tooltip);
				break;
			}
		}
	}

	public function addField($key, $name, $field, $tooltipText) {
		return $this->addRow($key, $name, $field, $tooltipText);
	}

	public function setAction($action) {
		$this->action = $action;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function getAction() {
		return $this->action;
	}

	public function getTitle() {
		return $this->title;
	}

	public function addSubTitle($subtitle) {
		$this->addMessage("<hr>");
		$this->addMessage("<center><b>" . $subtitle . "</b></center>");
		$this->addMessage("<hr>");
	}


}

//--------------------------------------------------------------------------------------------------------------------//
// Class: RowElements
//--------------------------------------------------------------------------------------------------------------------//
class RowElements {
	var $name;
	var $field;
	var $help;

	public function RowElements($name, $field, $help) {
		$this->name = $name;
		$this->field = $field;
		$this->help = $help;
	}

	public function getField() {
		return $this->field;
	}

	public function setField($field) {
		$this->field = $field;
	}

	public function getHelp() {
		return $this->help;
	}

	public function setHelp($help) {
		$this->help = $help;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledBigTable
//--------------------------------------------------------------------------------------------------------------------//
class StyledBigTable implements HtmlElement {

	private $elements;
	private $headers;

	public function StyledBigTable() {
		$this->init();
	}

	private function init() {
		$this->elements = new Vector();
		$this->headers = new Vector();
	}

	public function setHeaders($headers) {
		$this->headers = $headers;
	}

	public function addHeader($o) {

		$this->headers->addElement($o);
	}


	public function addRow($row) {
		$this->elements->add($row);
	}

	public function getHtml() {
		$str = "<table class=\"adminlist\" >\n";
		$this->processHeaders($str);
		$this->processContents($str);
		$str .= "</table>";
		return $str;
	}

	private function processContents(&$str) {
		for ($i = 0; $i < $this->elements->size(); $i++) {
			if (($i % 2) == 0) {
				$str .= "<tr class=\"row0\" >\n     ";
			}
			else {
				$str .= "<tr class=\"row1\" >\n     ";
			}
			$element = $this->elements->elementAt($i);
			$size = 0;
			if (is_array($element)) {
				$size = count($element);
			} else if ($element instanceof Vector) {
				$size = $element->size();
			}

			for ($j = 0; $j < $size; $j++) {
				$data = NULL;
				if (is_array($element)) {
					$data = $element[$j];
				} else if ($element instanceof Vector) {
					$data = $element->elementAt($j);
				}
				$str .= "<td class=\"row1\" >" . (($data == NULL) ? "" : $data) . "</td>";
			}
			$str .= "</tr>\n";
		}
	}

	private function processHeaders(&$str) {
		if ($this->headers->size() > 0) {
			$str .= "<tr align='left'>\n     ";
			for ($i = 0; $i < $this->headers->size(); $i++) {
				$text = $this->headers->elementAt($i);
				$str .= "<th>" . $text . "</th>";
			}
			$str .="</tr>\n     ";
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledTable
//--------------------------------------------------------------------------------------------------------------------//
class StyledTable extends HtmlTable {

	public function StyledTable(){
		$this->HtmlTable();
		$this->setClass("comtorbox");
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledToolBar
//--------------------------------------------------------------------------------------------------------------------//
class StyledToolBar implements HtmlElement {

	var $items;
	var $forms;

	const ICON_NEW = "images/icons/small/document-new.png";
	const ICON_EXCEL = "images/icons/small/excel.png";
	const ICON_VIEW = "images/tango/edit-find.png";

	/**************************************************/

	const ICON_BACK = "images/icons/small/go-previous.png";

	/***************************Fin*************************/

	public function StyledToolBar() {
		$this->items = new Vector();
		$this->forms = new Vector();
	}

	public function addItem($url, $icon, $label) {
		$this->items->add(new StyledToolBarItem($url, $icon, $label));
	}

	public function getHtml() {
		$resp = "<table class=\"toolbar\"><tr>";
		$items_array = $this->items->toArray();
		foreach ($items_array as $item) {
			$resp .= $item->getHtml();
		}

		if ($this->forms->size() > 0) {
			$forms_array = $this->forms->toArray();
			foreach ($forms_array as $form) {
				$resp .= "<td class=\"toolbar\">" . $form->getHtml() . "</td>";
			}
		}

		$resp .= "</tr></table>";
		return $resp;
	}

	public function addForm($form) {
		$this->forms->add($form);
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledToolBarItem
//--------------------------------------------------------------------------------------------------------------------//
class StyledToolBarItem implements HtmlElement {
	private $url;
	private $icon;
	private $label;

	public function StyledToolBarItem($url, $icon, $label) {
		$this->url = $url;
		$this->icon = $icon;
		$this->label = $label;
	}

	public function getIcon() {
		return $this->icon;
	}

	public function setIcon($icon) {
		$this->icon = $icon;
	}

	public function getLabel() {

		return $this->label;
	}

	public function setLabel($label) {
		$this->label = $label;
	}

	public function getUrl() {
		return $this->url;
	}

	public function setUrl($url) {
		$this->url = $url;
	}

	public function getHtml() {
		return "<td class=\"toolbar\">" . "<a href=\"" . $this->getUrl() . "\">" . "<img src=\"" . $this->getIcon()
		. "\" border=\"0\">" . "<br>" . $this->getLabel() . "</a></td>";
	}

	public function toString() {
		return $this->getHtml();
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledToolBarWithElements
//--------------------------------------------------------------------------------------------------------------------//
class StyledToolBarWithElements extends StyledToolBar {
	var $elements;

	public function StyledToolBarWithElements() {
		$this->StyledToolBar();
		$this->elements = new Vector();
	}

	public function addHtmlElement($htmlElement) {
		$this->elements->add($htmlElement);
	}

	public function addItem($url, $icon, $label, $element, $onClick, $identifier) {
		$this->items->add(new StyledContainerToolBarItem($url, $icon, $label, $element, $onClick, $identifier));
	}

	public function getHtml() {
		$resp = "<table class=\"toolbar\"><tr>";
		$items_array = $this->items->toArray();
		foreach ($items_array as $item) {
			$resp .= $item->getHtml();
		}

		if ($this->forms->size() > 0) {
			$form_array = $this->forms->toArray();
			foreach ($form_array as $form) {
				$resp .= "<td class=\"toolbar\">" . $form->getHtml() . "</td>";
			}
		}

		if ($this->elements->size() > 0) {
			$elements_array = $this->elements->toArray();
			foreach ($elements_array as $htmlElement) {
				$resp .= "<td class=\"toolbar\">" . $htmlElement->getHtml() . "</td>";
			}
		}

		$resp .= "</tr></table>";
		return resp;
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: StyledContainerToolBarItem
//--------------------------------------------------------------------------------------------------------------------//
class StyledContainerToolBarItem extends StyledToolBarItem {

	private $innerElement;
	private $onClick;
	private $identifier;

	public function StyledContainerToolBarItem($url, $icon, $label, $element, $onClick, $identifier) {
		$this->StyledToolBarItem($url, $icon, $label);
		$this->innerElement = $element;
		$this->onClick = $onClick;
		$this->identifier = $identifier;
	}

	public function getInnerElement() {
		return $this->innerElement;
	}

	public function setInnerElement($innerElement) {
		$this->innerElement = $innerElement;
	}

	public function getHtml() {
		$html = "<td class=\"toolbar\">"
		. "<a "
		. (($this->getIdentifier() != NULL) ? " id =\"" . $this->getIdentifier() . "\" " . " name =\"" . $this->getIdentifier()
		. "\" " : "") . " href=\"" . $this->getUrl() . "\" "
		. (($this->getOnClick() != NULL) ? " onClick =\"" . $this->getOnClick() . "\" " : "") . "\">" . "<img src=\""
		. $this->getIcon() . "\" border=\"0\">" . "<br>" . $this->getLabel() . "</a></td>";
		if ($this->innerElement != NULL) {
			$html .= "<td class=\"toolbar\">" . $this->innerElement->getHtml() . "</td>";
		}

		return $html;
	}

	public function getOnClick() {
		return $this->onClick;
	}

	public function setOnClick($onClick) {
		$this->onClick = $onClick;
	}

	public function getIdentifier() {
		return $this->identifier;
	}

	public function setIdentifier($identifier) {
		$this->identifier = $identifier;
	}
}
// Class: HtmlGenericElement
//--------------------------------------------------------------------------------------------------------------------//
class HtmlGenericElement implements HtmlElement{
	var $myElem;
	public function HtmlGenericElement($texto){
		$this->myElem=$texto;
	}

	public function getHtml(){
		return $this->myElem;
	}


}



// Class: HtmlSelectFromQuery
//--------------------------------------------------------------------------------------------------------------------//
class HtmlSelectFromQuery extends HtmlSelect{

	public function HtmlSelectFromQuery($formElemName, $keyName, $label, $table, $moreQuery,$blankOption=false, $daoType="mysql"){
		$this->HtmlSelect($formElemName);
		$query= "select $keyName, $label from $table $moreQuery";
		$rows = DaoMgr::getDao($daoType)->executeQuery($query);
		if($blankOption){
			$this->addOption("-1", "-----");
		}
		foreach ($rows as $row){
			$this->addOption($row[$keyName], $row[$label]);
		}
	}

}

// Class: HtmlFCKEditor
//--------------------------------------------------------------------------------------------------------------------//
class HtmlFCKEditor implements HtmlElement, HtmlFormElement{
	var $oFCKeditor;
	var $attributes;
	public function HtmlFCKEditor($fieldName, $iniText){
		$this->oFCKeditor = new FCKeditor($fieldName) ;

		$this->oFCKeditor->BasePath	= './fckeditor/';
		$this->oFCKeditor->Value= $iniText;
		$this->oFCKeditor->Height=400;

	}
	public function getHtml(){

		return $this->oFCKeditor->CreateHtml();
	}

	public function setValue($value){

		$this->oFCKeditor->Value = $value;
	}

	public function getValue(){
		return $this->oFCKeditor->Value;


	}

	public function setName($name){
		$this->oFCKeditor->InstanceName= $name;
	}

	public function getName(){
		return $this->oFCKeditor->InstanceName;

	}

	public function setEnable($enable){

	}




}

class HtmlYesNoSelector extends HtmlSelect{



	public function HtmlYesNoSelector($name, $yes_label, $no_label){

		$this->HtmlSelect($name);
		$this->addOption('1',$yes_label);
		$this->addOption('0',$no_label);

	}

}

class HtmlParentIdSelector extends HtmlSelect{

	public function HtmlParentIdSelector($name){


		$rows= DaoMgr::getDao()->executeQuery("select id, name from menu_item where parent_id = -1");
		//var_dump($rows);
		$this->HtmlSelect($name);
		foreach($rows as $row){
			$menu_item = new Menu_item();
			$menu_item = DaoMgr::getDao()->getObject($menu_item, $row);
			$id_item = $menu_item->getId();
			$name_item = $menu_item->getName();
			echo "id item: ".$name_item;
			$this->addOption($id_item,$name_item);
		}


	}
}
?>

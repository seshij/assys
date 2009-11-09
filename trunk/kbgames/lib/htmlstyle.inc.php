<?php
include_once("html.inc.php");
class HtmlTableBox extends HtmlTable  {
	function HtmlTableBox($title){
		$this->HtmlTable();
		$this->addAttribute("width","100%");
		$this->addAttribute("class","htmltablebox");
		$cell = $this->addCell(new HtmlCenter(new HtmlText("Titulo")));
		$cell->addAttribute("class","htmltablebox.title");
		$this->nextRow();
								
	}
}


?>

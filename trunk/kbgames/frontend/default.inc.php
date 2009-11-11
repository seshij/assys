<?
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');

$tabla_contenido=new HtmlTable();
$tabla_contenido->addAttribute("id","contenido");
$tabla_contenido->addCell();
$tabla_contenido->nextRow();

echo $tabla_contenido->getHtml();
?>

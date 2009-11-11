<?
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');

$tabla_contenido=new HtmlTable();
$tabla_contenido->addAttribute("id","contenido");

$banner_negro=new HtmlDiv("banner_negro");
$banner_negro->addAttribute("class","ui-corner-all");
$tabla_contenido->addCell($banner_negro);
$tabla_contenido->nextRow();


$tabla_contenido->addCell();
$tabla_contenido->nextRow();
$tabla_contenido->addCell();
$tabla_contenido->nextRow();
$tabla_contenido->addCell();
$tabla_contenido->nextRow();
$tabla_contenido->addCell();
$tabla_contenido->nextRow();

echo $tabla_contenido->getHtml();
?>

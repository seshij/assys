<?
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');

$tabla_contenido=new HtmlTable();
$tabla_contenido->addAttribute('cellspacing','0');
$tabla_contenido->addAttribute('cellpadding','0');
$tabla_contenido->addAttribute('id','contenido');

//Falta poner donde meter la pelicula flash de 728 x 455
$banner_negro=new HtmlDiv('banner_negro');
$banner_negro->addAttribute('class','ui-corner-all');

$div_swf=new HtmlDiv("swf");

$banner_swf=new HtmlText("<script language='JavaScript' type='text/javascript'>
	AC_FL_RunContent(
		'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
		'width', '715',
		'height', '455',
		'src', 'banner_negro',
		'quality', 'high',
		'pluginspage', 'http://www.adobe.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'window',
		'devicefont', 'false',
		'id', 'banner_negro',
		'bgcolor', '#666666',
		'name', 'banner_negro',
		'menu', 'true',
		'allowFullScreen', 'false',
		'allowScriptAccess','sameDomain',
		'movie', 'banner_negro',
		'salign', ''
		); //end AC code
</script>
<noscript>
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0' width='715' height='455' id='banner_negro' align='middle'>
	<param name='allowScriptAccess' value='sameDomain' />
	<param name='allowFullScreen' value='false' />
	<param name='movie' value='banner_negro.swf' /><param name='quality' value='high' /><param name='bgcolor' value='#666666' />	<embed src='banner_negro.swf' quality='high' bgcolor='#666666' width='715' height='455' name='banner_negro' align='middle' allowScriptAccess='sameDomain' allowFullScreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />
	</object>
</noscript>");
$div_swf->addElement($banner_swf);
$banner_negro->addElement($div_swf);
$tabla_contenido->addCell($banner_negro);
$tabla_contenido->nextRow();

//Top 10
/*$banner_negro=new HtmlDiv('banner_negro');
$banner_negro->addAttribute('class','ui-corner-all');
$tabla_contenido->addCell($banner_negro);
$tabla_contenido->nextRow();*/


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

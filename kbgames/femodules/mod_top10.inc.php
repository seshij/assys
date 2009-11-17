<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$top10=new HtmlContainer();

//Tabla que contiene todo (Titulo de encuesta y Encuesta)
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_top10");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");

//Titulo de la encuesta en un DIV dentro de la primera fila de la tabla
$div=new HtmlDiv("titulo_top10");
$div->addAttribute("class", "ui-widget ui-widget-header ui-corner-top");
$div_titulo=new HtmlDiv("divimg_titulo");
$div_titulo->addElement(new HtmlText("<img src='images/frontend/header_top10.jpg'>"));
$div->addElement($div_titulo);
$table->addCell($div);
$table->nextRow();

//DIV que contiene una tabla donde se genera la pregunta y opciones de la encuesta
$div=new HtmlDiv("top10_swf");
$div->addAttribute("class", "ui-corner-bottom");
$banner_top10=new HtmlText("<script language='JavaScript' type='text/javascript'>
	AC_FL_RunContent(
		'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
		'width', '728',
		'height', '237',
		'src', 'swf/top_juegos',
		'quality', 'high',
		'pluginspage', 'http://www.adobe.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'window',
		'devicefont', 'false',
		'id', 'swf/top_juegos',
		'bgcolor', '#ffffff',
		'name', 'swf/top_juegos',
		'menu', 'true',
		'allowFullScreen', 'false',
		'allowScriptAccess','sameDomain',
		'movie', 'media/swf/top_juegos',
		'salign', ''
		); //end AC code
</script>
<noscript>
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0' width='728' height='237' id='swf/top_juegos' align='middle'>
	<param name='allowScriptAccess' value='sameDomain' />
	<param name='allowFullScreen' value='false' />
	<param name='movie' value='media/swf/top_juegos.swf' /><param name='quality' value='high' /><param name='bgcolor' value='#ffffff' />	<embed src='media/swf/top_juegos.swf' quality='high' bgcolor='#ffffff' width='728' height='237' name='swf/top_juegos' align='middle' allowScriptAccess='sameDomain' allowFullScreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />
	</object>
</noscript>");
$div->addElement($banner_top10);
$table->addCell($div);
$table->nextRow();

$top10->addElement($table);
echo $top10->getHtml();
?>

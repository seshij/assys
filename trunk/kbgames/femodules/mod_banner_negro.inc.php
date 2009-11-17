<?php
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');

$banner_principal=new HtmlDiv('banner_negro');
$banner_principal->addAttribute('class','ui-corner-all');

$div_swf=new HtmlDiv("swf");
$banner_swf=new HtmlText("<script language='JavaScript' type='text/javascript'>
	AC_FL_RunContent(
		'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0',
		'width', '715',
		'height', '455',
		'src', 'swf/banner_principal',
		'quality', 'high',
		'pluginspage', 'http://www.adobe.com/go/getflashplayer',
		'align', 'middle',
		'play', 'true',
		'loop', 'true',
		'scale', 'showall',
		'wmode', 'window',
		'devicefont', 'false',
		'id', 'swf/banner_principal',
		'bgcolor', '#ffffff',
		'name', 'swf/banner_principal',
		'menu', 'true',
		'allowFullScreen', 'false',
		'allowScriptAccess','sameDomain',
		'movie', 'media/swf/banner_principal',
		'salign', ''
		); //end AC code
</script>
<noscript>
	<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0' width='715' height='455' id='swf/banner_principal' align='middle'>
	<param name='allowScriptAccess' value='sameDomain' />
	<param name='allowFullScreen' value='false' />
	<param name='movie' value='media/swf/banner_principal.swf' /><param name='quality' value='high' /><param name='bgcolor' value='#ffffff' />	<embed src='media/swf/banner_principal.swf' quality='high' bgcolor='#ffffff' width='715' height='455' name='swf/banner_principal' align='middle' allowScriptAccess='sameDomain' allowFullScreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />
	</object>
</noscript>");
$div_swf->addElement($banner_swf);
$banner_principal->addElement($div_swf);

echo $banner_principal->getHtml();
?>

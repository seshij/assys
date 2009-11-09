<?php
include_once("dao.inc.php");
include_once ("html.inc.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Front End del Portal</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="tooltip/tooltip.css" type="text/css" />

<!-- JSCookMenu GRAY -->
<SCRIPT TYPE='text/javascript' SRC='JSCookMenu/effect.js'></SCRIPT>
<SCRIPT TYPE='text/javascript' SRC='JSCookMenu/JSCookMenu.js'></SCRIPT>


<script language="JavaScript" src="calendar1.js"></script>
<script language="JavaScript" src="codefinder.js"></script>
<script language="JavaScript" src="tooltip/tooltip.js"></script>


<LINK REL='stylesheet' HREF='JSCookMenu/ThemePanel/theme.css'
	TYPE='text/css'>
<SCRIPT SRC='JSCookMenu/ThemePanel/theme.js' TYPE='text/javascript'></SCRIPT>
<LINK REL='stylesheet' HREF='JSCookMenu/ThemeGray/theme.css'
	TYPE='text/css'>
<SCRIPT SRC='JSCookMenu/ThemeGray/theme.js' TYPE='text/javascript'></SCRIPT>
</head>
<body>

</body>
<?php
function getMenuList(){
	$rows = DaoMgr::getDao()->executeQuery("select name,orientation,root_menu_item from menu");

	foreach($rows as $row){
		$menu = "['', 'Noticias', 'admin.php?option=news', '_self', 'descripcion'],";

		echo "
		<script type='text/javascript'>

		var  MenuPrincipal = [$menu];

		</script>

		<script type='text/javascript'>
			cmDraw ('MenuPrincipalID', MenuPrincipal, 'hbr', cmThemeGray);
		</script>";
		
	}

}
?>
</html>
<?
include_once("lib/cmsadmin.php");
echo($_SERVER['DOCUMENT_ROOT']);
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>KB Games</title>
<link rel="stylesheet" href="css/main.css">
<link rel="stylesheet" href="tooltip/tooltip.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/redmond.datepick.css" type="text/css" />

<!-- JSCookMenu GRAY -->
<SCRIPT TYPE='text/javascript' SRC='scripts/jquery.js'></SCRIPT>
<script type="text/javascript" src="jquery.ui/ui.core.js"></script>
<script type="text/javascript" src="jquery.ui/ui.dialog.js"></script>
<link rel="stylesheet" href="jquery.themes/assys/ui.all.css" type="text/css">

<SCRIPT TYPE='text/javascript' SRC='scripts/functions.js'></SCRIPT>

<SCRIPT TYPE='text/javascript' SRC='JSCookMenu/effect.js'></SCRIPT>
<SCRIPT TYPE='text/javascript' SRC='JSCookMenu/JSCookMenu.js'></SCRIPT>


<script type='text/javascript' language="JavaScript" src="scripts/calendar1.js"></script>
<script type='text/javascript' language="JavaScript" src="scripts/codefinder.js"></script>
<script type='text/javascript' language="JavaScript" src="scripts/ajaxStuff.js"></script>
<script type='text/javascript' language="JavaScript" src="tooltip/tooltip.js"></script>
<script type='text/javascript' language="JavaScript" src="scripts/menu.js"></script>

<script type='text/javascript' language="javascript" src="scripts/jquery.validate.js"></script>
<script type='text/javascript' language="javascript" src="scripts/validator.js"></script>
<script type="text/javascript" language="javascript" src="scripts/cumplepicker.js"></script>
<script type="text/javascript" language="javascript" src="scripts/jquery.datepick.js"></script>
<script type="text/javascript" language="javascript" src="scripts/jquery.datepick-es.js"></script>
<script type='text/javascript' language="javascript" src="scripts/fotoAdministrator.js"></script>
<script type='text/javascript' language="javascript" src="scripts/ajaxupload.3.5.js"></script>


<LINK REL='stylesheet' HREF='JSCookMenu/ThemeCSAb/theme.css' TYPE='text/css'>
<SCRIPT SRC='JSCookMenu/ThemeCSAb/theme.js' TYPE='text/javascript'></SCRIPT>

</head>

<body>

<div>
<script type="text/javascript">
<!--
var myMenu2 =
[
	['', 'Home', 'admin.php', '_self', ''],
	['', 'Administrar', '', '_self', '',
		['', 'Categorias', 'admin.php?option=categorias', '_self', ''],
		['', 'Juegos', 'admin.php?option=menu_juegos', '_self', ''],
                ['', 'Club KBGames', 'admin.php?option=miembros', '_self', ''],
                ['', 'Noticias y Eventos', 'admin.php?option=noticias', '_self', ''],
	],
	['', 'Interno', '', '_self', '',
		['', 'Back-End', 'admin.php?option=back_end_user', '_self', ''],
		['', 'Roles', 'admin.php?option=rol', '_self', ''],
		['', 'Logs', 'admin.php?option=logs', '_self', ''],
	],
	['', 'Log Out', 'admin.php?action=logout', '_self', '']
	
];
-->
</script>





<table align="center" width="100%" border="0" cellpadding="0"
	cellspacing="0" bgcolor="#FFFFFF">

	<tr>
		<td height="100" valign="top" align="center" bgcolor="black"></td>
	</tr>
	<tr>
		<td></td>
	</tr>
	<tr>
		<td valign="top" id="MainMenu" height="30"><?php //Aqui debe ir el menu

			if(isset($_SESSION["user"])){
				echo "<div id=\"myMenu2\"></div>
						<script type=\"text/javascript\">
						<!--
						cmDraw ('myMenu2', myMenu2, 'hbr', cmThemeCSAb);
						--></script>";
			}
?></td>
	</tr>

	<tr>
		<td align="right">
		<div style="font-size: 11px;"><?php	
		if(isset($_SESSION["user"])){
			$user=$_SESSION["user"];
			print_r("Usuario:".$user->name."&nbsp;&nbsp;&nbsp;");
		}
		?></div>
		</td>
	</tr>

	<tr>

		<td height="300" valign="top" align="center" id="mainCell">


		<br>
		<?php
		adminContent();
		?> <br>
		</td>
	</tr>

</table>
</div>
<div id="dialogo"></div>
</body>
</html>

<?
include_once 'main.inc.php';
include_once("lib/cfrontend.php");
include_once("lib/html.inc.php");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>KBGames</title>

        <link rel="stylesheet" href="css/frontend.css">
        <link rel="stylesheet" href="css/uitheme/jquery-custom.css" type="text/css">

        <script type="text/javascript" language="JavaScript" src="css/uitheme/jquery-custom.js"></script>
        <script type='text/javascript' language="JavaScript" src='scripts/jquery.js'></script>
        <script type='text/javascript' language="JavaScript" src="scripts/menu.js"></script>
        <script type="text/javascript" language="JavaScript" src="scripts/DD_roundies.uicornerfix.js"></script>
        <script type="text/javascript">
            $.uicornerfix('15px');
        </script>
    </head>
    <body>
            <table align="center" width="1050" border="1" cellspacing="0" cellpadding="0" id="contenedor">
                <tr>
                    <td>
                        <table align="center" width="90%" border="0" cellspacing="0" cellpadding="0" class="tblppal">
                            <tr>
                                <td colspan="2">
                                    <?
                                    require("femodules/mod_header.inc.php");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="290" style="vertical-align:top">
                                    <table width="250" style="margin-left:40px">
                                        <?
                                        require("femodules/mod_menu.inc.php");
                                        require("femodules/mod_encuesta.inc.php");
                                        require("femodules/mod_sorpresa.inc.php");
                                        ?>
                                    </table>
                                </td>
                                <td style="vertical-align:top">
                                    <?php
                                    indexContent();
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <?
                                    //$cabezote=new HtmlFlashMovie("images/frontend/pieindex.swf",710,103,"pieindex","images/frontend/pieindex");
                                    $cabezote=new HtmlImg("images/frontend/banner.jpg");
                                    echo $cabezote->getHtml();
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?
                        $cabezote=new HtmlImg("images/frontend/footer.jpg");
                        echo $cabezote->getHtml();
                        ?>
                    </td>
                </tr>
            </table>
    </body>
</html>

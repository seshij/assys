<?php
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');
?>
<table cellspacing="0" cellpadding="0" id="contenido">
    <tr>
        <td>
            <?php
            require("femodules/mod_banner_negro.inc.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            require("femodules/mod_top10.inc.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <?php
                        require("femodules/mod_registrate.inc.php");
                        ?>
                    </td>
                    <td style="vertical-align:top">
                        <?php
                        require("femodules/mod_noticias.inc.php");
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            require("femodules/mod_soporte_juega.inc.php");
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <?php
            require("femodules/mod_sorpresa.inc.php");
            ?>
        </td>
    </tr>
</table>



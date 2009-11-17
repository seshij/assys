<?php
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');
?>
<table cellspacing='0' cellpadding='0' width="100%" style="text-align:center">
    <tr>
        <td>
            <div id='above_footer' class='ui-corner-all'>
                <?php
                $cabezote=new HtmlImg("images/frontend/banner.jpg");
                echo $cabezote->getHtml();
                ?>
            </div>
        </td>
    </tr>
</table>



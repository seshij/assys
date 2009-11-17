<?php
include_once ('lib/html.inc.php');
include_once ('lib/util.inc.php');
include_once('components/administrable.inc.php');


class PSPFEController extends HtmlGuiInterface {
    var $theContainer;

    public function PSPFEController() {
        $this->theContainer=new HtmlContainer();

    }

    public function getHtml() {
        $this->getPSP();
        return $this->theContainer->getHtml();
    }

    public function requireComtorSession() {
        return true;
    }

    public function getPSP() {
        $contenido=new HtmlText("<table width='100' border='0' cellspacing='20'>
  <tr>
    <td colspan='3'><object id='FlashID' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' width='715' height='343'>
      <param name='movie' value='banner_psp.swf' />
      <param name='quality' value='high' />
      <param name='wmode' value='opaque' />
      <param name='swfversion' value='6.0.65.0' />
      <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
      <param name='expressinstall' value='Scripts/expressInstall.swf' />
      <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
      <!--[if !IE]>-->
      <object type='application/x-shockwave-flash' data='banner_psp.swf' width='715' height='343'>
        <!--<![endif]-->
        <param name='quality' value='high' />
        <param name='wmode' value='opaque' />
        <param name='swfversion' value='6.0.65.0' />
        <param name='expressinstall' value='Scripts/expressInstall.swf' />
        <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
        <div>
          <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
          <p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' width='112' height='33' /></a></p>
        </div>
        <!--[if !IE]>-->
      </object>
      <!--<![endif]-->
    </object></td>
  </tr>
  <tr>
    <td colspan='3' class='backtopwii'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='3'><img src='combo1psp.jpg' width='715' height='220' alt='combo 1 Psp go' /></td>
  </tr>
  <tr>
    <td colspan='3'><img src='combo2psp.jpg' width='715' height='220' /></td>
  </tr>
  <tr>
    <td colspan='3'><img src='combo3psp.jpg' width='715' height='220' /></td>
  </tr>
  <tr>
    <td colspan='3' class='backfooterjuegos'>&nbsp;</td>
  </tr>
</table>
<script type='text/javascript'>
<!--
swfobject.registerObject('FlashID');
//-->
</script>");
        $this->theContainer->addElement($contenido);
    }
}
?>
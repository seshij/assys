<?php
include_once("lib/html.inc.php");
include_once("lib/dao.inc.php");

$encuesta=new HtmlContainer();
$table = new HtmlTable();
$table->addAttribute("id", "contenedor_twitter");
$table->addAttribute("cellspacing", "0");
$table->addAttribute("cellpadding", "0");

$div=new HtmlDiv("twitter");
$widget=new HtmlText("<script src='http://widgets.twimg.com/j/2/widget.js'></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 5,
  interval: 6000,
  width: 245,
  height: 370,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#000000',
      color: '#ffffff',
      links: '#eb125b'
    }
  },
  features: {
    scrollbar: false,

     loop: false,
    live: true,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setUser('kbgamescolombia').start();
</script>");
$div->addElement($widget);
$table->addCell($div);
$table->nextRow();
$td=new HtmlTd();
$td->addElement($table);
$tr=new HtmlTr();
$tr->addElement($td);
$encuesta->addElement($tr);
echo $encuesta->getHtml();
?>

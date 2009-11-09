<?
include_once('lib/html.inc.php');
include_once('lib/dao.inc.php');
/*$elDiv=new HtmlDiv("newsDiv");
$elDiv->setClass("news");
$rows=DaoMgr::getDao()->executeQuery("select * from news order by id desc limit 5");
foreach($rows as $row){
	$elDiv->addElement(new HtmlLink($row['title'], "?option=news&myId=".$row['id'],"images/icons/small/edit-redo.png"));
	$elDiv->addElement(new HtmlBr());
}*/
$cabezote=new HtmlImg("images/frontend/principal.jpg");
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>
  <tr>
    <td>".$cabezote->getHtml()."</td>
    </tr>
</table>";
?>

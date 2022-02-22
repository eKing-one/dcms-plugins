<?


if (mysql_result(mysql_query("SELECT COUNT(*) FROM `rekl` WHERE `sel` = '1' AND `time_last` > '".time()."'"),0)>0)
{
?><div class="moduletable">
			<h3>Партнеры</h3><?
echo "<div class='rekl_main'>\n";



$q_rekl=mysql_query("SELECT * FROM `rekl` WHERE `sel` = '1' AND `time_last` > '".time()."' ORDER BY id ASC");
while ($post_rekl = mysql_fetch_array($q_rekl))
{

if ($post_rekl['dop_str']==1)
echo "<a target='_blank' href='/go.php?go=$post_rekl[id]'>";
else
echo "<a target='_blank' href='$post_rekl[link]'>";

if ($post_rekl['img']==NULL)echo "$post_rekl[name]";
else echo "<img src='$post_rekl[img]' alt='$post_rekl[name]' />";
echo "</a><br />\n";
}


echo "</div>\n";
?></div><?
}



if ($set['rekl']=='mobiads'){
if (isset ($set['mobiads_id']) && $set['mobiads_id']!=0 && isset ($set['mobiads_num_links']) && $set['mobiads_num_links']!=0){
?><div class="moduletable">
			<h3>Реклама</h3><?
echo "<div class='rekl_main'>\n";

echo "<!--mobiads.ru $set[mobiads_id]-->\n"; 
include_once H.'sys/inc/mobiads.php';
$ads = @get_ads($set['mobiads_id'], $set['mobiads_code'],$set['mobiads_num_links']); 
if($ads['STATUS'] == 'OK') 
foreach($ads['ADS'] as $link) 
{ 
echo '<a target="_blank" href="'.htmlspecialchars($link[0], ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($link[1], ENT_QUOTES, 'UTF-8')."</a><br />\n"; 
} 
else echo $ads['DESCRIPTION']; 
echo "</div>\n";
?></div><?
}
}
if ($set['rekl']=='wappc' && $set['wappc_num_links']!=0){
?><div class="moduletable">
			<h3>Реклама</h3><?
echo "<div class='rekl_main'>\n";
echo "<!-- http://wappc.biz/?uid=".ereg_replace('\..*$','',$set['wappc_id'])." -->";
include_once H.'sys/inc/libwappc3.php';
global $wappc3_curl;
$wappc3_curl=0;
print GetFeedWAPPC3($set['wappc_num_links'],array('charset'=>'utf-8','temp'=>H.'sys/tmp','aff'=>"$set[wappc_id]",'empty'=>"<a target='_blank' href='http://wappc.biz/partner.php?uid=5408'>Заработок WAP-мастеру</a>",'template'=>'%code%','sep'=>'<br />','topbid'=>1,'operator'=>1));

echo "</div>\n";
?></div><?
}
?>
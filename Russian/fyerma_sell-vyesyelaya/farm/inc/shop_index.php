<?php
echo "<div class='rowup'><img src='/farm/img/garden.png' alt='' /> <a href='/farm/garden/'>Ваш огород</a> / <b>Покупаем семена</b></div>";
$k_post=dbresult(dbquery("SELECT COUNT(*) FROM `farm_plant`"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];



$res = dbquery("select * from `farm_plant` LIMIT $start, $set[p_str];"); 
$arr = dbarray(dbquery("select * from `farm_plant` LIMIT $start, $set[p_str]"));

$num=0;
while ($post = dbarray($res)){

$timediff=$post['time'];
$oneMinute=60; 
$oneHour=60*60; 
$oneDay=60*60*24; 
$dayfield=floor($timediff/$oneDay); 
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour); 
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute); 
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute)); 
if($dayfield>0)$day=$dayfield.'д. ';
if($minutefield>0)$minutefield=$minutefield."м.";else$minutefield='';
$time_arr=$day.$hourfield."ч. ".$minutefield;

if ($num==1)
{
echo "<div class='rowdown'>";
$num=0;
}
else
{
echo "<div class='rowup'>";
$num=1;
} 

if ($level>=$post['level'])
{
$lvl="<font color='green'>$post[level] уровень</font>";
}
else
{
$lvl="<font color='red'>Доступно с $post[level] уровня</font>";
}
echo "<table class='post'>";
echo "<tr><td rowspan='2' style='width:30px'><img src='/farm/plants/$post[id].png' height='30' width='30'></td><td> <a href='/farm/shop/plant/$post[id]'>".$post['name']."</a> (".$lvl.")</td></tr>";
echo "<tr><td><small>растет $time_arr, опыт $post[oput], урожай $post[rand1]</small></td></tr></table></div>";
unset($day);
}


if ($k_page>1)str('?',$k_page,$page); // Вывод страниц


?>
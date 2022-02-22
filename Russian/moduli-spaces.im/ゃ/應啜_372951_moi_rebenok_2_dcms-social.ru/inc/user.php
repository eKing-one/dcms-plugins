<?
/*
Запрещено распространять скрипт в любом виде и под любым предлогом!
*/
if (!isset($user))
{
echo "<div class='err'>";
echo '<b>Игра "Мой Ребёнок" доступна только для обитателей сайта!</b>';
echo "</div>";
include_once '../sys/inc/tfoot.php';
exit;
}
$ba=mysql_query("SELECT * FROM `baby` WHERE `mama` > '0' OR `papa` > '0'");
while ($bab=mysql_fetch_assoc($ba))
{
if (time()>$bab['health_time'] && time()>$bab['happy_time'] && time()>$bab['eda_time'] && $bab['health']==0 && $bab['happy']==0 && $bab['eda']==0)
{
mysql_query("UPDATE `baby` SET `mama` = '0' WHERE `id` = '".$bab['id']."'");
mysql_query("UPDATE `baby` SET `papa` = '0' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['health_time'] && $bab['health']>0)
{
$t_1=time()+2100;
mysql_query("UPDATE `baby` SET `health_time` = '".$t_1."' WHERE `id` = '".$bab['id']."'");
if ($bab['health']>=5)
{
$health=$bab['health']-5;
}else{
$health=0;
}
mysql_query("UPDATE `baby` SET `health` = '".$health."' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['happy_time'] && $bab['happy']>0)
{
$t_2=time()+2000;
mysql_query("UPDATE `baby` SET `happy_time` = '".$t_2."' WHERE `id` = '".$bab['id']."'");
if ($bab['happy']>=6)
{
$happy=$bab['happy']-6;
}else{
$happy=0;
}
mysql_query("UPDATE `baby` SET `happy` = '".$happy."' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['eda_time'] && $bab['eda']>0)
{
$t_3=time()+1800;
mysql_query("UPDATE `baby` SET `eda_time` = '".$t_3."' WHERE `id` = '".$bab['id']."'");
if ($bab['eda']>=7)
{
$eda=$bab['eda']-7;
}else{
$eda=0;
}
mysql_query("UPDATE `baby` SET `eda` = '".$eda."' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['progulka'] && $bab['progulka']!=0)
{
mysql_query("UPDATE `baby` SET `progulka` = '0' WHERE `id` = '".$bab['id']."'");
$iq=$bab['iq']+1;
mysql_query("UPDATE `baby` SET `iq` = '".$iq."' WHERE `id` = '".$bab['id']."'");
if ($bab['health']<=90)
{
$health=$bab['health']+10;
}else{
$health=100;
}
mysql_query("UPDATE `baby` SET `health` = '".$health."' WHERE `id` = '".$bab['id']."'");
if ($bab['eda']>=7)
{
$eda=$bab['eda']-7;
}else{
$eda=0;
}
mysql_query("UPDATE `baby` SET `eda` = '".$eda."' WHERE `id` = '".$bab['id']."'");
if ($bab['happy']>=9)
{
$happy=$bab['happy']-9;
}else{
$happy=0;
}
mysql_query("UPDATE `baby` SET `happy` = '".$happy."' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['play'] && $bab['play']!=0)
{
mysql_query("UPDATE `baby` SET `play` = '0' WHERE `id` = '".$bab['id']."'");
$iq=$bab['iq']+1;
mysql_query("UPDATE `baby` SET `iq` = '".$iq."' WHERE `id` = '".$bab['id']."'");
if ($bab['health']<=97)
{
$health=$bab['health']+3;
}else{
$health=100;
}
mysql_query("UPDATE `baby` SET `health` = '".$health."' WHERE `id` = '".$bab['id']."'");
if ($bab['eda']>=4)
{
$eda=$bab['eda']-4;
}else{
$eda=0;
}
mysql_query("UPDATE `baby` SET `eda` = '".$eda."' WHERE `id` = '".$bab['id']."'");
if ($bab['happy']>=6)
{
$happy=$bab['happy']-6;
}else{
$happy=0;
}
mysql_query("UPDATE `baby` SET `happy` = '".$happy."' WHERE `id` = '".$bab['id']."'");
}
if (time()>$bab['skazka'] && $bab['skazka']!=0)
{
mysql_query("UPDATE `baby` SET `skazka` = '0' WHERE `id` = '".$bab['id']."'");
$iq=$bab['iq']+1;
mysql_query("UPDATE `baby` SET `iq` = '".$iq."' WHERE `id` = '".$bab['id']."'");
if ($bab['eda']>=5)
{
$eda=$bab['eda']-5;
}else{
$eda=0;
}
mysql_query("UPDATE `baby` SET `eda` = '".$eda."' WHERE `id` = '".$bab['id']."'");
if ($bab['happy']<=97)
{
$happy=$bab['happy']+3;
}else{
$happy=0;
}
mysql_query("UPDATE `baby` SET `happy` = '".$happy."' WHERE `id` = '".$bab['id']."'");
}
}
$ba_2=mysql_query("SELECT * FROM `baby` WHERE `mama` = '0' AND `papa` = '0'");
while ($bab_2=mysql_fetch_assoc($ba_2))
{
mysql_query("DELETE FROM `baby_sp` WHERE `id_baby` = '".$bab_2['id']."'");
}
function bab_time($t)
{
$it=intval($t);
$bab=mysql_fetch_array(mysql_query("SELECT * FROM `baby` WHERE `id` = '".$it."' LIMIT 1"));
$timediff=time()-$bab['time'];
$oneMinute=60;
$oneHour=60*60;
$oneDay=60*60*24;
$dayfield=floor($timediff/$oneDay);
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour);
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute);
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute));
$sDaysLeft=$dayfield;
$sDaysText="дней";
$nDaysLeftLength=strlen($sDaysLeft);
$d_1=substr($sDaysLeft,-1,1);
if (substr($sDaysLeft,-2,1) !=1 && $nDaysLeftLength>1)
{
if ($d_1==2 || $d_1==3 || $d_1==4)
{
$sDaysText="дня";
}
else if ($d_1==1)
{
$sDaysText="день";
}
}
if ($nDaysLeftLength==1)
{
if ($d_1==2 || $d_1==3 || $d_1==4)
{
$sDaysText="дня";
}
else if ($d_1==1)
{
$sDaysText="день";
}
}
$sHoursLeft=$hourfield;
$sHoursText="часов";
$nHoursLeftLength=strlen($sHoursLeft);
$h_1=substr($sHoursLeft,-1,1);
if (substr($sHoursLeft,-2,1) !=1 && $nHoursLeftLength>1)
{
if ($h_1==2 || $h_1==3 || $h_1==4)
{
$sHoursText="часа";
}
else if ($h_1==1)
{
$sHoursText="час";
}
}
if ($nHoursLeftLength==1)
{
if ($h_1=2 || $h_1==3 || $h_1==4)
{
$sHoursText="часа";
}
else if ($h_1==1)
{
$sHoursText="час";
}
}
$sMinsLeft=$minutefield;
$sMinsText="минут";
$nMinsLeftLength=strlen($sMinsLeft);
$m_1=substr($sMinsLeft,-1,1);
if ($nMinsLeftLength>1 && substr($sMinsLeft,-2,1)!=1)
{
if ($m_1==2 || $m_1==3 || $m_1==4)
{
$sMinsText="минуты";
}
else if ($m_1==1)
{
$sMinsText="минута";
}
}
if ($nMinsLeftLength==1)
{
if ($m_1==2 || $m_1==3 || $m_1==4)
{
$sMinsText="минуты";
}
else if ($m_1=="1")
{
$sMinsText="минута";
}
}
$sSecsLeft=$secondfield;
$sSecsText="секунд";
$s_1=substr($sSecsLeft,-1,1);
$nSecsLeftLength = strlen($sSecsLeft);
if (substr($sSecsLeft,-2,1)!=1 && $nSecsLeftLength>1)
{
if ($s_1==2 || $s_1==3 || $s_1==4)
{
$sSecsText="секунды";
}
else if ($s_1==1)
{
$sSecsText="секунда";
}
}
if ($nSecsLeftLength==1)
{
if ($s_1==2 || $s_1==3 || $s_1==4)
{
$sSecsText="секунды";
}
else if ($sSecsLeft=="1")
{
$sSecsText="секунда";
}
}
if ($timediff<=0)
{
$bab_time='Ошибка во времени!';
}else{
if ($sDaysLeft>0)
{
$denj="".$sDaysLeft." ".$sDaysText." ";
}else{
$denj="";
}
if ($sHoursLeft>0)
{
$chas="".$sHoursLeft." ".$sHoursText." ";
}else{
$chas="";
}
if ($sMinsLeft>0)
{
$min="".$sMinsLeft." ".$sMinsText." ";
}else{
$min="";
}
if ($sSecsLeft>0)
{
$sec="".$sSecsLeft." ".$sSecsText."";
}else{
$sec="";
}
$bab_time="".$denj."".$chas."".$min."".$sec."";
}
return $bab_time;
}
?>
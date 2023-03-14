<?php
$chk=dbresult(dbquery("SELECT COUNT(*) FROM `farm_user` WHERE `uid` = '".$user['id']."'"), 0);

if ($chk==0)
{
header("Location: /farm/welcome");
exit();
}

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));


if($fuser['exp']>=0 && $fuser['exp']<=150 || $fuser['exp']<=0 && $fuser['exp']<=150)$level=0;
elseif($fuser['exp']>=151 && $fuser['exp']<=300)$level=1;
elseif($fuser['exp']>=301 && $fuser['exp']<=800)$level=2;
elseif($fuser['exp']>=801 && $fuser['exp']<=1300)$level=3;
elseif($fuser['exp']>=1301 && $fuser['exp']<=2000)$level=4;
elseif($fuser['exp']>=2001 && $fuser['exp']<=3000)$level=5;
elseif($fuser['exp']>=3001 && $fuser['exp']<=4500)$level=6;
elseif($fuser['exp']>=4501 && $fuser['exp']<=6000)$level=7;
elseif($fuser['exp']>=6001 && $fuser['exp']<=9000)$level=8;
elseif($fuser['exp']>=9001 && $fuser['exp']<=12000)$level=9;
elseif($fuser['exp']>=12001 && $fuser['exp']<=16000)$level=10;
elseif($fuser['exp']>=16001 && $fuser['exp']<=20000)$level=11;
elseif($fuser['exp']>=20001 && $fuser['exp']<=25000)$level=12;
elseif($fuser['exp']>=25001 && $fuser['exp']<=30000)$level=13;
elseif($fuser['exp']>=30001 && $fuser['exp']<=35000)$level=14;
elseif($fuser['exp']>=35001 && $fuser['exp']<=40000)$level=15;
elseif($fuser['exp']>=40001 && $fuser['exp']<=50000)$level=16;
elseif($fuser['exp']>=50001 && $fuser['exp']<=60000)$level=17;
elseif($fuser['exp']>=60001 && $fuser['exp']<=70000)$level=18;
elseif($fuser['exp']>=70001 && $fuser['exp']<=85000)$level=19;
elseif($fuser['exp']>=85001 && $fuser['exp']<=90000)$level=20;
elseif($fuser['exp']>=90001 && $fuser['exp']<=105000)$level=21;
elseif($fuser['exp']>=105001 && $fuser['exp']<=115000)$level=22;
elseif($fuser['exp']>=115001 && $fuser['exp']<=130000)$level=23;
elseif($fuser['exp']>=130001 && $fuser['exp']<=155000)$level=24;
elseif($fuser['exp']>=155001 && $fuser['exp']<=170000)$level=25;
elseif($fuser['exp']>=170001 && $fuser['exp']<=190000)$level=26;
elseif($fuser['exp']>=190001 && $fuser['exp']<=210000)$level=27;
elseif($fuser['exp']>=210001 && $fuser['exp']<=230000)$level=28;
elseif($fuser['exp']>=230001 && $fuser['exp']<=250000)$level=29;
elseif($fuser['exp']>=250001 && $fuser['exp']<=270000)$level=30;
elseif($fuser['exp']>=270001 && $fuser['exp']<=290000)$level=31;
elseif($fuser['exp']>=290001 && $fuser['exp']<=320000)$level=32;
elseif($fuser['exp']>=320001 && $fuser['exp']<=340000)$level=33;
elseif($fuser['exp']>=340001 && $fuser['exp']<=360000)$level=34;
elseif($fuser['exp']>=360001 && $fuser['exp']<=400000)$level=35;
elseif($fuser['exp']>=400001 && $fuser['exp']<=450000)$level=36;
elseif($fuser['exp']>=450001 && $fuser['exp']<=500000)$level=37;
elseif($fuser['exp']>=500001 && $fuser['exp']<=550000)$level=38;
elseif($fuser['exp']>=550001 && $fuser['exp']<=600000)$level=39;
elseif($fuser['exp']>=600001 && $fuser['exp']<=650000)$level=40;
elseif($fuser['exp']>=650001 && $fuser['exp']<=700000)$level=41;
elseif($fuser['exp']>=700001 && $fuser['exp']<=750000)$level=42;
elseif($fuser['exp']>=750001 && $fuser['exp']<=800000)$level=43;
elseif($fuser['exp']>=800001 && $fuser['exp']<=850000)$level=44;
elseif($fuser['exp']>=850001 && $fuser['exp']<=950000)$level=45;
elseif($fuser['exp']>=950001 && $fuser['exp']<=1000000)$level=46;
elseif($fuser['exp']>=1000001 && $fuser['exp']<=1100000)$level=47;
elseif($fuser['exp']>=1100001 && $fuser['exp']<=1200000)$level=48;
elseif($fuser['exp']>=1200001 && $fuser['exp']<=1300000)$level=49;
elseif($fuser['exp']>=1300001 && $fuser['exp']<1500000)$level=50;
elseif($fuser['exp']>=1500001 && $fuser['exp']<=1700000)$level=51;
elseif($fuser['exp']>=1700001 && $fuser['exp']<=1900000)$level=52;
elseif($fuser['exp']>=1900001 && $fuser['exp']<=2100000)$level=53;
elseif($fuser['exp']>=2100001 && $fuser['exp']<=2300000)$level=55;
elseif($fuser['exp']>=2300001 && $fuser['exp']<=2600000)$level=56;
elseif($fuser['exp']>=2600001 && $fuser['exp']<=2900000)$level=57;
elseif($fuser['exp']>=2900001 && $fuser['exp']<=3100000)$level=58;
elseif($fuser['exp']>=3100001 && $fuser['exp']<=3500000)$level=59;
elseif($fuser['exp']>=3500001 && $fuser['exp']<=3800000)$level=60;
elseif($fuser['exp']>=3800001 && $fuser['exp']<=4000000)$level=61;
elseif($fuser['exp']>=4000001 && $fuser['exp']<=4400000)$level=62;
elseif($fuser['exp']>=4400001 && $fuser['exp']<=4800000)$level=63;
elseif($fuser['exp']>=4800001 && $fuser['exp']<=5000000)$level=64;
elseif($fuser['exp']>=5000001 && $fuser['exp']<=5400000)$level=65;
elseif($fuser['exp']>=5400001 && $fuser['exp']<=5800000)$level=66;
elseif($fuser['exp']>=5800001 && $fuser['exp']<=6200000)$level=67;
elseif($fuser['exp']>=6200001 && $fuser['exp']<=6500000)$level=68;
elseif($fuser['exp']>=6500001 && $fuser['exp']<=7000000)$level=69;
elseif($fuser['exp']>=7000001 && $fuser['exp']<=8000000)$level=70;
elseif($fuser['exp']>=8000001 && $fuser['exp']<=9000000)$level=71;
elseif($fuser['exp']>=9000001 && $fuser['exp']<=10000000)$level=72;
elseif($fuser['exp']>=10000001 && $fuser['exp']<=11000000)$level=73;

if (@$fuser['level']!=$level)
{
dbquery("UPDATE `farm_user` SET `level` = '".$level."' WHERE `uid` = '".$user['id']."' LIMIT 1");
}

$money = $fuser['gold'];
$money_ed='';
if ($money>=1000){$money= round($money/1000 , 2);$money_ed='K';}
if ($money>=1000){$money= round($money/1000 , 2);$money_ed='M';}

$exp = $fuser['exp'];
$exp_ed='';
if ($exp>=1000){$exp= round($exp/1000 , 2);$exp_ed='K';}
if ($exp>=1000){$exp= round($exp/1000 , 2);$exp_ed='M';}

$xp = $fuser['xp'];
$xp_ed='';
if ($xp>=1000){$xp= round($xp/1000 , 2);$xp_ed='K';}
if ($xp>=1000){$xp= round($xp/1000 , 2);$xp_ed='M';}

$conf = dbarray(dbquery("SELECT * FROM `farm_conf` ORDER BY id DESC LIMIT 1"));

if (time()>$conf['time_weather'])
{
$new = rand(1,5);
$timenew = time()+10800;
dbquery("INSERT INTO `farm_conf` (`weather`, `time_weather`) VALUES ('".$new."', '".$timenew."')");
}

echo "<div class='rowdown'>";
echo "<a href='/farm/garden/'><img src='/farm/img/home.jpg' alt='' /></a> ";
echo "<a href='/farm/exchanger'><img src='/farm/img/money.png' alt='' /> ".$money."".$money_ed." ";
echo "<img src='/farm/img/gems.png' alt='' /> ".$fuser['gems']."</a> ";
echo "<img src='/img/rosette.png' alt='' width='13' height='13' /> ".$level." ";
echo "<img src='/farm/img/exp.png' alt='' /> ".$exp."".$exp_ed." ";
echo "<a href='/farm/dining'><img src='/img/serdechko.png' alt='' width='13' height='13' /> ".$xp."".$xp_ed."</a>";
echo "<span style='float:right'><a href='/farm/help.php?weather'><img src='/farm/weather/".$conf['weather'].".png' alt='' /></a></span>";
echo "</div>";


$i = dbquery("SELECT * FROM `farm_gr` WHERE `kol` = '0' AND `id_user` = '".$user['id']."'"); 
while ($ii = dbarray($i)){

$semenk=dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '".$ii['semen']."'  LIMIT 1")); 

$pt=rand($semenk['rand1'],$semenk['rand2']);

dbquery("UPDATE `farm_gr` SET `kol` = '".$pt."' WHERE `id` = '".$ii['id']."' LIMIT 1");
}


$i2 = dbquery("SELECT * FROM `farm_gr` WHERE `kol` = '-1' AND `id_user` = '".$user['id']."'"); 
while ($ii2 = dbarray($i2)){

$semenk2=dbarray(dbquery("SELECT * FROM `farm_plant` WHERE  `id` = '".$ii2['semen']."'  LIMIT 1")); 

$pt2=rand($semenk2['rand1'],$semenk2['rand2']);

dbquery("UPDATE `farm_gr` SET `kol` = '".$pt2."' WHERE `id` = '".$ii2['id']."' LIMIT 1");
}

if ($fuser['posadka']>=50 && $fuser['posadka']<500 && $fuser['posadka_1']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'15' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `posadka_1` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили первый уровень достижения [b]Умелый огородник[/b]. Посажено [b]50[/b] растений. Вам выдано звание [b]Бронзовый огородник[/b]. В награду Вы получаете [b]15[/b] алмазов');
}

if ($fuser['posadka']>=500 && $fuser['posadka']<1000 && $fuser['posadka_1']==1 && $fuser['posadka_2']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'50' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `posadka_2` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили второй уровень достижения [b]Умелый огородник[/b]. Посажено [b]500[/b] растений. Вам выдано звание [b]Серебряный огородник[/b]. В награду Вы получаете [b]50[/b] алмазов');
}

if ($fuser['posadka']>=1000 && $fuser['posadka']<3000 && $fuser['posadka_1']==1 && $fuser['posadka_2']==1 && $fuser['posadka_3']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'150' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `posadka_3` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили третий уровень достижения [b]Умелый огородник[/b]. Посажено [b]1000[/b]. растений. Вам выдано звание [b]Золотой огородник[/b]. В награду Вы получаете [b]150[/b] алмазов');
}


if ($fuser['poliv']>=50 && $fuser['poliv']<500 && $fuser['poliv_1']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'15' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `poliv_1` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили первый уровень достижения [b]Мастер полива[/b]. Полито [b]50[/b] растений. Вам выдано звание [b]Бронзовый поливатель[/b]. В награду Вы получаете [b]15[/b] алмазов');
}

if ($fuser['poliv']>=500 && $fuser['poliv']<1000 && $fuser['poliv_1']==1 && $fuser['poliv_2']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'50' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `poliv_2` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили второй уровень достижения [b]Мастер полива[/b]. Полито [b]500[/b] растений. Вам выдано звание [b]Серебряный поливатель[/b]. В награду Вы получаете [b]50[/b] алмазов');
}

if ($fuser['poliv']>=1000 && $fuser['poliv']<3000 && $fuser['poliv_1']==1 && $fuser['poliv_2']==1 && $fuser['poliv_3']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'150' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `poliv_3` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили третий уровень достижения [b]Мастер полива[/b]. Полито [b]1000[/b]. растений. Вам выдано звание [b]Золотой поливатель[/b]. В награду Вы получаете [b]150[/b] алмазов');
}


if ($fuser['udobrenie']>=50 && $fuser['udobrenie']<500 && $fuser['udobrenie_1']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'15' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `udobrenie_1` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили первый уровень достижения [b]Мастер удобрения[/b]. Удобрено [b]50[/b] растений. Вам выдано звание [b]Бронзовый удобритель[/b]. В награду Вы получаете [b]15[/b] алмазов');
}

if ($fuser['udobrenie']>=500 && $fuser['udobrenie']<1000 && $fuser['udobrenie_1']==1 && $fuser['udobrenie_2']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'50' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `udobrenie_2` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили второй уровень достижения [b]Мастер удобрения[/b]. Удобрено [b]500[/b] растений. Вам выдано звание [b]Серебряный удобритель[/b]. В награду Вы получаете [b]50[/b] алмазов');
}

if ($fuser['udobrenie']>=1000 && $fuser['udobrenie']<3000 && $fuser['udobrenie_1']==1 && $fuser['udobrenie_2']==1 && $fuser['udobrenie_3']==0)
{
dbquery("UPDATE `farm_user` SET `gems` = `gems`+'150' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `udobrenie_3` = '1' WHERE `uid` = '".$user['id']."' LIMIT 1");
add_farm_event('Вы получили третий уровень достижения [b]Мастер удобрения[/b]. Удобрено [b]1000[/b]. растений. Вам выдано звание [b]Золотой удобритель[/b]. В награду Вы получаете [b]150[/b] алмазов');
}

if ($fuser['k_poliv_time']>time())
{
$tos=$fuser['k_poliv_time']-time();
echo "<div class='rowup'>";
echo "<img src='/farm/img/irrigation_small.png' alt='' class='rpg' /> 你现在用喷头浇水。<br /><img src='/farm/img/time.png' alt='' class='rpg' /> 再等一会 ".sklon_after_number("$tos","一秒","秒","秒",1)."";
echo "</div>";
echo "<div class='rowdown'>";
echo "<img src='/img/add.png' alt='' class='rpg' /> <a href='/farm/garden/?".$passgen."'>更新</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
exit();
}

if ($fuser['k_posadka_time']>time())
{
$tos=$fuser['k_posadka_time']-time();
echo "<div class='rowup'>";
echo "<img src='/farm/img/seeder_small.png' alt='' class='rpg' /> 你现在用播种机把植物种在花床上。<br /><img src='/farm/img/time.png' alt='' class='rpg' /> 再等一会 ".sklon_after_number("$tos","一秒","秒","秒",1)."";
echo "</div>";
echo "<div class='rowdown'>";
echo "<img src='/img/add.png' alt='' class='rpg' /> <a href='/farm/garden/?".$passgen."'>更新</a>";
echo "</div>";
include_once '../sys/inc/tfoot.php';
exit();
}
?>
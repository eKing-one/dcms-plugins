<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
only_reg();
$set['title']='Весёлая ферма :: Грядка';
include_once '../sys/inc/thead.php';
title();
err();
if (!isset($_GET['id']) || !is_numeric($_GET['id']))
{
header("Location: /farm/garden/");
exit;
}

$conf=dbarray(dbquery("SELECT * FROM `farm_conf` ORDER BY id DESC LIMIT 1"));

$fuser=dbarray(dbquery("SELECT * FROM `farm_user` WHERE `uid` = '".$user['id']."' LIMIT 1"));

$int=intval($_GET['id']);
$post = dbarray(dbquery("select * from `farm_gr` WHERE  `id` = '$int'  LIMIT 1"));
$plnt = dbarray(dbquery("SELECT * FROM `farm_plant` WHERE `id` = '$post[semen]' LIMIT 1"));


if(isset($_GET['unxp']))
{
add_farm_event('[red]Ошибка![/red] Чтобы выполнить данное действие, Вам необходимо пополнить [b] здоровье [/b]');
}

if(isset($_GET['unxpmy']))
{
add_farm_event('[red]Ошибка![/red] Чтобы выполнить данное действие, Вам необходимо пополнить [b] здоровье [/b]');
header("Location: /farm/garden/");
exit;
}

if(isset($_GET['ok']))
{
$semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($_SESSION['pid'])."' "));
add_farm_event('Вы успешно посадили '.$semen1['name'].'. Опыт +1, здоровье -1.');
}

if(isset($_GET['udobr_ok']))
{
$res = dbarray(dbquery("select * from `farm_udobr_name` WHERE `id` = '".intval($_SESSION['udobr'])."' LIMIT 1"));
add_farm_event('Вы успешно использовали '.$res['name'].'. Опыт +1, здоровье -1.');
}

if(isset($_GET['watok']))
{
add_farm_event('Вы успешно полили грядку. Время до сбора урожая сократилось на [b]полчаса[/b].Опыт +1, здоровье -1.');
}


if(isset($_GET['sob_okmy']))
{
$semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($_SESSION['pid'])."' "));
add_farm_event('Вы успешно собрали '.$semen1['name'].'. Опыт +'.intval($_SESSION['opyt']).', здоровье -2.');
unset($_SESSION['pid']);
unset($_SESSION['opyt']);
header("Location: /farm/garden/");
exit;
}

if(isset($_GET['watokmy']))
{
add_farm_event('Вы успешно полили грядку. Время до сбора урожая сократилось на [b]полчаса[/b].Опыт +1, здоровье -1.');
header("Location: /farm/garden/");
exit;
}


if(isset($_GET['sob_ok']))
{
$semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($_SESSION['pid'])."' "));
add_farm_event('Вы успешно собрали '.$semen1['name'].'. Опыт +'.intval($_SESSION['opyt']).', здоровье -2.');
}

if(isset($_GET['nextok']))
{
$semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($_SESSION['plid'])."' "));
add_farm_event('Растение '.$semen1['name'].' перешло на следующий сезон ('.intval($_SESSION['grsezon']).'). Опыт +'.intval($_SESSION['opyt']).', здоровье -2.');
}

if(isset($_GET['nextokmy']))
{
$semen1 = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($_SESSION['plid'])."' "));
add_farm_event('Растение '.$semen1['name'].' перешло на следующий сезон ('.intval($_SESSION['grsezon']).'). Опыт +'.intval($_SESSION['opyt']).', здоровье -2.');
unset($_SESSION['plid']);
unset($_SESSION['opyt']);
header("Location: /farm/garden/");
exit;
}


if(isset($_POST['sadit']) && $post && $user['id']==$post['id_user'] && $post['semen']==0)
{
if ($fuser['xp']>0)
{
$res = dbarray(dbquery("select * from `farm_semen` WHERE `id` = '".intval($_POST[sadit])."' ")); 
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '$res[semen]' "));

if (isset($_SESSION['pid']))
{
unset($_SESSION['pid']);
}
$_SESSION['pid'] = $semen['id'];


$t=time()+$semen['time'];
dbquery("UPDATE `farm_user` SET `exp` = ".($fuser['exp']+1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `xp` = ".($fuser['xp']-1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `semen` = $res[semen] WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = $int LIMIT 1");
//dbquery("UPDATE `farm_gr` SET `time_water` = '".(time()+1800)."' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time` = '$t' WHERE `id` = $int LIMIT 1");
if($res['kol']>=2){
dbquery("UPDATE `farm_semen` SET `kol` = `kol`-'1' WHERE `id` = ".intval($_POST['sadit'])." LIMIT 1");
}else{
dbquery("DELETE FROM `farm_semen` WHERE `id` = ".intval($_POST['sadit'])."");
}
dbquery("UPDATE `farm_user` SET `posadka` = `posadka`+'1' WHERE `uid` = '".$user['id']."' LIMIT 1");

header("Location: /farm/gr.php?gr=".$int."&ok");
}

if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}

}


if (isset($_GET['posadka']) && $post && $user['id']==$post['id_user'] && $post['semen']==0 && isset($_GET['plantid']) && is_numeric($_GET['plantid']))
{
if ($fuser['xp']>0)
{
$check = dbresult(dbquery("SELECT COUNT(*) FROM `farm_semen` WHERE `id` = '".intval($_GET['plantid'])."' AND `id_user` = '$user[id]'"),0); 
if ($check==0)
{
header("Location: /farm/gr.php?id=".$int."");
}
$res = dbarray(dbquery("select * from `farm_semen` WHERE `id` = '".intval($_GET['plantid'])."' ")); 
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '$res[semen]' "));

if (isset($_SESSION['pid']))
{
unset($_SESSION['pid']);
}
$_SESSION['pid'] = $semen['id'];


$t=time()+$semen['time'];
dbquery("UPDATE `farm_user` SET `exp` = ".($fuser['exp']+1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `xp` = ".($fuser['xp']-1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `semen` = $res[semen] WHERE `id` = $int LIMIT 1");
//dbquery("UPDATE `farm_gr` SET `time_water` = '".(time()+1800)."' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time` = '$t' WHERE `id` = $int LIMIT 1");
dbquery("UDATE `farm_gr` SET `udobr` = '0' WHERE `id` = $int LIMIT 1");
if($res['kol']>=2){
dbquery("UPDATE `farm_semen` SET `kol` = `kol`-'1' WHERE `id` = ".intval($_GET['plantid'])." LIMIT 1");
}else{
dbquery("DELETE FROM `farm_semen` WHERE `id` = ".intval($_GET['plantid'])."");
}

dbquery("UPDATE `farm_user` SET `posadka` = `posadka`+'1' WHERE `uid` = '".$user['id']."' LIMIT 1");

header("Location: /farm/my.php?".$passgen."&saditok");
}

if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}

}


if (isset($_GET['get']) && $user['id']==$post['id_user'] && $post['semen']!=0 && $post['time']<time())
{
if ($fuser['xp']>0)
{
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($post['semen'])."' "));

if ($post['sezon']<$semen['let'])
{
header("Location: /farm/gr/$int");
exit();
}

if (isset($_SESSION['pid']))
{
unset($_SESSION['pid']);
}

if (isset($_SESSION['opyt']))
{
unset($_SESSION['opyt']);
}

$opyt0 = $semen['oput']*$post['kol'];
$_SESSION['pid'] = $semen['id'];
$_SESSION['opyt'] = $opyt0;

if ($conf['weather']==1)
{
$wth=$post['kol']+3;
}
if ($conf['weather']==2)
{
$wth=$post['kol']+2;
}
if ($conf['weather']==3)
{
$wth=$post['kol']-1;
if ($fuser['teplica']==1)
{
$wth=$post['kol'];
}
}
if ($conf['weather']==4)
{
$wth=$post['kol']-3;
if ($fuser['teplica']==1)
{
$wth=$post['kol'];
}
}
if ($conf['weather']==5)
{
$wth=$post['kol']+1;
}

if ($fuser['selection']==0)
{
$ums=0;
}
if ($fuser['selection']==1)
{
$prep=$wth/100;
$ums=ceil($prep*5);
}
if ($fuser['selection']==2)
{
$prep=$wth/100;
$ums=ceil($prep*10);
}
if ($fuser['selection']==3)
{
$prep=$wth/100;
$ums=ceil($prep*15);
}
if ($fuser['selection']==4)
{
$prep=$wth/100;
$ums=ceil($prep*20);
}
if ($fuser['selection']==5)
{
$prep=$wth/100;
$ums=ceil($prep*25);
}

$wth=$wth+$ums;

dbquery("INSERT INTO `farm_ambar` (`kol` , `semen`, `id_user`) VALUES  ('".$wth."', '".intval($post['semen'])."', '".$user['id']."') ");

dbquery("UPDATE `farm_user` SET `exp` = '".$opyt."' WHERE `uid` = '".$user['id']."' LIMIT 1");

$xp = $fuser['xp']-2;
dbquery("UPDATE `farm_user` SET `xp` = '".$xp."' WHERE `uid` = '".$user['id']."' LIMIT 1");

$opyt1 = $semen['oput']*$post['kol'];

dbquery("UPDATE `farm_user` SET `exp` = '".($fuser['exp']+$opyt1)."' WHERE `uid` = '".$user['id']."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `semen` = '0' WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `time` = NULL WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `water` = '0' WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `kol` = '0' WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `time_water` = NULL WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `vskop` = '0' WHERE `id` = '".$int."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `sezon` = '1' WHERE `id` = '".$int."' LIMIT 1"); 

header("Location: /farm/gr.php?id=".$int."&sob_ok");


}
if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}

}

if(isset($_GET['getg']) && $user['id']==$post['id_user'] && $post['semen']!=0 && $post['time']<time())
{
if ($fuser['xp']>0)
{
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($post['semen'])."' "));


if (isset($_SESSION['pid']))
{
unset($_SESSION['pid']);
}

if (isset($_SESSION['opyt']))
{
unset($_SESSION['opyt']);
}

$opyt0 = $semen['oput']*$post['kol'];
$_SESSION['pid'] = $semen['id'];
$_SESSION['opyt'] = $opyt0;

dbquery("INSERT INTO `farm_ambar` (`kol` , `semen`, `id_user`) VALUES  ('".intval($post['kol'])."', '".intval($post['semen'])."', '".$user['id']."') ");

dbquery("UPDATE `farm_user` SET `exp` = '$opyt' WHERE `uid` = '".$user['id']."' LIMIT 1");

$xp = $fuser['xp']-2;
dbquery("UPDATE `farm_user` SET `xp` = '$xp' WHERE `uid` = '".$user['id']."' LIMIT 1");

$opyt1 = $semen['oput']*$post['kol'];

dbquery("UPDATE `farm_user` SET `exp` = '".($fuser['exp']+$opyt1)."' WHERE `uid` = '".$user['id']."' LIMIT 1");

dbquery("UPDATE `farm_gr` SET `semen` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time` = NULL WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `water` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `kol` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time_water` = NULL WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `vskop` = '0' WHERE `id` = $int LIMIT 1");
header("Location: /farm/gr.php?id=".$int."&sob_okmy");


}
if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxpmy");
}

}


$sznw = $post['sezon']+1;

if(isset($_GET['next']) && $user['id']==$post['id_user'] && $post['semen']!=0 && $post['time']<time() && ($sznw<$plnt['let'] || $sznw==$plnt['let']))
{
if ($fuser['xp']>0)
{
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($post['semen'])."' "));

if ($semen['let']==1)
{
header("Location: /farm/garden/");
exit;
}

$semenlet = $semen['let'];

$sezonnew = $post['sezon']+1;

if ($sezonnew>$semenlet)
{
header("Location: /farm/gr.php?id=".$int."&get");
}


if (isset($_SESSION['grsezon']))
{
unset($_SESSON['grsezon']);
}

$_SESSION['grsezon'] = $sezonnew;

if ($sezonnew>$semenlet)
{
header("Location: /farm/gr.php?id=".$int."&get");
}

if (isset($_SESSION['plid']))
{
unset($_SESSION['plid']);
}
$_SESSION['plid'] = $post['semen'];

$opt = $semen['oput']*$post['kol'];

if (isset($_SESSION['opyt']))
{
unset($_SESSION['opyt']);
}
$_SESSION['opyt'] = $opt;

$t = time()+$semen['time'];
dbquery("UPDATE `farm_gr` SET `time` = '$t' WHERE `id` = $int LIMIT 1");

$opyt1 = $semen['oput']*$post['kol'];
$opytplu = $opyt1+$fuser['exp'];

dbquery("UPDATE `farm_user` SET `exp` = '$opytplu' WHERE `uid` = '".$user['id']."' LIMIT 1");

dbquery("UPDATE `farm_user` SET `xp` = '".($fuser['xp']-2)."' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `sezon` = '$sezonnew' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `kol` = '0' WHERE `id` = $int LIMIT 1");

if ($conf['weather']==1)
{
$wth=$post['kol']+3;
}
if ($conf['weather']==2)
{
$wth=$post['kol']+2;
}
if ($conf['weather']==3)
{
$wth=$post['kol']-1;
if ($fuser['teplica']==1)
{
$wth=$post['kol'];
}
}
if ($conf['weather']==4)
{
$wth=$post['kol']-3;
if ($fuser['teplica']==1)
{
$wth=$post['kol'];
}
}
if ($conf['weather']==5)
{
$wth=$post['kol']+1;
}

if ($fuser['selection']==0)
{
$ums=0;
}
if ($fuser['selection']==1)
{
$prep=$wth/100;
$ums=ceil($prep*5);
}
if ($fuser['selection']==2)
{
$prep=$wth/100;
$ums=ceil($prep*10);
}
if ($fuser['selection']==3)
{
$prep=$wth/100;
$ums=ceil($prep*15);
}
if ($fuser['selection']==4)
{
$prep=$wth/100;
$ums=ceil($prep*20);
}
if ($fuser['selection']==5)
{
$prep=$wth/100;
$ums=ceil($prep*25);
}

$wth=$wth+$ums;

dbquery("INSERT INTO `farm_ambar` (`kol` , `semen`, `id_user`) VALUES  ('".$wth."', '".$post['semen']."', '".$user['id']."') ");

header("Location: /farm/gr.php?id=".$int."&nextok");
}
else
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}

}


if(isset($_GET['nextmy']) && $user['id']==$post['id_user'] && $post['semen']!=0 && $post['time']<time() && ($sznw<$plnt['let'] || $sznw==$plnt['let']))
{
if ($fuser['xp']>0)
{
$semen = dbarray(dbquery("select * from `farm_plant` WHERE `id` = '".intval($post['semen'])."' "));

$semenlet = $semen['let'];

$sezonnew = $post['sezon']+1;

if ($sezonnew>$semenlet)
{
header("Location: /farm/gr.php?id=".$int."&get");
}


if (isset($_SESSION['grsezon']))
{
unset($_SESSON['grsezon']);
}

$_SESSION['grsezon'] = $sezonnew;

if ($sezonnew>$semenlet)
{
header("Location: /farm/gr.php?id=".$int."&get");
}

if (isset($_SESSION['plid']))
{
unset($_SESSION['plid']);
}
$_SESSION['plid'] = $post['semen'];

$opt = $semen['oput']*$post['kol'];

if (isset($_SESSION['opyt']))
{
unset($_SESSION['opyt']);
}
$_SESSION['opyt'] = $opt;

$t = time()+$semen['time'];
dbquery("UPDATE `farm_gr` SET `time` = '$t' WHERE `id` = $int LIMIT 1");

$opyt1 = $semen['oput']*$post['kol'];
$opytplu = $opyt1+$fuser['exp'];

dbquery("UPDATE `farm_user` SET `exp` = '$opytplu' WHERE `uid` = '".$user['id']."' LIMIT 1");

dbquery("UPDATE `farm_user` SET `xp` = '".($fuser['xp']-2)."' WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '0' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `sezon` = '$sezonnew' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `kol` = '0' WHERE `id` = $int LIMIT 1");
header("Location: /farm/gr.php?id=".$int."&nextokmy");
}
else
{
header("Location: /farm/gr.php?unxpmy");
}

}



if (isset($_POST['udobr']) && $post && $user['id']==$post['id_user'] && $post['semen']!=0)
{
if ($fuser['xp']>0)
{
$res = dbarray(dbquery("select * from `farm_udobr` WHERE `id` = '".intval($_POST['udobr'])."' "));

$semen = dbarray(dbquery("select * from `farm_udobr_name` WHERE `id` = '$res[udobr]' "));

if (isset($_SESSION['udobr']))
{
unset($_SESSION['udobr']);
}

$_SESSION['udobr'] = $semen['id']; 


dbquery("UPDATE `farm_user` SET `exp` = ".($fuser['exp']+1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `xp` = ".($fuser['xp']-1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_gr` SET `udobr` = '1' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time` = `time`- $semen[time] WHERE `id` = $int LIMIT 1");

/*
$newtmi = $post['time_water']+$semen['time'];

dbquery("UPDATE `farm_gr` SET `time_water` = '".$newtmi."' WHERE `id` = '".$int."' LIMIT 1");
*/

if($res['kol']>=2){
dbquery("UPDATE `farm_udobr` SET `kol` = `kol`-'1' WHERE `id` = ".intval($_POST['udobr'])." LIMIT 1");
}else{
dbquery("DELETE FROM `farm_udobr` WHERE `id` = ".intval($_POST['udobr'])."");
}
dbquery("UPDATE `farm_user` SET `udobrenie` = `udobrenie`+'1' WHERE `uid` = '".$user['id']."' LIMIT 1");

header("Location: /farm/gr.php?id=".$int."&udobr_ok");
}
if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}
}
if(isset($_GET['woter']) && $post['time_water']<time())
{
if ($fuser['xp']>0)
{
$wat = time()+1800;
$tmn = $post['time']-1800;
dbquery("UPDATE `farm_gr` SET `time` = '$tmn' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time_water` = '$wat' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_user` SET `exp` = ".($fuser['exp']+1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `xp` = ".($fuser['xp']-1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `poliv` = `poliv`+'1' WHERE `uid` = '".$user['id']."' LIMIT 1");

header("Location: /farm/gr.php?id=".$int."&watok");
}
if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxp");
}
}



if(isset($_GET['water']) && $post['time_water']<time())
{
if ($fuser['xp']>0)
{
$wat = time()+1800;
$tmn = $post['time']-1800;
dbquery("UPDATE `farm_gr` SET `time` = '$tmn' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_gr` SET `time_water` = '$wat' WHERE `id` = $int LIMIT 1");
dbquery("UPDATE `farm_user` SET `exp` = ".($fuser['exp']+1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `xp` = ".($fuser['xp']-1)." WHERE `uid` = '".$user['id']."' LIMIT 1");
dbquery("UPDATE `farm_user` SET `poliv` = `poliv`+'1' WHERE `uid` = '".$user['id']."' LIMIT 1");

header("Location: /farm/gr.php?id=".$int."&watokmy");
}
if ($fuser['xp']<0)
{
header("Location: /farm/gr.php?id=".$int."&unxpmy");
}
}

aut();
include 'inc/str.php';

farm_event();

if($post){
if($user['id']==$post['id_user']){


include 'inc/gr.php';

}else{echo "<div class='err'>Это не Bаша грядка</div>";}
}else{echo "<div class='err'>Такой грядки не существует</div>";}

echo "<div class='rowdown'>";
echo "<img src='/farm/img/garden.png' alt='' class='rpg' /> <a href='/farm/garden/'>Назад</a><br/>";
echo "<img src='/img/back.png' alt='' class='rpg' /> <a href='/farm/'>На ферму</a>";
echo "</div>";

include_once '../sys/inc/tfoot.php';

?>
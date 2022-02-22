<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/adm_check.php';
include_once '../sys/inc/user.php';



only_reg();
user_access('adm_panel_show',null,'/index.php?'.SID);
$set['title'] = 'награждение юзера';
include_once '../sys/inc/thead.php';
title();
aut();







if (isset($_GET['id']))$ank['id']=intval($_GET['id']);else {header("Location: /index.php?".SID);exit;}if (mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `id` = '$ank[id]' LIMIT 1"),0)==0){header("Location: /index.php?".SID);exit;}

$ank=get_user($ank['id']);



if ($user['id']==$ank['id']){header("Location: /index.php?".SID);exit;}


if (isset($_POST['save'])){


if (isset($_POST['opis_nagrad']) && strlen2($_POST['opis_nagrad'])<=1512){if (preg_match('#^A-zА-я0-9 _\*\?\.,\#ui',$_POST['opis_nagrad']))$err[]='Вы используете запрещеные символы';else {$ank['opis_nagrad']=$_POST['opis_nagrad'];mysql_query("UPDATE `user` SET `opis_nagrad` = '".mysql_real_escape_string($ank['opis_nagrad'])."' WHERE `id` = '$ank[id]' LIMIT 1");}}else $err[]='лимит превышен';

if (isset($_POST['nagrad']) && ($_POST['nagrad']==1 || $_POST['nagrad']==0)){$ank['nagrad']=$_POST['nagrad'];mysql_query("UPDATE `user` SET `nagrad` = '$ank[nagrad]' WHERE `id` = '$ank[id]' LIMIT 1");}


msg('изменения успешно приняты');}
echo "<form method=\"post\" action=\"?id=$ank[id]&go\">";

echo "обитатель: <a href=/info.php?id=$ank[id]><b>$ank[nick]</b></a><br/>";
echo "наградить юзера:<br /> <input name='nagrad' type='radio' ".($ank['nagrad']==1?' checked="checked"':null)." value='1' />да";echo "<input name='nagrad' type='radio' ".($ank['nagrad']==0?' checked="checked"':null)." value='0' />нет<br />";
echo "Вы можете дать описание за что юзер был награжден:<br />\n<input type='text' name='opis_nagrad' value='$ank[opis_nagrad]' maxlength='1500' /><br />\n";
echo "<input type='submit' name='save' value='Сохранить' />\n";

echo "</form>\n";




echo "<a href='/id$ank[id]'><div class='gmenu'><img src='/style/glavnaya.gif'> Назад на страницу $ank[nick]</div></a>";
include_once '../sys/inc/tfoot.php';
?>



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





$set['title']='Настройки дуэлей';
include_once '../sys/inc/thead.php';
title();
err();
aut();

if($user['level'] > 4) {

$ball = mysql_fetch_assoc(mysql_query("select * from `duels_settings` "));

if(isset($_GET['save'])) { echo '<div class="msg"> Сохранено!</div>'; }

echo "<div class='main_menu'>
<form action='?' method='POST'>

Голосов на победу [5-50]:<br/>
<input type='text' name='golosov' value ='".$ball['golosov']."' /><br/> 

Баллы за голосование [0-1000]:<br/>
<input type='text' name='golos' value ='".$ball['golos']."' /><br/> 

Баллы за победу [0-1000]:<br/> 
<input type='text' name='pobeda' value ='".$ball['pobeda']."' /><br/> 

Баллы за проигрыш [0-1000]:<br/> 
<input type='text' name='pobeda2' value ='".$ball['pobeda2']."' /><br/> 

Сколько человек можно пригласить на свою дуэль [5-100]:<br/> 
<input type='text' name='invite' value ='".$ball['invite']."' /><br/> 

<input type='submit' name='add' value='Изменить'/><br/> 
</form>
</div>";


if(isset($_POST['add'])) {
if($_POST['golosov'] >= 5 and $_POST['invite'] >= 5 and $_POST['golos'] >= 0 and $_POST['pobeda'] >= 0 and $_POST['pobeda2'] >= 0 and
$_POST['golosov'] <= 50 and $_POST['invite'] <= 100 and $_POST['golos'] <= 1000 and $_POST['pobeda'] <= 1000 and $_POST['pobeda2'] <= 1000) {

mysql_query("update `duels_settings` set `golosov` = '".intval($_POST['golosov'])."' ");
mysql_query("update `duels_settings` set `golos` = '".intval($_POST['golos'])."' ");
mysql_query("update `duels_settings` set `pobeda` = '".intval($_POST['pobeda'])."' ");
mysql_query("update `duels_settings` set `pobeda2` = '".intval($_POST['pobeda2'])."' ");
mysql_query("update `duels_settings` set `invite` = '".intval($_POST['invite'])."' ");

header("location: ?save");
} else
{
echo '<div class="err"> Ошибка!</div>';
}




}
}
else { header("Location: index.php".SID); }
echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';

include_once '../sys/inc/tfoot.php';
?>
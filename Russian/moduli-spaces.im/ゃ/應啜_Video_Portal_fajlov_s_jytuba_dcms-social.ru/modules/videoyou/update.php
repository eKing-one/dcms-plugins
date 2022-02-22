<?
######################################################
#   Видео Портал файлов с ютуба dcms 6.6.4 и фиера   #         
#   Автор: Saint, JumanG.ru  & wmsait.ru  	    	 #
#   icq: 399537814, сайт: http://DCMS-FIERA.ru     	 #
#   Вы не имеете права продавать, распростронять,	 #
#   давать друзьям даный скрипт.                 	 #
#   Даная версия являет платной, и купить       	 #
#   можно только у Saint'a 							 #
#	Вы не имеете права ставить если модуль 			 #
#	даже если он слит!!  							 #
#	даже если он был вап продаан барыгой !!!  		 #
#	даже если вы его случайно где то скачали !!!	 #  
#	даже если что то еще ,							 #
#	не тратьте моё время и ваше	,					 #
#	постреченое на постройку вашего сайта ;)		 #
######################################################
#		Видео Портал файлов с ютуба by Saint 		 #
######################################################
$JumanG = $_SERVER['DOCUMENT_ROOT'];
include_once $JumanG.'/sys/inc/start.php';
include_once $JumanG.'/sys/inc/compress.php';
include_once $JumanG.'/sys/inc/sess.php';
include_once $JumanG.'/sys/inc/home.php';
include_once $JumanG.'/sys/inc/settings.php';
include_once $JumanG.'/sys/inc/db_connect.php';
include_once $JumanG.'/sys/inc/ipua.php';
include_once $JumanG.'/sys/inc/fnc.php';
include_once $JumanG.'/sys/inc/user.php';
only_reg();
$set['title']='videoyou ';
include_once $JumanG.'/sys/inc/thead.php';
title();
$videoyou=mysql_fetch_assoc(mysql_query('SELECT * FROM `videoyou` WHERE `id`='.intval($_GET['id'])));
if (isset($_POST['save'])){
if (isset($_POST['msg']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['msg']))))<=20480)
{
$videoyou['msg']=esc(stripcslashes(htmlspecialchars($_POST['msg'])));
mysql_query("UPDATE `videoyou` SET `msg` = '$videoyou[msg]' WHERE `id` = '$videoyou[id]' LIMIT 1");
}
if (isset($_POST['video']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['video']))))<=11048)
{
$videoyou['video']=esc(stripcslashes(htmlspecialchars($_POST['video'])));
mysql_query("UPDATE `videoyou` SET `video` = '$videoyou[video]' WHERE `id` = '$videoyou[id]' LIMIT 1");
}
else $err='Видео  не может быть длиннее 10024 символов';
if (isset($_POST['opis']) && strlen2(esc(stripcslashes(htmlspecialchars($_POST['opis']))))<=20480)
{
$videoyou['opis']=esc(stripcslashes(htmlspecialchars($_POST['opis'])));
mysql_query("UPDATE `videoyou` SET `opis` = '$videoyou[opis]' WHERE `id` = '$videoyou[id]' LIMIT 1");
}
if (!isset($err))msg('Изменения успешно приняты');
}
err();
aut();
echo "<div class='p_m'><form method='post' action='?id=$videoyou[id]'>\n";
echo "<span class=\"ank_t\">Заголовок:</span><br />\n";
echo "<input type='text' name='video'  value='$videoyou[video]'value='$videoyou[video]'><br />\n";
echo "<span class=\"ank_t\">Ссылка на видео:</span><br />\n";
echo "<input type='text' name='msg'  value='$videoyou[msg]'value='$videoyou[msg]'><br />\n";
echo "Описание :<br /><textarea name=\"opis\">$videoyou[opis]</textarea>";
echo "<input type='submit' name='save' value='Сохранить' />\n";
echo "</form></div>";
if(isset($_SESSION['refer']) && $_SESSION['refer']!=NULL && otkuda($_SESSION['refer']))
echo "&laquo;<a href='$_SESSION[refer]'>".otkuda($_SESSION['refer'])."</a><br />\n";
echo "<div class='foot'>\n";
echo "&laquo;<a href='/videoyou'>Назад</a><br />\n";
echo "</div>\n";
include_once $JumanG.'/sys/inc/tfoot.php';

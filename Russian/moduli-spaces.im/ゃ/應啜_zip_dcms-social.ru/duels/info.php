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




$set['title']='Информация о дуэлях';
include_once '../sys/inc/thead.php';
title();
err();
aut();

$ball = mysql_fetch_assoc(mysql_query("select * from `duels_settings` "));



echo "<div class='main'> Информация о дуэлях:</div>";
echo '<div class="nav2">1) Чтобы выиграть в дуэлях, Вам нужно набрать <b>'.$ball['golosov'].'</b> голосов</div>';

echo '<div class="nav2"> 2) За оставленный Вами голос в дуэлях, Вам дается <b>'.$ball['golos'].'</b> баллов</div>';

echo '<div class="nav2"> 3) За победу в дуэлях, Вы получаете <b>'.$ball['pobeda'].'</b> баллов</div>';

echo '<div class="nav2"> 4) За проигрыш в дуэлях, Вы получаете <b>'.$ball['pobeda2'].'</b> баллов</div>';

echo '<div class="foot"><img src="img/duels.png"> <a href="index.php">Дуэли</a></div>';

include_once '../sys/inc/tfoot.php';
?>
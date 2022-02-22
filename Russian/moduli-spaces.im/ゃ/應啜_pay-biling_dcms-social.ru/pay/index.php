<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$set['title']='Что хотим пополнить?';
include_once '../sys/inc/thead.php';
only_reg();
title();
err();
aut();
?>
<?
echo "<div class='nav1'>\n";


echo "<b>Личный счет:</b><br />



- <b><font color='red'>$user[balls]</font></b> баллов.<br />



- <b><font color='green'>$user[money]</font></b> $sMonet[0]";
echo "</div>\n";
?>
<div class="fon2">
  После нажатия на ссылку <b>пополнить</b> вы сможете выбрать необходимое количество <b>баллов</b> или <b>монет</b> и перейти к форме оплаты.<hr>
</div>
<div class="fon3" style="vertical-align:middle;"><img src="/style/icons/str.gif"> <a href="sms.php"> - | Пополнить баллы |</div></a><hr>
<div class="fon3" style="vertical-align:middle;"><img src="/style/icons/str.gif"> <a href="smsmoney.php"> - | Пополнить монеты|</div></a><hr>

<div class="fon2" style="vertical-align:middle;"> <a href="info.php"> - Моя страница!</div></a>
<?
include_once '../sys/inc/tfoot.php';
?>
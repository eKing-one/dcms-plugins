<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

only_reg();
$set['title'] = 'Стартовая страница';
include_once 'sys/inc/thead.php';
title();
aut();


echo '<div class="nav1"><center>';
echo '<b>Добро пожаловать!</b>';
echo '</center></div>';
echo '<div class="nav1">';
echo "<img src='kot.gif'>";
echo "</div>";

echo "<div class='nav1'>";
echo "Время: </a>";
echo '<font color="green"><u>' . vremja($time). '</u></font>.';
echo "</div>";

echo '<div class="nav1">';
echo 'Сегодня: ';
echo '<font color="green"><u>' . date("d.m.Y") . '</u></font>.';
echo '</div>';


echo '<div class="nav1"><center>';
echo '<b>Быстрая навигация:</b>';
echo '</center></div>';
echo '<div class="nav1"><img src="style/icons/druzya.png">  <a href="/info.php?id='.$user['id'].'"> Мой сайт</a></div>';
echo '<div class="nav1"> <img src="style/icons/chat.png"> <a href="/chat/"> Чат</a></div>';
echo '<div class="nav1"> <img src="style/icons/forum.png"> <a href="/forum/"> Форум</a></div>';
echo '<div class="nav1"> <img src="style/icons/zametki.gif"> <a href="/plugins/notes/"> Дневники</a></div>';
echo '<div class="nav1"> <img src="style/icons/meets.gif"> <a href="/user/love/"> Знакомства</a></div>';
echo '<div class="nav1"> <img src="style/icons/info.gif"> <a href="my_aut.php"> История входов</a></div>';
echo "<div class='nav1'>";
echo "<b>Закладка для автовхода:</b><br />\n";
echo "<input type='text' value='http://$_SERVER[SERVER_NAME]/login.php?id=$user[id]&amp;pass=".htmlspecialchars($_POST['pass1'])."' /><br />\n";
echo "</div>";
include_once 'sys/inc/tfoot.php';
exit;
?>

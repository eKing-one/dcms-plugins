<?
require '../sys/inc/start.php';
require '../sys/inc/compress.php';
require '../sys/inc/sess.php';
require '../sys/inc/home.php';
require '../sys/inc/settings.php';
require '../sys/inc/db_connect.php';
require '../sys/inc/ipua.php';
require '../sys/inc/fnc.php';
require '../sys/inc/user.php';
$set['title']='Цвет Online Статуса';
require '../sys/inc/thead.php';
only_reg();
title();
echo "<table><td class='block'><a href='/pages/servis.php'>Сервисы</a></td><td class='block'><a href='onstatus.php'>Выбрать статус</a></td></table>";
if (isset($_GET['oky']) && isset($_POST['oky'])){
if($user['balls']<300)$err[]="Для смены цвета статуса у вас недостаточно баллов";
if (!isset($err)){
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-300)."' WHERE `id` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `scolor` = '".htmlspecialchars($_POST['scolor'])."' WHERE `id` = '$user[id]' LIMIT 1");
header('Location: ?ok');
}
}
if(isset($_GET['ok']))
{
msg('Успешно');
}
echo '<div class="post">';
echo "Стоимость окраски Online  статуса <img src='/style/images/money.gif' alt='' class='icon'/> <b>300</b> баллов<br/>\n";
echo "У Bас <img src='/style/images/money.gif' alt='' class='icon'/> <b>$user[balls]</b> баллов\n";
echo '</div>';
echo "<form action='?oky' method='post'>\n";
echo "Выберите цвет:<br/>\n";
echo '<select name="scolor">
<option selected="selected" value="orange" style="background-color: orange; color: black;">оранжевый</option>
<option value="red" style="background-color: red; color: white;">красный</option>
<option value="brown" style="background-color: brown; color: white;">коричневый</option>
<option value="pink" style="background-color: pink; color: white;">Светло-розовый</option>
<option value="#f509fe" style="background-color: #f509fe; color: white;">розовый</option>
<option value="green" style="background-color: green; color: white;">зеленый</option>
<option value="lightgreen" style="background-color: lightgreen; color: white;">светло-зеленый</option>
<option value="#669999" style="background-color: #669999; color: white;">морской волны</option>
<option value="#471b23" style="background-color: #471b23; color: white;">Бурый</option>
<option value="#65a442" style="background-color: #65a442; color: white;">Болотный</option>
<option value="blue" style="background-color: blue; color: white;">синий</option>
<option value="#12177a" style="background-color: #12177a; color: white;">темно-синий</option>
<option value="blueviolet" style="background-color: blueviolet; color: white;">фиолетовый</option>
<option value="yellow" style="background-color: yellow; color: black;">желтый</option>
<option value="goldenrod" style="background-color: goldenrod; color: black;">золотой</option>
<option value="gray" style="background-color: gray; color: black;">темно-серый</option>
<option value="#CCCCCC" style="background-color: #CCCCCC; color: black;">светло-серый</option>
</select>';
echo "<input type='submit' value='Сохранить' name='oky'></form>\n";
require '../sys/inc/tfoot.php';
?>
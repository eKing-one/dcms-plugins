<?
include_once '../../sys/inc/start.php';
include_once '../../sys/inc/compress.php';
include_once '../../sys/inc/sess.php';
include_once '../../sys/inc/home.php';
include_once '../../sys/inc/settings.php';
include_once '../../sys/inc/db_connect.php';
include_once '../../sys/inc/ipua.php';
include_once '../../sys/inc/fnc.php';
include_once '../../sys/inc/user.php';
$set['title']='Смена значка онлайн'; // заголовок страницы
include_once '../../sys/inc/thead.php';
title();
err();
aut();
# Запросы в db

if (isset($_POST['on_i']) && isset($user))
{
$icon=intval($_POST['on_i']);
if(!isset($err)){
mysql_query("UPDATE `user` SET `on_i` = '".$icon."' WHERE `id` = '".$user['id']."' LIMIT 1");
mysql_query("UPDATE `user` SET `money` = '".($user['money']-1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg(' Значек Успешно Куплен ;)');}}

# Меню выбора

echo "<div class='nav1'>";

if($user['money']<1){
echo '<br>У вас не хватает монет. Для смены значка онлайн вам необходима хотя бы одна монета!<br><br>';
}else{

echo'<form action="?" method="post">';
echo '<center><input type="radio" name="on_i" value="1"/><img src="/style/on/1.png" /><input type="radio" name="on_i" value="2"/><img src="/style/on/2.png" /><input type="radio" name="on_i" value="3"/><img src="/style/on/3.png" /><input type="radio" name="on_i" value="4"/><img src="/style/on/4.png" /><br><input type="radio" name="on_i" value="6"/><img src="/style/on/6.png" /><input type="radio" name="on_i" value="7"/><img src="/style/on/7.png" /><input type="radio" name="on_i" value="8"/><img src="/style/on/8.png" /><input type="radio" name="on_i" value="9"/><img src="/style/on/9.png" /><br><input type="radio" name="on_i" value="10"/><img src="/style/on/10.png" /><input type="radio" name="on_i" value="11"/><img src="/style/on/11.png" /><input type="radio" name="on_i" value="12"/><img src="/style/on/12.png" /><input type="radio" name="on_i" value="13"/><img src="/style/on/13.png" /><br><input type="radio" name="on_i" value="14"/><img src="/style/on/14.png" /><input type="radio" name="on_i" value="15"/><img src="/style/on/15.png" /><input type="radio" name="on_i" value="16"/><img src="/style/on/16.png" /><input type="radio" name="on_i" value="17"/><img src="/style/on/17.png" /><br></center>';
echo '<br><center><b>Цена :: <font color="orange">1</font><img src="/style/on/money.png" alt="*"> </b> <input type="submit" value="Сменить Значек"/></br></center><br>';
}
echo '</div>';
include_once '../../sys/inc/tfoot.php';
# Автор: 1EVROS
# Email: 1evros@mail.ru
?>

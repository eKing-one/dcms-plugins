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

$set['title'] = 'Новый клан';
include_once '../sys/inc/thead.php';
title();
aut(); // форма авторизации

if ($user['balls']>=10000 && mysql_result(mysql_query("SELECT COUNT(id) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)==0)
{
if (isset($_POST['name']) && isset($_POST['opis']))
{
	$opis = $_POST['opis'];
	$name = $_POST['name'];

	if (strlen2($name)<3 ) $err = 'Короткое название';
	if (strlen2($name)>32) $err = 'Длинное название';
	if (strlen2($opis)<10) $err = 'Короткое описание';
	if (strlen2($opis)>250) $err = 'Длинное описание';

$name = htmlspecialchars(stripslashes($name));
$opis = htmlspecialchars(stripslashes($opis));

if(!isset($err)) {
mysql_query("INSERT INTO `clan` (`user`, `time`, `name`, `about`) VALUES ('$user[id]', '".time()."', '$name', '$opis')");
$clanid = mysql_insert_id();
mysql_query("INSERT INTO `clan_user` (`id_user`, `id_clan`, `time`, `level`) VALUES ('$user[id]', '$clanid', '$time', '2')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-10000)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Клан успешно создан');
}
}

err();

echo "<div class='p_t'>Новый клан:</div>\n";
echo "<div class='str'>\n";
echo "<form method='post' action='?act=create'>\n";
echo "Название (3-64)*:<br />\n";
echo "<input type = 'text' name = 'name' value = '' maxlength = '64'><br />\n";
echo "Описание (10-250)*:<br />\n";
echo "<textarea name='opis'  rows='3' cols='35' maxlength = '250'></textarea><br />\n";
echo "<br />\n";
echo "<input type='submit' value='Создать'></form>\n";
echo "</div>\n";
echo "<div class='str'>";
echo "<a href='/klan/'>Все кланы</a><br/>\n";
echo "<a href='/klan/rules.php'>Помощь и Правила</a>\n";
echo "</div>\n";
}
else
{
echo "<div class='str'>\n";
echo "У вас не хватает баллов или вы уже состоите в другом клане!\n";
echo "</div>\n";
}
include_once '../sys/inc/tfoot.php';
?>
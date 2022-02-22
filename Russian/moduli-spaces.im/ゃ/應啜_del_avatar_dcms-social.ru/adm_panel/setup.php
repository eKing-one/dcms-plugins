<?php
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

$set['title']='Установка модуля (Удаление аватаров пользователей) By System';
include_once '../sys/inc/thead.php';
title();
err();
aut();

switch($_GET['steep']){

case '2':
$query = "INSERT INTO `all_accesses` (`type` , `name`) VALUES ('dell_user_avatar','Удаление аватаров пользователей');";
if(mysql_query($query)){
	echo "<div class='menu'>\n";
echo "Запись прав о модуле в базу данных завершена!<br />";
echo "
<a href='?steep=3'>Далее</a>
</div>";
}else{
	echo "<div class='menu'>\n";
echo "Ошибка внесения записи в базу данных!<br />";
echo "
<a href='?steep=2'>Далее</a>
</div>";
}
break;

case '3':
if(copy('inc/dell_avatar_module.php', H.'sys/add/admin/dell_avatar_module.php')){
	echo "<div class='menu'>\n";
echo "Внесение модуля в админ-панель завершенно!<br />";
echo "
<a href='?steep=4'>Далее</a>
</div>";
}else{
	echo "<div class='menu'>\n";
echo "Ошибка внесения модуля в админ-панель!<br />";
echo "
<a href='?steep=3'>Далее</a>
</div>";
}
break;

case '4':
@unlink("setup.dat");
echo "<div class='menu'>\n";
echo "Модуль успешно установлен!<br />";
echo "
<a href='del_avatar.php'>Далее</a>
</div>";
break;

default:
	echo "<div class='menu'>\n";
echo "Нажимая далее вы принимаете следующее соглашение:<br />

<b>Соглашение:</b><br />
1. Данный модуль сделан для движка DCMS 6.6.4 !<br/ >
2. Продажа данного модуля запрещена (увижу в продаже отшлепаю по попе!).<br />
3. Автор данного модуля никто инной как <b>Xokano</b> за изменение коопирайта получите по попе =).<br />
Сайт автора: <a href='http://wmclass.lark.ru'>Благотварительность</a><br />
Ссылка на caйт wmclass.lark.ru: <a href='http://wmclass.lark.ru/info.php?id=2'>Xokano</a><br />
<a href='?steep=2'>Далее</a>
</div>";
break;

}
include_once '../sys/inc/tfoot.php';
?>
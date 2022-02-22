Название: Действия над пользователями
Автор: Killer
Версия: 1.0
Требования: DCMS Social

Описание:
Пользователи могут выполнять действия над другими пользователями.
Каждое действие стоит определенное количество баллов.
Действия могут быть приватными, т.е. никто его не сможет увидеть кроме того пользователя, над которым оно было выполнено.
Есть админка. Можно удалять, добавлять, редактировать действия.
В архиве имеется 27 действий.
Есть уведомление о новом действии.

Установка:
- Папки style, sys, adm_panel, user закинуть в корень сайта.
- Выполнить запросы из файла actions.sql.
- В файле личной страницы пользователя прописать код:

echo "<img src='/style/icons/action_user.png' /> <a href='/user/actions/?id=$ank[id]'>Действия</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `actions_user` WHERE `id_user` = '$ank[id]'".(isset($user) && ($user['id']==$ank['id'] || $user['level'] > 3) ? NULL : " AND (`type` = '0' OR `type` = '1' AND `id_ank` = '$user[id]')").""), 0).")<br />\n";

- Открываем файл user/notification/index.php. Находим код:

if ($type == 'guest') 
{
	if ($avtor['id'])
	{
	echo status($avtor['id']) .  group($avtor['id']) . " <a href='/info.php?id=$avtor[id]'>$avtor[nick]</a>  " . medal($avtor['id']) . " " . online($avtor['id']) . " $name ";
	echo "<img src='/style/icons/guest.png' alt='*'> <a href='/guest/?page=$pageEnd'>гостевой</a>  $s1 ".vremja($post['time'])." $s2";
	} else {
	echo 'Этот пользователь пользователь уже удален =(';
	}
	echo "<div style='text-align:right;'><a href='?komm&amp;del=$post[id]&amp;page=$page'><img src='/style/icons/delete.gif' alt='*' /></a></div>";
}

После него вставляем код:

include('inc/actions.php');

- Открываем файл adm_panel/index.php. Находим код:

if ($user['level'] > 3)echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='smiles.php'>Смайлы</a></div>\n";

После него вставляем код:

if (user_access('actions_edit'))echo "<div class='main'><img src='/style/icons/str.gif' alt=''/> <a href='actions.php'>Редактирование действий</a></div>\n";

- Заходим в Админку -> Привилегии групп пользователей. Находим свою группу и добавляем ей привилегию "Действия - Редактирование действий". После этого в Админке появится пункт "Редактирование действий", выбрав который вы сможете изменить действия.

- Это все. Не забываем о положительном отзыве!)

Спасибо за то, что приобрели наш товар! Больше красивых и качественных скриптов Вы сможете купить здесь http://gix.su/shop/user.php?id=96
(с) Killer
Все права защищены. Если этот модуль попал к вам не из рук автора, просьба оповестить нас о этом, используя наши контакты.
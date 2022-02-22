Установка:
Распаковать в корень
Выполнить запросы из galaxy.sql

Под аватаркой в user/info/wap.php прописать код:

if (isset($user) && $user['id'] == $ank['id'])
{
	echo '<img src="/style/icons/icon_gala.png" alt="*" width="20" /> <a href="/user/pers.php">Мой персонаж</a>'; 
}
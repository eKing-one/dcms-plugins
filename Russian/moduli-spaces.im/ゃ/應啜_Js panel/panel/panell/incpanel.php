<?



$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);


echo "<li>";
echo "<a href='/info.php'title='Моя Страница'><img src='/panell/img/1.png' alt='' /> Моя Страница</a>";
echo "</li>\n";

echo '<li>';
echo "<a href='/mail.php'title='Мои Сообщения $k_new'><img src='/panell/img/2.png' alt='' />  Мои Сообщения</a> ";
echo '</li>';

echo '<li>';
echo "<a href='/frend.php'title='Мои Друзья'><img src='/panell/img/5.png' alt='' />  Мои Друзья</a>";
echo '</li>';

echo '<li>';
echo "<a href='/guest/'title='Гостевая книга'><img src='/panell/img/3.png' alt='' />  Гостевая</a>";
echo '</li>';

echo '<li>';
echo "<a href='/forum/'title='Форум'><img src='/panell/img/4.png' alt='' />  Форум</a>";
echo '</li>';

echo '<li>';
echo "<a href='/settings.php'title='Настройки'><img src='/panell/img/6.png' alt='' />  Настройки</a>";
echo '</li>';

echo '<li>';
echo "<a href='/exit.php'title='Выход'><img src='/panell/img/7.png' alt='' />  Выход</a>";
echo '</li>';

?>
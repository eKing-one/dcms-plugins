<?








$k_n_s_zak=mysql_result(mysql_query("SELECT COUNT(`forum_zakl`.`id_them`) FROM `forum_zakl` LEFT JOIN `forum_p` ON `forum_zakl`.`id_them` = `forum_p`.`id_them` AND `forum_p`.`time` > `forum_zakl`.`time` WHERE `forum_zakl`.`id_user` = '$user[id]' AND `forum_p`.`id` IS NOT NULL"),0);
if ($k_n_s_zak>0)
echo "<a href='/zakl.php' title='Новые сообщения в закладках'><b>Сообщения в закладках ($k_n_s_zak)</b></a><br />\n";
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `forum_zakl` WHERE `id_user` = '$user[id]'"),0)!=0)
echo "<a href='/zakl.php'>Мои закладки</a><br />\n";








$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);
$k_new_fav=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
 LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
 WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);



if ($k_new!=0 && $k_new_fav==0)
echo "<b><a href='/new_mess.php'>Нов".($k_new==1?'о':'ы')."е сообщени".($k_new==1?'е':"я ($k_new)")."</a></b> <img src='/style/icons/mess0.png' /><br />\n";
if ($k_new_fav!=0)
echo "<b><a href='/new_mess.php'>Сообщени".($k_new_fav==1?'е':'я')."</a></b> <img src='/style/icons/mess_fav.png' alt='$k_new_fav' /><br />\n";


echo "<a href='/konts.php'>Контакты</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND (`type` = 'common' OR `type` = 'favorite')"), 0).")<br />\n";
echo "<a href='/anketa.php'>Моя анкета</a><br />\n";
echo "<a href='/info.php'>Посмотреть анкету</a><br />\n";

echo "<a href='/settings.php'>Мои настройки</a><br />\n";
echo "<a href='/avatar.php'>Мой аватар</a><br />\n";
echo "<a href='/ncolor.php'>Купить цвет ника</a><br />\n";

$opdirbase=@opendir(H.'sys/add/umenu');
while ($filebase=@readdir($opdirbase))
if (eregi('\.php$',$filebase))
include_once(H.'sys/add/umenu/'.$filebase);

echo "<a href='/secure.php'>Сменить пароль</a><br />\n";
echo "<a href='/rules.php'>Правила</a><br />\n";

if (user_access('adm_panel_show'))echo "<a href='/adm_panel/'>Админка</a><br />\n";

if ($set['web']==false)
echo "<hr />\n<a href='/exit.php'>Выход из под $user[nick]</a><br />\n";
?>
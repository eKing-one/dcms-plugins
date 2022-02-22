<?
/////////////Автор by wladua
/////////////Модуль личный кабинет V1
echo "<div class='main_menu'>";
echo "<img src='/style/icons/time.png' >Сейчас на часах : </a>";
echo vremja($time);
echo "</div>";


echo "<div class='nav1'>";
echo "<table allspacing='1' cellpadding='1'><tr>";
echo "<td class='bl1'>";
echo avatar_ank($user['id']);
echo "</td>";
echo "<td class='bl2' align= center'>";
 echo "<img src='/style/kabinet/information.png' alt='*' width='16'/> <b>ID: $user[id]</b><br /> \n";
echo "<img src='/style/kabinet/money.png' alt='*' width='16'/> Баллы: ";
echo "$user[balls]</font><br />\n"; echo "<img src='/style/kabinet/money_add.png' alt='*' width='16'/> "; echo $sMonet[2] . ': '     .    $user['money'] . '<br />';
echo "</div>\n";
echo "</div>";
echo "</td>";
echo "</table> ";
echo "</div>";




echo "<div class='main_menu'>";
if (user_access('adm_panel_show')){
echo "<img src='/style/kabinet/admin.png' alt='' /> <a href='/adm_panel/'>Админка</a><br />\n";}
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/main.png' alt='' /> <a href='/info.php'>Моя страничка</a><br />\n";
echo "</div>";

echo "<div class='main_menu'>";
$k_music=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_music` WHERE `id_user` = '$user[id]'"),0);
echo "<img src='/style/kabinet/Music2.png' alt='' /> <a href='/user/music/index.php?id=$user[id]'>Моя музыка</a> ";
echo "(" . $k_music . ")";
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Write Message.png' alt='' /> <a href='user/info/edit.php'>Редактировать анкету</a><br />\n";echo "</div>";echo "<div class='main_menu'>";
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Contact.png' alt='' /> <a href='/konts.php'>Мои контакты</a> (".mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND (`type` = 'common' OR `type` = 'favorite')"), 0).")<br />\n";
$k_new=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` IS NULL OR `users_konts`.`type` = 'common' OR `users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);
$k_new_fav=mysql_result(mysql_query("SELECT COUNT(`mail`.`id`) FROM `mail`
LEFT JOIN `users_konts` ON `mail`.`id_user` = `users_konts`.`id_kont` AND `users_konts`.`id_user` = '$user[id]'
WHERE `mail`.`id_kont` = '$user[id]' AND (`users_konts`.`type` = 'favorite') AND `mail`.`read` = '0'"),0);
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Currency Dollar.png' alt='*' /> <a href=\"/user/money/index.php\">Дополнительные услуги</a><br /> \n";
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Gear.png' alt='' /> <a href='/user/info/settings.php'>Мои настройки</a><br />\n";
echo "</div>";

echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Lock.png' alt='' /> <a href='/secure.php'>Сменить пароль</a><br />\n";
echo "</div>";

if ($set['web']==false){echo "<div class='main_menu'>";
echo "<img src='/style/kabinet/Standby.png' alt='' /> <a href='/exit.php'>Выход из под $user[nick]</a><br />\n";echo "</div>";}


?>
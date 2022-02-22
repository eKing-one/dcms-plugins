<?
if($user['level']>=3 && mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
// $ust->access('comm_delete_cat')
$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '".intval($_GET['id'])."'");
$cat=mysql_fetch_array($cat);
$set['title'] = 'Сообщества - Удаление категории'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if(isset($_POST['submited']))
{
$q_comm = mysql_query("SELECT * FROM `comm` WHERE `id_cat` = '$cat[id]'");
while ($post_comm = mysql_fetch_array($q_comm))
{
// удаляем все данные из форума
$q_forum_cat = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$post_comm[id]' AND `type` = 'cat'");
while ($post_forum_cat = mysql_fetch_array($q_forum_cat))
{
$q_forum_topic = mysql_query("SELECT * FROM `comm_forum` WHERE `id_comm` = '$post_comm[id]' AND `type` = 'topic' AND `id_cat` = '$post_forum_cat[id]'");
while ($post_forum_topic = mysql_fetch_array($q_forum_topic))
{
mysql_query("DELETE FROM `comm_forum` WHERE `id` = '$post_forum_topic[id]' AND `type` = 'topic'");
mysql_query("DELETE FROM `comm_forum_komm` WHERE `id_topic` = '$post_forum_topic[id]' LIMIT 1");
}
mysql_query("DELETE FROM `comm_forum` WHERE `id` = '$post_forum_cat[id]' AND `type` = 'cat'");
}
// удаляем все данные из чата
mysql_query("DELETE FROM `comm_chat` WHERE `id_comm` = '$post_comm[id]'");
mysql_query("DELETE FROM `comm_chat_who` WHERE `id_comm` = '$post_comm[id]'");
// удаляем все данные из загрузок
$q_files_dir = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$post_comm[id]' AND `type` = 'dir' AND `id_dir` = '0'");
while ($post_files_dir = mysql_fetch_array($q_files_dir))
{
$q_files_file = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$post_comm[id]' AND `counter` like '%/$post_files_dir[id]/%' AND `type` = 'file'");
while ($post_files_file = mysql_fetch_array($q_files_file))
{
mysql_query("DELETE FROM `comm_files_komm` WHERE `id_file` = '$post_files_file[id]' AND `id_comm` = '$post_comm[id]'");
mysql_query("DELETE FROM `comm_files_rating` WHERE `id_file` = '$post_files_file[id]' AND `id_comm` = '$post_comm[id]'");
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$post_files_file[id]' AND `type` = 'file' AND `id_comm` = '$post_comm[id]'");
unlink(H."comm/files/c$post_comm[id]/d$post_files_file[id_dir]/$post_files_file[name].$post_files_file[ras].dat");
if (is_file(H."comm/screen_tmp/48-48_".$post_files_file['id']."screen.png"))unlink(H."comm/screen_tmp/48-48_".$post_files_file['id']."screen.png");
if (is_file(H."comm/screen_tmp/128-128_".$post_files_file['id']."screen.png"))unlink(H."comm/screen_tmp/128-128_".$post_files_file['id']."screen.png");
}
$q_files_dir2 = mysql_query("SELECT * FROM `comm_files` WHERE `id_comm` = '$post_comm[id]' AND `counter` like '%/$post_files_dir[id]/%' AND `type` = 'dir'");
while ($post_files_dir2 = mysql_fetch_array($q_files_dir2))
{
rmdir(H."comm/files/c$post_comm[id]/d$post_files_dir2[id]");
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$post_files_dir2[id]' AND `type` = 'dir' AND `id_comm` = '$post_comm[id]'");
}
mysql_query("DELETE FROM `comm_files` WHERE `id` = '$post_files_dir[id]' AND `type` = 'dir' AND `id_comm` = '$post_comm[id]'");
rmdir(H."comm/files/c$post_comm[id]/d$post_files_dir[id]");
}
rmdir(H."comm/files/c$post_comm[id]/d0");
// удаляем визиты
mysql_query("DELETE FROM `soo_visits` WHERE `id_comm` = '$post_comm[id]'");
// удаляем участников
mysql_query("DELETE FROM `comm_users` WHERE `id_comm` = '$post_comm[id]'");
// удаляем баны
mysql_query("DELETE FROM `comm_users_ban` WHERE `id_comm` = '$post_comm[id]'");
// удаляем записи в журнале
mysql_query("DELETE FROM `comm_journal` WHERE `id_comm` = '$post_comm[id]'");
// удаляем юзеров из ч/с сообщества
mysql_query("DELETE FROM `comm_blist` WHERE `id_comm` = '$post_comm[id]'");
// удаляем "пересоздателей"
mysql_query("DELETE FROM `comm_readmin` WHERE `id` = '$post_comm[id]'");
// удаляем сообщество
mysql_query("DELETE FROM `comm` WHERE `id` = '$post_comm[id]'");
}
// удаляем категорию
mysql_query("DELETE FROM `comm_cat`  WHERE `id` = '$cat[id]'");
header("Location:/comm");
exit;
}
echo "<form method='POST'>\n";
echo "Подтвердите удаление категории<br/>\n";
echo "<input type='submit' name='submited' value='Удалить'><a href='?'>Отмена</a>\n";
echo "</form>\n";
}
else{header("Location:/comm");exit;}
?>
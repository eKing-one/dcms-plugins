<?


$moders = mysql_query("SELECT `id` FROM `user` WHERE `group_access`>'1'");
$msg = "Вас вызывает пользователь $user[nick] в [url=/soo/$soo[id]/]эту Группу![/url] Возможно там творится каша и нужно ваше вмешательство.";

while($vizov = mysql_fetch_array($moders))
{

mysql_query("INSERT INTO `mail` (`id_user` , `id_kont` ,`msg` , `time` )VALUES ('$user[id]', '$vizov[id]', '$msg', '$time')");
mysql_query("INSERT INTO `konts` VALUES('$user[id]', '$vizov[id]', '$time')");

}


?>
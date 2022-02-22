<?
if($gruppy['ban']!=NULL && $gruppy['ban']>$time)
{
header("Location:ban.php?s=$gruppy[id]");
}
elseif(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy`='$gruppy[id]' AND `id_user` = '$user[id]' AND `activate`='0' AND `invit`='0' AND `ban`>'$time' LIMIT 1"),0)==1)
{
header("Location:banned.php?s=$gruppy[id]");
}
?>
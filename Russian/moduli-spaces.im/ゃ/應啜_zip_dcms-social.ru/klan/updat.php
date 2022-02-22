<?
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
$id = intval($_GET['id']);
$q = mysql_query("SELECT * FROM `clan`");
while ($post = mysql_fetch_array($q))
{
$users = mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_clan` = '$post[id]' AND `activaty` = 0"),0);
$level = ($post['level']*5)*$users;

if ($level>=1000)
{
$balls = $level;
mysql_query("UPDATE `clan` SET `bank` = '".intval($post['bank']+$balls)."' WHERE `id` = '$post[id]' LIMIT 1");
}
elseif($level<1000)
{
$balls = $level*5;
mysql_query("UPDATE `clan` SET `bank` = '".intval($post['bank']+$balls)."' WHERE `id` = '$post[id]' LIMIT 1");
}

}
header("Location: adminka.php?id=$id");
?>
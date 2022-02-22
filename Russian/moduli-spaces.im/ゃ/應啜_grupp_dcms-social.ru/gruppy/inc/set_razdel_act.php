<?
if (isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='set' && isset($_POST['name']))
{

$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (strlen2($name)<3)$err='Слишком короткое название';
if (strlen2($name)>32)$err='Слишком днинное название';
$name=my_esc($name);


if (!isset($err)){

mysql_query("UPDATE `gruppy_forums` SET `name` = '$name' WHERE `id` = '$forum[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
$razdel=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_forums` WHERE `id` = '$forum[id]' AND `id_gruppy`='$gruppy[id]' LIMIT 1"));
msg('Изменения успешно приняты');
}
}

if (isset($_GET['act']) && isset($_GET['ok']) && $_GET['act']=='delete')
{

mysql_query("DELETE FROM `gruppy_forums` WHERE `id` = '$forum[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_forum_thems` WHERE `id_forum` = '$forum[id]' AND `id_gruppy`='$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_forum` = '$forum[id]' AND `id_gruppy`='$gruppy[id]'");
msg('Форум успешно удален');
err();
echo "<a href=\"forum.php?s=$gruppy[id]\">К списку форумов</a><br />\n";
echo "<a href=\"index.php?s=$gruppy[id]\">В сообщество</a><br/>\n";
include_once '../sys/inc/tfoot.php';
}
?>

<?
if(isset($_POST['ok']) && isset($_POST['name']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (strlen2($name)<3)$err[]='Короткое название раздела';
if (strlen2($name)>32)$err[]='Название раздела не должно быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В названии раздела обнаружен мат: '.$mat;
$name=my_esc($name);
if(isset($_POST['desc']) && $_POST['desc']!=NULL)
{
$desc = esc(stripcslashes(htmlspecialchars($_POST['desc'])));
if (strlen2($desc)<3)$err[]='Короткое описание раздела';
if (strlen2($desc)>100)$err[]='Описание раздела не должно быть длиннее 100 символов';
$mat=antimat($desc);
if ($mat)$err[]='В описании раздела обнаружен мат: '.$mat;
$desc=my_esc($desc);
}
else
{
$desc=NULL;
}
if(!isset($err))
{
mysql_query("INSERT INTO `gruppy_cat` (`name`, `desc`) values ('$name', '$desc')");
msg('Категория успешно создана');
}
}
if(isset($_GET['edit']) && isset($_POST['name']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_cat` WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1"),0)==1)
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (strlen2($name)<3)$err[]='Короткое название раздела';
if (strlen2($name)>32)$err[]='Название раздела не должно быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В названии раздела обнаружен мат: '.$mat;
$name=my_esc($name);
if(isset($_POST['desc']) && $_POST['desc']!=NULL)
{
$desc = esc(stripcslashes(htmlspecialchars($_POST['desc'])));
if (strlen2($desc)<3)$err[]='Короткое описание раздела';
if (strlen2($desc)>100)$err[]='Описание раздела не должно быть длиннее 100 символов';
$mat=antimat($desc);
if ($mat)$err[]='В описании раздела обнаружен мат: '.$mat;
$desc=my_esc($desc);
}
else
{
$desc=NULL;
}
if(!isset($err))
{
mysql_query("UPDATE `gruppy_cat` SET `name`='$name', `desc`='$desc' WHERE `id`='".intval($_GET['edit'])."' LIMIT 1");
msg('Категория успешно изменена');
}
}
if(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_cat` WHERE `id` = '".intval($_GET['del'])."' LIMIT 1"),0)==1)
{
$q=mysql_query("SELECT * FROM `gruppy` WHERE `id_cat`='".intval($_GET['del'])."'");
while ($delete = mysql_fetch_assoc($q))
{
mysql_query("DELETE FROM `gruppy_users` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_chat` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_news` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_bl` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_friends` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_friends` WHERE `id_friend`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_votes` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_votes_otvet` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_forums` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_forum_thems` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_forum_mess` WHERE `id_gruppy`='$delete[id]'");
mysql_query("DELETE FROM `gruppy` WHERE `id`='$delete[id]'");
mysql_query("DELETE FROM `gruppy_obmen_dir` WHERE `id_gruppy`='$delete[id]'");
$q2=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_gruppy`='$delete[id]'");
while ($del = mysql_fetch_assoc($q2))
{
unlink(H.'sys/gruppy/obmen/files/'.$del['id'].'.dat');
}
mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id_gruppy`='$delid'");
mysql_query("DELETE FROM `gruppy_obmen_komm` WHERE `id_gruppy`='$delid'");
}
mysql_query("DELETE FROM `gruppy` WHERE `id_cat`='".intval($_GET['del'])."'");
mysql_query("DELETE FROM `gruppy_cat` WHERE `id`='".intval($_GET['del'])."'");
msg('Категория успешно удалена');
}
err();
?>
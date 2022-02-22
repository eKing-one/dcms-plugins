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
mysql_query("INSERT INTO `diary_cat` (`name`, `desc`) values ('$name', '$desc')");
msg('Категория успешно создана');
}
}
if(isset($_GET['edit']) && isset($_POST['name']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_cat` WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1"),0)==1)
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
mysql_query("UPDATE `diary_cat` SET `name`='$name', `desc`='$desc' WHERE `id`='".intval($_GET['edit'])."' LIMIT 1");
msg('Категория успешно изменена');
}
}
if(isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_cat` WHERE `id` = '".intval($_GET['del'])."' LIMIT 1"),0)==1)
{
$q=mysql_query("SELECT * FROM `diary` WHERE `id_cat`='".intval($_GET['del'])."'");
while ($delete = mysql_fetch_assoc($q))
{
$q2=mysql_query("SELECT * FROM `diary_images` WHERE `id_diary`='$delete[id]'");
while ($delet = mysql_fetch_assoc($q))
{
unlink(H.'diary/images/48/'.$delet['id'].'.'.$delet['ras'].'');
unlink(H.'diary/images/128/'.$delet['id'].'.'.$delet['ras'].'');
unlink(H.'diary/images/640/'.$delet['id'].'.'.$delet['ras'].'');
unlink(H.'diary/images/'.$delet['id'].'.'.$delet['ras'].'');
}
mysql_query("DELETE FROM `diary_rating` WHERE `id_diary`='$delete[id]'");
mysql_query("DELETE FROM `diary_images` WHERE `id_diary`='$delete[id]'");
mysql_query("DELETE FROM `diary_komm` WHERE `id_diary`='$delete[id]'");
mysql_query("DELETE FROM `diary` WHERE `id`='$delete[id]'");
}
mysql_query("DELETE FROM `diary_cat` WHERE `id`='".intval($_GET['del'])."'");
msg('Категория успешно удалена');
}
err();
?>
<?


if (isset($user) && $user['id']==$ank['id']){
if (isset($_GET['act']) && $_GET['act']=='create' && isset($_GET['ok']) && isset($_POST['name']) && isset($_POST['opis']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])),1);
if (isset($_POST['translit1']) && $_POST['translit1']==1)$name=translit($name);
if (strlen2($name)<3)$err='Короткое название';
if (strlen2($name)>32)$err='Название не должно быть длиннее 32-х символов';
$name=my_esc($name);

$msg=$_POST['opis'];
if (isset($_POST['translit2']) && $_POST['translit2']==1)$msg=translit($msg);
//if (strlen2($msg)<10)$err='Короткое описание';
if (strlen2($msg)>256)$err='Длина описания превышает предел в 256 символов';
$msg=my_esc($msg);

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery` WHERE `id_user` = '$ank[id]' AND `name` = '$name'"),0)!=0)
$err='Альбом с таким названием уже существует';


if (!isset($err))
{
mysql_query("INSERT INTO `gallery` (`opis`, `time_create`, `id_user`, `name`, `time`) values('$msg', '$time', '$ank[id]', '$name', '$time')");
msg('Фотоальбом успешно создан');
}



}

}
?>
<?
if($_GET['new']=='ok')
{
if(isset($_POST['name']) && $_POST['name']!=NULL && isset($_POST['desc']) && $_POST['desc']!=NULL)
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `name` = '".htmlspecialchars($_POST['name'])."' LIMIT 1"),0)==0)
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
if (strlen2($name)<3)$err[]='Короткое название';
if (strlen2($name)>32)$err[]='Название не должно быть длиннее 32-х символов';
$mat=antimat($name);
if ($mat)$err[]='В названии обнаружен мат: '.$mat;
$name = htmlspecialchars($_POST['name']);

$desc = esc(stripcslashes(htmlspecialchars($_POST['desc'])));
if (strlen2($desc)<3)$err[]='Короткое описание';
if (strlen2($desc)>100)$err[]='Описание не должно быть длиннее 100 символов';
$mat=antimat($desc);
if ($mat)$err[]='В описании обнаружен мат: '.$mat;
$desc=my_esc($desc);
}
else
{
$err[]='Сообщество с таким названием уже существует';
}
}
else
{
$err[]='Одно из полей не заполнено';
}
if(!isset($err))
{
mysql_query("INSERT INTO `gruppy` (`id_cat`, `name`, `desc`, `admid`, `time`) values ('$r', '$name', '$desc', '$user[id]', '$time')");
$soo_id=mysql_insert_id();
msg('Группа успешно создано.');
echo '<div class="foot">';
echo'<img src="img/back.png" alt=""/> <a href="/gruppy/'.$soo_id.'">Перейти в Группу</a><br/>';
echo '</div>';
}
}
?>

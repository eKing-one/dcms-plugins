<?
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm` WHERE `id` = '".intval($_GET['id'])."'"),0)!=0)
{
$comm=mysql_query("SELECT * FROM `comm` WHERE `id` = '".intval($_GET['id'])."'");
$comm=mysql_fetch_array($comm);

$cat=mysql_query("SELECT * FROM `comm_cat` WHERE `id` = '$comm[id_cat]'");
$cat=mysql_fetch_array($cat);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_users` WHERE `id_comm` = '$comm[id]' AND `activate` = '1' AND `invite` = '0'"),0)==0)$comm['id_user']=0;
$ank=get_user($comm['id_user']); // sozdak

$set['title'] = 'Сообщества - '.htmlspecialchars($comm['name']).' - Аватар'; // Заголовок страницы
include_once '../sys/inc/thead.php';
title();
aut();
if($ank['id']==$user['id'] && isset($user))
{
if (isset($_FILES['file']) && isset($_POST['submited']))
{
$file_path = $_FILES['file']['tmp_name'];
$save_path = H."comm/img/avatar/";
$type = $_FILES['file']['type'];
if ($type!=='image/jpeg' && $type!=='image/jpg' && $type!=='image/gif' && $type!=='image/png')$err[]="Это не картинка";
else
{
if (is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))unlink(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png");
$comm['mdi']=md5(rand(12345,99999999));
mysql_query("UPDATE `comm` SET `mdi` = '$comm[mdi]' WHERE `id` = '$comm[id]'");
$name = "comm.".$comm['id'].".".$comm['mdi'].".img.png";
create_screen($file_path, $save_path, 96, 96, NULL, $name);
msg("Аватар успешно установлен");
}
}
if(isset($_GET['rotate']) && ($_GET['rotate']=='right' || $_GET['rotate']=='left') && is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))
{
$rotate=$_GET['rotate'];
if($rotate=='left')$degrees=90;else $degrees=270;

// Файл и угол поворота
$icon = H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png";
// Загрузка изображения
$source = imagecreatefromstring(file_get_contents($icon));
// Поворот
$rotate = imagerotate($source, $degrees, 0);
// Ввод
if (is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))unlink(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png");
$comm['mdi']=md5(rand(12345,99999999));
mysql_query("UPDATE `comm` SET `mdi` = '$comm[mdi]' WHERE `id` = '$comm[id]'");
imagepng($rotate,H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png");
}
if (isset($_GET['delete']))
{
if (is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))unlink(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png");
}
err();
?>
	<table class='post'>
		<tr>
			<td class='icon48'>
		<?
		if (is_file(H."comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png"))
		{
			echo "<img src='/comm/img/avatar/comm.".$comm['id'].".".$comm['mdi'].".img.png' /><br/>\n";
			?>
			<style>
				img.rotate {
					border: 2px solid #CCEDEC;
					border-radius: 3px;
				}
				img.rotate:hover {
					border: 2px solid #CCEDEC;
					background: #CCEDEC;
					border-radius: 3px;
				}
			</style>
			<?
			echo "<center><a href='?act=comm_avatar&id=$comm[id]&rotate=left'><img src='/comm/img/rotate_left.png' class='rotate' /></a> <a href='?act=comm_avatar&id=$comm[id]&rotate=right'><img src='/comm/img/rotate_right.png' class='rotate' /></a><br /><a href='?act=comm_avatar&id=$comm[id]&delete'>Удалить</a></center>\n";
		}
		else echo "<img src='/comm/screen_tmp/48-48_0screen.png'/><br/>\n";
		?>
			</td>
			<td class='p_t'>
				<form method='post' enctype='multipart/form-data'>
				<input type='file' name='file' accept='image/*,image/gif,image/png,image/jpeg' />
				<input value='Заменить' type='submit' name='submited' /><br /><a href='?act=comm_settings&id=<?php echo $comm['id'];?>'>Назад</a>
				</form>
				</td>
		</tr>
	</table>
						<div class='menu'>
							Можно загружать картинки форматов: GIF, JPG, PNG<br />Качественное преобразование GIF-анимации не гарантируется<br />
						</div>
<?
echo "<div class='foot'>&raquo; <a href='?act=comm&id=$comm[id]'>В сообщество</a></div>";
}
else{header("Location:/comm");exit;}
}
else{header("Location:/comm");exit;}
?>
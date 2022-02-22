<?
only_reg();
$ank = get_user(intval(@$_GET['user_id']));
if (!@$ank['id']) {
	$set['title'] = 'Ошибка!';
	include('../../sys/inc/thead.php');
	title();
	aut();
	$err[] = 'Пользователь не найден';
	err();
	?>
	<div class="foot">
		<img src="/user/us_guest/images/back.png" alt="Назад"> <a href="/">Назад</a><br />
	</div>
	<?
	include('../../sys/inc/tfoot.php');
	exit();
}
$count_attached_files = mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_files` WHERE `id_comment` = '0' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user[id]'"), 0);
$sess_var = "us_guest_mess_new_".$ank['id'];
$set['title'] .= ' '.$ank['nick'];
include('../../sys/inc/thead.php');
title();
aut();
include('inc/guest_enter.php');
if (isset($_GET['type']) && $_GET['type'] == 'graf') {
	if (!$webbrowser) {
		$err = 'Ваше устройство не поддерживает прикрепление граффити';
		err();
	}
	elseif ($count_attached_files >= $max_attach_files) {
		$err = 'Что бы прикрепить граффити, Вам нужно удалить некоторые прикрепленные файлы';
		err();
	} else {
	if (isset($_GET['send'])) {
		if(isset($_FILES['Filedata'])) {
		  $fHandle = fopen($_FILES['Filedata']['tmp_name'], "rb");
		  if($fHandle) {
		    $fData      = bin2hex(fread($fHandle, 32));
		    if($fData == "89504e470d0a1a0a0000000d494844520000024a0000012508060000001b69cd") {
		      $fImageData = getimagesize($_FILES['Filedata']['tmp_name']);
		      if($fImageData[0] == 586 && $fImageData[1] == 293) {
		        $file_time  = time();
		        $file_rand  = rand(0,9);
		        $file_time  = $file_time . $file_rand;
		        $file_name  = md5($file_time) . ".png";
		        $i      = 0;
		        while(file_exists($file_name) && $i < 20) {
		          $i++;
		          $file_time  = time();
		          $file_rand  = rand(0,9)+$i;
		          $file_time  = $file_time . $file_rand;
		          $file_name  = md5($file_time) . ".png";
		        }
		        $origImage    = imagecreatefrompng($_FILES['Filedata']['tmp_name']);
		        $newImage   = imagecreatetruecolor(587, 295);
		        imagecopyresized($newImage, $origImage, 0, 0, 0, 0, 587, 295, $fImageData[0], $fImageData[1]);
		        mysql_query("INSERT INTO `us_guest_files` (`id_comment`, `id_user_adm`, `id_user`, `name`, `ras`, `time`) VALUES ('0', '$ank[id]', '$user[id]', '".md5($file_time)."', 'png', '".time()."')");
		        $fid = mysql_insert_id();
		        imagepng($newImage,  H."user/us_guest/files/$fid.dat");
		        exit();
		      }
		    }
		  }
		}
	}
	?>
	<script src="/user/us_guest/graf/swfobject.js"></script>
	<embed type="application/x-shockwave-flash" src="/user/us_guest/graf/graffiti.swf" width="600" height="400" style="undefined" id="player" name="player" quality="high" allowfullscreen="false" flashvars="overstretch=false&amp;postTo=/user/us_guest/attach/<? echo $ank['id']?>/graf?send&amp;redirectTo=/user/us_guest/attach/<? echo $ank['id']?>/files"\>
	</embed>
	<?
	}
} else {
	if (isset($_POST['submited']) && isset($user) && isset($_FILES['file']) && is_file($_FILES['file']['tmp_name']) && $_POST['mdp'] == $mdp && $count_attached_files < $max_attach_files && ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0))) {	
		$file = $_FILES['file'];
		$fst_name = $file['name'];
		$fst_name = ereg_replace('(#|\?)', NULL, $fst_name);
		$ras = eregi_replace('^.*\.', NULL, $fst_name); // расширение без имени файла
		$name = eregi_replace('\.[^\.]*$', NULL, $fst_name); // имя файла без расширения
		if (!$name)$err[] = 'Неверное название файла';
		if (filesize($file['tmp_name']) > 10*1048576)$err[]='Размер файла превышает установленные ограничения';
		$name = my_esc($name);
		$ras = my_esc($ras);
		if (!isset($err)) {
			mysql_query("INSERT INTO `us_guest_files` (`id_comment`, `id_user_adm`, `id_user`, `name`, `ras`, `time`) VALUES ('0', '$ank[id]', '$user[id]', '$name', '$ras', '".time()."')");
			$fid = mysql_insert_id();
			if (copy($file['tmp_name'], H."user/us_guest/files/$fid.dat")) {
				chmod(H."user/us_guest/files/$fid.dat", 0777);
			} else {
				mysql_query("DELETE FROM `us_guest_files` WHERE `id` = '$fid'");
			}
			header("Location: ?");
			exit();
		}
	}
	if (isset($_GET['delete_file']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_files` WHERE `id` = '".intval($_GET['delete_file'])."' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user[id]' AND `id_comment` = '0'"), 0)) {
		mysql_query("DELETE FROM `us_guest_files` WHERE `id` = '".intval($_GET['delete_file'])."'");
		if (is_file(H."user/us_guest/files/".intval($_GET['delete_file']).".dat"))unlink(H."user/us_guest/files/".intval($_GET['delete_file']).".dat");
		if (is_file(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_small.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_small.png");
		if (is_file(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_big.png"))unlink(H."user/us_guest/screens/user_$ank[id]_file_".intval($_GET['delete_file'])."_big.png");
		header("Location: ?");
		exit();
	}
	err();
	$comment_id_attach = 0;
	$user_id_attach = $user['id'];
	$div_style_attach = ' style="margin: 4px 0;" class="p_m"';
	$delete_attach = 1;
	include('inc/files_show.php');
	if (isset($user)) {
		if ($ank['guestbook_komm']=='all' || $ank['guestbook_komm']=='only_me' && ($user['id']==$ank['id'] || $user['group_access']>=7) || $ank['guestbook_komm']=='friends' && ($ank['id']==$user['id'] || $user['group_access']>=7 || mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$ank[id]') OR (`user` = '$ank[id]' AND `frend` = '$user[id]')"),0)!=0)) {
			if (!isset($_SESSION[$sess_var])) {
				$_SESSION[$sess_var] = array(
					'msg' => null
				);
			}
			?>
			<form action='' method='POST' enctype='multipart/form-data'>
				Файл (<? echo size_file(10*1048576)?>):<br />
				<input name='file' type='file'<? echo ($count_attached_files >= $max_attach_files?" disabled":null)?>/><br />
				<input type="hidden" name="mdp" value="<? echo $mdp?>">
				<input value="Отправить" type="submit" name="submited"<? echo ($count_attached_files >= $max_attach_files?" disabled":null)?>/>
			</form>
			<?
		} else {
			?>
			<div class="err">
				Автор ограничил круг лиц, которые могут оставлять сообщения!
			</div>
			<?
		}
	} else {
		?>
		<div class="msg">
			<img src="/user/us_guest/images/add.png" alt=""> <a href="/aut.php">Добавить сообщение</a><br />
		</div>
		<?
	}
}
?>
<div class="foot">
	<img src="/user/us_guest/images/back.png" alt=""> <a href="/user/us_guest/?user_id=<? echo $ank['id']?>">Назад</a>
</div>
<?
include('../../sys/inc/tfoot.php');
exit();
?>
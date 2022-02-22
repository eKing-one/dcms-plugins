<?
if($dir_id['upload']==1 && (!isset($set['obmen_limit_up']) || $set['obmen_limit_up']<=$user['balls'])){

if(isset($_GET['act']) && $_GET['act']=='upload' && isset($_GET['ok']) && $l!='/'){

if(!isset($_FILES['file'])){
	$err[]='Ошибка при выгрузке файла';
	}else if(!isset($_FILES['file']['tmp_name']) || filesize($_FILES['file']['tmp_name'])>$dir_id['maxfilesize']){
		$err[]='Размер файла превышает установленные ограничения';
		}else{
			$file=esc(stripcslashes(htmlspecialchars($_FILES['file']['name'])));
			$file=ereg_replace('(#|\?)', NULL, $file);
			$name=eregi_replace('\.[^\.]*$', NULL, $file);
			$ras=strtolower(eregi_replace('^.*\.', NULL, $file));
			$type=$_FILES['file']['type'];
			$size=filesize($_FILES['file']['tmp_name']);
			$rasss=explode(';', $dir_id['ras']);
			$ras_ok=false;

for($i=0;$i<count($rasss);$i++){

if($rasss[$i]!=NULL && $ras==$rasss[$i]){
	$ras_ok=true;
	}
	}

if(!$ras_ok){
	$err='Неверное расширение файла';
	}
	}

$opis=NULL;

if(isset($_POST['opis'])){
	$opis=stripslashes(htmlspecialchars(esc($_POST['opis'])));
	}

if(!isset($err)){
	mysql_query("INSERT INTO `gruppy_obmen_files` (`id_gruppy`, `id_dir`, `name`, `ras`, `type`, `size`, `time`, `time_last`, `id_user`, `opis`) VALUES ('$gruppy[id]', '$dir_id[id]', '$name', '$ras', '$type', '$size', '$time', '$time', '$user[id]', '$opis')");
	$id_file=mysql_insert_id();

if(!@copy($_FILES['file']['tmp_name'], H."sys/gruppy/obmen/files/$id_file.dat")){
	mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id` = '$id_file' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
	$err[]='Ошибка при выгрузке';
	}
	}

if(!isset($err)){

chmod(H."sys/gruppy/obmen/files/$id_file.dat", 0666);

if(isset($_FILES['screen']) && $imgc=@imagecreatefromstring(file_get_contents($_FILES['screen']['tmp_name']))){
	$img_x=imagesx($imgc);
	$img_y=imagesy($imgc);

if($img_x==$img_y){
	$dstW=128; // ширина
	$dstH=128; // высота
	}elseif ($img_x>$img_y){
		$prop=$img_x/$img_y;
		$dstW=128;
		$dstH=ceil($dstW/$prop);
		}else{
			$prop=$img_y/$img_x;
			$dstH=128;
			$dstW=ceil($dstH/$prop);
			}

$screen=imagecreatetruecolor($dstW, $dstH);
imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
imagedestroy($imgc);
$screen=img_copyright($screen);
imagegif($screen,H."sys/gruppy/obmen/screens/128/$id_file.gif");
imagedestroy($screen);
}
}
}
}
?>
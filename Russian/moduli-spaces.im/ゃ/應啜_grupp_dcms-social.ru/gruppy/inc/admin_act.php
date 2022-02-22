<?php
if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['ok']) && $l!='/'){
$q=mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND `dir_osn` like '$l%'");
while($post = mysql_fetch_array($q)){
$q2=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_dir` = '$post[id]' AND `id_gruppy` = '$gruppy[id]'");
while($post2 = mysql_fetch_array($q2)){	unlink(H.'sys/gruppy/obmen/files/'.$post2['id'].'.dat');
	mysql_query("DELETE FROM `soo_omen_komm` WHERE `id_file`='$post2[id]' AND `id_gruppy`='$gruppy[id]'");
	}

mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id_dir` = '$post[id]' AND `id_gruppy` = '$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_obmen_dir` WHERE `id` = '$post[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
}

$q2=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_dir` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]'");
while($post = mysql_fetch_array($q2)){	unlink(H.'sys/gruppy/obmen/files/'.htmlspecialchars($post['id']).'.dat');
	mysql_query("DELETE FROM `soo_omen_komm` WHERE `id_file`='$post2[id]' AND `id_gruppy`='$gruppy[id]'");
	}

mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id_dir` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]'");
mysql_query("DELETE FROM `gruppy_obmen_dir` WHERE `id` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
$l=$dir_id['dir_osn'];
msg('Папка успешно удалена');

$dir_id=mysql_fetch_array(mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `dir` = '/$l' OR `dir` = '$l/' OR `dir` = '$l' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"));
$id_dir=$dir_id['id'];
}


if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='mesto' && isset($_GET['ok']) && isset($_POST['dir_osn']) && $l!='/'){
if($_POST['dir_osn']==NULL){	$err= "Не выбран коненый путь";
	}else{
$q=mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND `dir_osn` like '$l%'");
while($post = mysql_fetch_array($q)){	$new_dir_osn=eregi_replace("^$l/",htmlspecialchars($_POST['dir_osn']),$post['dir_osn']).$dir_id['name'].'/';
	$new_dir=$new_dir_osn.$post['name'];
	mysql_query("UPDATE `gruppy_obmen_dir` SET `dir`='$new_dir/', `dir_osn`='$new_dir_osn' WHERE `id` = '$post[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
	}

$l=$_POST['dir_osn'];
mysql_query("UPDATE `gruppy_obmen_dir` SET `dir`='".$l."$dir_id[name]/', `dir_osn`='".$l."' WHERE `id` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
msg('Папка успешно перемещена');

$dir_id=mysql_fetch_array(mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"));
$id_dir=$dir_id['id'];
}
}


if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='rename' && isset($_GET['ok']) && isset($_POST['name']) && $l!=NULL){
if($_POST['name']==NULL){	$err= "Введите название папки";
	}else{		$newdir=retranslit(esc(htmlspecialchars($_POST['name']),1));

if(!isset($err)){
if($l!='/'){	$l.='/';
	}

$downpath=eregi_replace('[^/]*/$', NULL, $l);
mysql_query("UPDATE `gruppy_obmen_dir` SET `name`='".esc(htmlspecialchars($_POST['name']),1)."', `dir`='".$downpath."$newdir/', `dir_osn`='".$downpath."' WHERE `id_gruppy` = '$gruppy[id]' AND `dir` = '/$l' OR `dir` = '$l/' OR `dir` = '$l' LIMIT 1");
msg('Папка успешно переименована');

$l=$downpath.$newdir;
$dir_id=mysql_fetch_array(mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND `dir` = '/$l' OR `dir` = '$l/' OR `dir` = '$l' LIMIT 1"));
$id_dir=$dir_id['id'];
}
}
}


if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='mkdir' && isset($_GET['ok']) && isset($_POST['name'])){
if($_POST['name']==NULL){	$err= "Введите название папки";
	}else{		$newdir=retranslit(esc(htmlspecialchars($_POST['name']),1));

if(isset($_POST['upload']) && htmlspecialchars($_POST['upload'])=='1'){	$upload=1;
	}else{		$upload=0;
		}

if(!isset($_POST['ras']) || htmlspecialchars($_POST['ras'])==NULL){	$upload=0;
	}

$size=0;

if($upload==1 && isset($_POST['size']) && isset($_POST['mn'])){	$size=intval($_POST['size'])*intval($_POST['mn']);

if($upload_max_filesize<$size){	$size=$upload_max_filesize;
	}
}else{	$upload=0;
	}

$ras=esc(htmlspecialchars($_POST['ras']),1);

if(!isset($err)){
if($l!='/'){	$l.='/';
	}

mysql_query("INSERT INTO `gruppy_obmen_dir` (`id_gruppy`, `name`, `ras`, `maxfilesize`, `dir`, `dir_osn`, `upload`) VALUES ('$gruppy[id]', '".esc(htmlspecialchars($_POST['name']),1)."', '$ras', '$size', '".$l."$newdir/', '".$l."', '$upload')");
msg('Папка "'.esc(htmlspecialchars($_POST['name']),1).'" успешно создана');
}
}
}


if($gruppy['admid']==$user['id'] && isset($_GET['act']) && $_GET['act']=='set' && isset($_GET['ok'])){
if(isset($_POST['upload']) && $_POST['upload']=='1'){	$upload=1;
	}else{		$upload=0;
		}

if(!isset($_POST['ras']) || $_POST['ras']==NULL){	$upload=0;
	}

$size=0;

if($upload==1 && isset($_POST['size']) && isset($_POST['mn'])){	$size=intval($_POST['size'])*intval($_POST['mn']);

if($upload_max_filesize<$size){	$size=$upload_max_filesize;
	}
	}else{		$upload=0;
		}


$ras=esc(htmlspecialchars($_POST['ras']),1);

if(!isset($err)){
if($l!='/'){	$l.='/';
	}

mysql_query("UPDATE `gruppy_obmen_dir` SET `ras`='$ras', `maxfilesize`='$size', `upload`='$upload' WHERE `id` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]'");
msg('Параметры папки успешно изменены');

$dir_id=mysql_fetch_array(mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id` = '$dir_id[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"));
$id_dir=$dir_id['id'];
}
}









?>
<?php

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

if(isset($_GET['id'])){
	$soo = intval($_GET['id']);
	}else{
		header("Location: /index.php");
		}

$admin = mysql_fetch_array(mysql_query("SELECT * FROM `community_user_incomm` WHERE `cid` = '$soo' AND `uid` = '".$user['id']."'"));

$set['title'] = 'Настройки &amp; '.$_SERVER['HTTP_HOST'];
include_once '../sys/inc/thead.php';

title();
aut();

echo '<div class="menu">';

$comm = mysql_fetch_array(mysql_query('SELECT * FROM `community_comm` WHERE `id` = '.$soo.' LIMIT 1'));
if(!isset($user)){
	echo '<div class="err">Гостям вход запрещён.</div>';
	}else if($soo==0 || $soo<0){
		echo '<div class="err">Иди нахуй! Хакер недоношеный!</div>';
		}else if($soo!=$comm['id']){
			echo '<div class="err">Сообщество не найдено.</div>';
			}else if($admin['priv']!=2){
				echo '<div class="err">Доступ закрыт</div>';
				}else if(mysql_result(mysql_query("SELECT COUNT(*) FROM `comm_ban` WHERE `id_user` = '$user[id]' AND `id_comm` = '$soo' AND `time` > '$time'"), 0)!=0){
					header('Location: ban.php?id='.$soo);
					}else{

if(!isset($_GET['mode'])){

if(isset($_GET['name'])){
	msg('Название должно быть от 5 до 100 букв');
	}else if(isset($_GET['about'])){
		msg('Описание должно быть от 20 до 256 букв.');
		}else if(isset($_GET['slogan'])){
			msg('Слоган должен быть от 10 до 100 букв.');
			}else if(isset($_GET['status'])){
				msg('Ошибка в выборе статуса сообщества.');
				}else if(isset($_GET['no'])){
					msg('Изменения успешно приняты.');
					}

echo '<b>Управление сообществом.</b><br/>';
echo '&#187;&nbsp;<a href="upload.php?id='.$soo.'">Логотип</a><br/>';
echo '&#187;&nbsp;<a href="rules.php?id='.$soo.'">Правила</a><br/>';
echo '&#187;&nbsp;<a href="setting.php?id='.$soo.'&amp;mode=set">Сообщество</a><br/>';

if($comm['status']==2){
	echo '&#187;&nbsp;<a href="activate.php?id='.$soo.'">Активация участников</a><br/>';
	}

}else if($_GET['mode']=='set'){

if(isset($_POST['name']) && isset($_POST['about']) && isset($_POST['slogan']) && isset($_POST['status']) && $admin['priv']==2){
	$name = esc(stripcslashes(htmlspecialchars($_POST['name'])));
	$about = esc(stripcslashes(htmlspecialchars($_POST['about'])));
	$slogan = esc(stripcslashes(htmlspecialchars($_POST['slogan'])));
	$status = intval($_POST['status']);

if(strlen2($name)<10 && strlen2($name)>100){
	header("Location: setting.php?id=$soo&name");
	exit;
	}

if(strlen2($about)<20 && strlen2($about)>256){
	header("Location: setting.php?id=$soo&about");
	exit;
	}

if(strlen2($slogan)<10 && strlen2($slogan)>100){
	header("Location: setting.php?id=$soo&slogan");
	exit;
	}

if($status!=1 && $status!=2){
	header("Location: setting.php?id=$soo&status");
	exit;
	}

mysql_query("UPDATE `community_comm` SET `name` = '".mysql_real_escape_string($name)."', `about` = '".mysql_real_escape_string($about)."', `slogan` ='".mysql_real_escape_string($slogan)."', `status` ='".mysql_real_escape_string($status)."' WHERE `id` = '".$soo."'");
mysql_query("OPTIMIZE TABLE `community_comm`");
header("Location: setting.php?id=$soo&no");
}

$comm = mysql_fetch_array(mysql_query("SELECT * FROM `community_comm` WHERE `id` = '$soo' LIMIT 1"));
echo '<form method="post" action="setting.php?id='.$soo.'&amp;mode=set">';
echo 'Название:<br/><input type="text" name="name" value="'.esc(stripcslashes(htmlspecialchars($comm['name']))).'" maxlength="100"/><br/>';
echo 'Описание:<br/><input type="text" name="about" value="'.esc(stripcslashes(htmlspecialchars($comm['about']))).'" maxlength="256"/><br/>';
echo 'Слоган:<br/><input type="text" name="slogan" value="'.esc(stripcslashes(htmlspecialchars($comm['slogan']))).'" maxlength="100"/><br/>';
echo 'Статус сообщества:<br/><select name="status">';

if($comm['status']==1){
	$sel=' selected="selected"';
	}else{
		$sel=NULL;
		}
echo '<option value="1"'.$sel.'>Отрыт для чтения</option>';

if($comm['status']==2){
	$sel=' selected="selected"';
	}else{
		$sel=NULL;
		}
echo '<option value="2"'.$sel.'>Закрыт для чтения</option>';
echo '</select><br/>';
echo '<input type="submit" name="save" value="Сохранить"/></form>';
}

echo '&#187;&nbsp;<a href="comm.php?id='.$soo.'">В сообщество</a><br/>';
}

echo '</div>';
include_once '../sys/inc/tfoot.php';
?>
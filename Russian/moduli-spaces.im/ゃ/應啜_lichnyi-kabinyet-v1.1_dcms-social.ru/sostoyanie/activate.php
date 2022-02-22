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
$set['title'] = 'Активация участника';
include_once '../sys/inc/thead.php';

title();
aut();


$comm = mysql_fetch_array(mysql_query('SELECT * FROM `community_comm` WHERE `id` = '.$soo.' LIMIT 1'));

if(!isset($user)){
	echo '<div class="err">Доступ закрыт.</div>';
	}else if($soo==0 || $soo<0){
		echo '<div class="err">Иди нахуй! Хакер недоношеный!</div>';
		}else if($soo!=$comm['id']){
			echo '<div class="err">Сообщество не найдено.</div>';
			}else if($admin['priv']!=2){
				echo '<div class="err">Доступ закрыт.</div>';
				}else{

if($_GET['uid'] && $_GET['act']=='yes'){
	$uid = intval($_GET['uid']);
	$user_comm = mysql_fetch_array(mysql_query('SELECT * FROM `community_user_incomm` WHERE `cid` = '.$soo.' AND `uid` = '.$uid.' LIMIT 1'));

if($user_comm['uid']!=$uid){
	echo '<div class="err">Данный пользователь не подовал заявку на вступление.</div>';
	}else if($user_comm['activate']==1){
		echo '<div class="err">Данный пользователь уже был активирован.</div>';
		}else{
			mysql_query("UPDATE `community_user_incomm` SET `activate` = '1' WHERE `uid` = '$uid' AND `cid` = '$soo' LIMIT 1");
			$msg = 'Ваша заявка на вступление в сообщество [b]'.$comm['name'].'[/b] успешно одобрена';
			mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '$uid', '$time', '$msg', '0')");
			mysql_query("OPTIMIZE TABLE `community_user_incomm`, `jurnal`");
			}
			}else if($_GET['uid'] && $_GET['act']=='no'){
				$uid = intval($_GET['uid']);
				$user_comm = mysql_fetch_array(mysql_query('SELECT * FROM `community_user_incomm` WHERE `cid` = '.$soo.' AND `uid` = '.$uid.' LIMIT 1'));

if($user_comm['uid']!=$uid){
	echo '<div class="err">Данный пользователь не подовал заявку на вступление.</div>';
	}else if($user_comm['activate']==1){
		echo '<div class="err">Данный пользователь уже был активирован.</div>';
		}else{
			mysql_query("DELETE FROM `community_user_incomm` WHERE `uid` = '$uid' AND `cid` = '$soo' LIMIT 1");
			$msg = 'Извените, но Вам отказали в вступлении в сообщество [b]'.$comm['name'].'[/b]';
			mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `time`, `msg`, `read`) VALUES ('0', '$uid', '$time', '$msg', '0')");
			mysql_query("OPTIMIZE TABLE `community_user_incomm`, `jurnal`");
			}
			}


$k_m=mysql_result(mysql_query("SELECT COUNT(*) FROM `community_user_incomm` WHERE `activate` = '0' AND `cid` = '$soo'"),0);
$k_page=k_page($k_m,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if($k_m=='0'){
	echo 'Нет пользователей для активации.<br/>';
	}

$q = mysql_query("SELECT * FROM `community_user_incomm` WHERE `cid` = '$soo' AND `activate` = '0' LIMIT $start, $set[p_str]");
while($p = mysql_fetch_array($q)){
	$a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $p[uid] LIMIT 1"));


echo "<table class='post'>";
echo "<tr>";
if($set['set_show_icon']==2){
echo "<td class='icon48' rowspan='2'>";
echo avatar($users['id']);
echo "</td>";
}
elseif($set['set_show_icon']==1)
{
echo '<img src="/style/themes/'.$set['set_them'].'/user/'.$a['pol'].'.png" alt=""/>';
echo "</td>";
}
echo "<td class='p_t'>";
echo '<a href="/info.php?id='.$a['id'].'">'.$a['nick'].'</a>'.online($p['uid']).' ('.vremja($p['time']).')<br/>';
echo "</td></tr><tr>";
if($set['set_show_icon']==1) echo"<td class='p_m' colspan='2'>"; else echo "<td class='p_m'>";
echo '<a href="activate.php?id='.$soo.'&amp;uid='.$p['uid'].'&amp;act=yes"> Активировать</a> | <a href="activate.php?id='.$soo.'&amp;uid='.$p['uid'].'&amp;act=no">Отказать </a>';}
echo "</td></tr>";
echo "</table>";

if($k_page>1){
	str("activate.php?id=$soo&amp;",$k_page,$page);
	}

echo "<div class='foot'>";
echo '<img src="/style/themes/'.$set['set_them'].'/icons/default.png"alt=""/>&nbsp;<a href="setting.php?id='.$soo.'">В управление</a><br/>';
echo '<img src="/style/themes/'.$set['set_them'].'/icons/default.png"alt=""/>&nbsp;<a href="comm.php?id='.$soo.'">В сообщество</a></div>';
}

include_once '../sys/inc/tfoot.php';
?>
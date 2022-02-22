<?php
########################################
#   Мод кланы для DCMS for VIPTABOR    #
#      Автор: DenSBK ICQ: 830-945	   #
#  Запрещена перепродажа данного мода. #
# Запрещено бесплатное распространение #
#    Все права пренадлежат автору      #
########################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';


if (mysql_result(mysql_query("SELECT COUNT(*) FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"),0)==0)
	{
	$id = intval($_GET['id']);
	$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$id' LIMIT 1"));
	
	$set['title'] = 'Вступить в Клан';

    	if (isset($_GET['yes']))
    	{
			if ($clan['all']==0)
			{
			$msg="Пользователь [url=/info.php?id=$user[id]]$user[nick][/url] вступил(а) в клан!";
			mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', '$msg', '$time')");
	    	mysql_query("INSERT INTO `clan_user` (`id_user`, `id_clan`, `time`) VALUES ('$user[id]', '$id', '$time')");
			}
			elseif ($clan['all']==1)
			{
			mysql_query("INSERT INTO `clan_user` (`id_user`, `id_clan`, `time`, `activaty`) VALUES ('$user[id]', '$id', '$time', '1')");
			}
            header("Location: myklan.php?".SID);
    	}

	include_once '../sys/inc/thead.php';
	title();
    aut();
	
	
    echo '<div class="post">';
    echo '<div class="p_m">Вы уверены что хотите вступить в клан '.$clan['name'].'?</div>';
    echo '</div>';

    echo '<table><td>';
    echo '<form action="enter.php?id='.$id.'&amp;yes" method="post">';
	echo '<input value="Вступить" type="submit">';
	echo '</form>';
	echo '</td><td>';
    echo '<form action="klan.php?id='.$id.'" method="post">';
	echo '<input value="Отмена" type="submit"></form>';
	echo '</td></table>';

	include_once '../sys/inc/tfoot.php';
	}
	else
	{
	
	$set['title'] = 'Покинуть клан';
	
    	if (isset($_GET['yes']))
    	{
			$id = intval($_GET['yes']);
			$msg="Пользователь [url=/info.php?id=$user[id]]$user[nick][/url] ушёл из клана!";
			mysql_query("INSERT INTO `clan_jurnal` (`id_clan`, `msg`, `time`) values('$id', '$msg', '$time')");
			
	    	mysql_query("DELETE FROM `clan_user` WHERE `id_clan` = '$id' AND `id_user` = '$user[id]' LIMIT 1");
            header("Location: index.php".SID);
    	}
	
	include_once '../sys/inc/thead.php';
	title();
    aut();

	$us = mysql_fetch_array(mysql_query("SELECT * FROM `clan_user` WHERE `id_user` = '$user[id]' LIMIT 1"));
	$clan = mysql_fetch_array(mysql_query("SELECT * FROM `clan` WHERE `id` = '$us[id_clan]' LIMIT 1"));
	
	if($clan['user']==$user['id']){
	echo '<div class="err">Создатель не может покинуть свой клан.</div>';
	}
	else
	{
    echo '<div class="post">';
    echo '<div class="p_m">Вы уверены что хотите покинуть Клан '.$clan['name'].'?</div>';
    echo '</div>';

    echo '<table><td>';
    echo '<form action="?yes='.$clan['id'].'" method="post">';
	echo '<input value="Покинуть" type="submit">';
	echo '</form>';
	echo '</td><td>';
    echo '<form action="myklan.php" method="post">';
	echo '<input value="Отмена" type="submit"></form>';
	echo '</td></table>';
	}
	}
include_once '../sys/inc/tfoot.php';
?>

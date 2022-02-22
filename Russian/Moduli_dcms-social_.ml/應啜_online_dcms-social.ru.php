<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

?>
<style>
	.block-online {
		padding: 10px 5px;
		background: #eee;
		border-bottom: 1px solid #ccc;
	}
	
	.mail-online-botton {
		background: #28ad60;
		padding: 3px 7px;
		border-radius: 5px;
		color: #ddd;
		font: 15px bold;
		cursor: pointer;
	}
	
	.mail-online-botton:hover {
		background: #2fcc70;
	}
	
	.mail-online-form {
		display: none;
	}
	
	.mail-online-form textarea {
		width: 100%;
		border: 1px solid #ccc;
		border-radius: 5px;
		box-sizing: border-box;
		padding: 10px;
	}
	
	.photo-online img {
		border-radius: 50%;
		float: left;
		margin-right: 10px;
	}
	
	.login-online {
		margin-bottom: 7px;
	}
	
	.online-info {
		height: 20px;
		margin-top: 10px;
	}
	
	.online-ank {
		float: left;
		border-bottom: 3px solid #95a6a6;
		margin: 0 2px;
	}

	.online-ank:hover {
		float: left;
		border-bottom: 3px solid #28ad60;
	}
	
	.online-rating {
		float: right;
		border-bottom: 3px solid #28ad60;
	}
	
	.lider-msg {
		color: #555;
		font-size: 13px;
		padding: 10px;
		padding-bottom: 0;
	}
</style>

<script type="text/javascript">
function lider(id){
	var menu = document.getElementById('lider-id-'+id).style;
	if(menu.display == 'none')	menu.display = 'block';
	else 						menu.display = 'none';
}

function online(id){
	var menu = document.getElementById('id-'+id).style;
	if(menu.display == 'none')	menu.display = 'block';
	else 						menu.display = 'none';
}
</script>
<?
$set['title'] = 'Сейчас на сайте'; // заголовок страницы
include_once 'sys/inc/thead.php';
title();
aut();

/*
==============================================
Этот скрипт выводит 1 случайного "Лидера" и 
ссылку на весь их список.(с) DCMS-Social
==============================================
*/

$k_lider = mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `time` > '$time'"),0);

$liders = mysql_fetch_assoc(mysql_query("SELECT * FROM `liders` WHERE `time` > '$time' ORDER BY rand() LIMIT 1"));

if ($k_lider > 0)
{
	?>
	<div class="block-online">
		<div class="photo-online"><?=user::avatar($liders['id_user'], 1)?></div>
		<div class="login-online"><?=group($liders['id_user']).user::nick($liders['id_user'], 1, 1, 1)?></div>
		
		<? if($user['id'] != $liders['id_user']){ ?>
			<span class="mail-online-botton" onclick="javascript:lider('<?=$liders['id_user']?>')">Написать</span>
			<form class="mail-online-form" id="lider-id-<?=$liders['id_user']?>" method="post" action="/mail.php?id=<?=$liders['id_user']?>">
				<textarea name="msg" placeholder="Введите сообщение"></textarea><br>
				<?php 
				if ($user['level']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_kont` = '$user[id]' AND `id_user` = '$liders[id_user]'"), 0)==0)
				echo "<img src='/captcha.php?SESS=$sess' width='100' height='30' alt='Проверочное число' /><br />\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
				?>
				<input type="submit" name="send" value="Отправить">
			</form>
		<? } ?>
	
		<? if ($liders['msg']) echo '<div class="lider-msg">'.output_text($liders['msg']).'</div>'; ?>
		<div class="online-info">
			<? if(isset($user)){ ?>
				<span class="online-ank"><a href="/user/gift/categories.php?id=<?=$ank['id']?>"><img src="/style/icons/present.gif"></a></span>
			<? } ?>
			<span class="online-ank"><a href="/user/frends/?id=<?=$ank['id']?>"><img src="/style/icons/druzya.png"></a></span>
			<span class="online-ank"><a href="/foto/<?=$ank['id']?>/"><img src="/style/icons/foto.png"></a></span>
			<span class="online-ank"><a href="/plugins/notes/user.php?id=<?=$ank['id']?>/"><img src="/style/icons/zametki.gif"></a></span>
			<span class="online-rating"><img src="/style/icons/lider.gif"> Все лидеры <?=$k_lider?></span>
		</div>
		<div class="clear: both"></div>
	</div>
	<?
}
	

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > '".(time()-600)."'"), 0);
$k_page = k_page($k_post,$set['p_str']);
$page = page($k_page);
$start = $set['p_str']*$page-$set['p_str'];

$q = mysql_query("SELECT id, rating, ank_city, pol, ank_d_r, ank_m_r, ank_g_r, ank_o_sebe, url, level, ip, ip_xff, ip_cl, ua, date_last FROM `user` WHERE `date_last` > '".(time()-600)."' ORDER BY `date_last` DESC LIMIT $start, $set[p_str]");

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo 'Сейчас на сайте никого нет';
	echo '</div>';
}

while ($ank = mysql_fetch_assoc($q))
{	
	?>
	<div class="block-online">
		<div class="photo-online"><?=user::avatar($ank['id'], 1)?></div>
		<div class="login-online"><?=group($ank['id']).user::nick($ank['id'], 1, 1, 1).otkuda($ank['url'])?></div>
		
		<? if($user['id'] != $ank['id']){ ?>
			<span class="mail-online-botton" onclick="javascript:online('<?=$ank['id']?>')">Написать</span>
			<form class="mail-online-form" id="id-<?=$ank['id']?>" method="post" action="/mail.php?id=<?=$ank['id']?>">
				<textarea name="msg" placeholder="Введите сообщение"></textarea><br>
				<?php
				if ($user['level']==0 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_kont` = '$user[id]' AND `id_user` = '$ank[id]'"), 0)==0)
				echo "<img src='/captcha.php?SESS=$sess' width='100' height='30' alt='Проверочное число' /><br />\n<input name='chislo' size='5' maxlength='5' value='' type='text' /><br/>\n";
				?>
				<input type="submit" name="send" value="Отправить">
			</form>
		<? } ?>

		<div class="online-info">
			<? if(isset($user)){ ?>
				<span class="online-ank"><a href="/user/gift/categories.php?id=<?=$ank['id']?>"><img src="/style/icons/present.gif"></a></span>
			<? } ?>
			<span class="online-ank"><a href="/user/frends/?id=<?=$ank['id']?>"><img src="/style/icons/druzya.png"></a></span>
			<span class="online-ank"><a href="/foto/<?=$ank['id']?>/"><img src="/style/icons/foto.png"></a></span>
			<span class="online-ank"><a href="/plugins/notes/user.php?id=<?=$ank['id']?>/"><img src="/style/icons/zametki.gif"></a></span>
			<span class="online-rating"><img src="/style/icons/rating.png"> <?=$ank['rating']?>%</span>
		</div>
		<div class="clear: both"></div>
	</div>
	<?
}

if ($k_page>1)str("?",$k_page,$page); // Вывод страниц

include_once 'sys/inc/tfoot.php';
?>
<?
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) die;
define('H', $_SERVER['DOCUMENT_ROOT'].'/');
include_once H.'sys/inc/start.php';
include_once H.'sys/inc/compress.php';
include_once H.'sys/inc/sess.php';
include_once H.'sys/inc/home.php';
include_once H.'sys/inc/settings.php';
include_once H.'sys/inc/db_connect.php';
include_once H.'sys/inc/ipua.php';
include_once H.'sys/inc/fnc.php';
include_once H.'sys/inc/user.php';
only_reg();

if (!isset($_GET['id']))
{
	echo 'Загрузка данных...<br />
<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>';
	exit;
}


$ank = get_user(intval($_GET['id']));

if (!$ank || $_SESSION['id_mail'] != $ank['id'])
{
	echo 'Загрузка данных...<br />
<img src="/ajax/mail/ajax-loader.gif" alt="Loading..."/>';
	exit;
}

$k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `unlink` != '$user[id]' AND `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]' AND  `unlink` != '$user[id]'"), 0);

if ($k_post == 0)
{
	echo '<div class="mess">';
	echo 'Нет сообщений';
	echo '</div>';
}

$q = mysql_query("SELECT * FROM `mail` WHERE `unlink` != '$user[id]' AND `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]' AND `unlink` != '$user[id]' ORDER BY id DESC LIMIT 7");


while ($post = mysql_fetch_array($q))
{

	$ank2 = get_user($post['id_user']);
	
	if ($post['read'] == 0)
	{
		echo '<div class="nav1" style="background: #fcdada;">';
	}
	else echo '<div class="nav2">';
	
	echo group($ank2['id']) . ' <a href="/info.php?id=' . $ank2['id'] . '">' . $ank2['nick'] . '</a> ';
	echo medal($ank2['id']) . online($ank2['id']) . ' (' . vremja($post['time']) . ')<br />';
	

	echo output_text($post['msg']) . '<br />';
	
	echo '</div>';
	
	if ($post['read'] == 0 && $post['id_kont'] == $user['id'] && $_SESSION['id_mail'] == $ank['id'])
	{
		// помечаем сообщения как прочитанные
		mysql_query("UPDATE `mail` SET `show_vk` = '0', `read` = '1' WHERE `id` = '$post[id]' AND `id_kont` = '$user[id]'");
		$audio = true;	
		?>
		<audio autoplay="autoplay">
		  <source src="/ajax/audio.ogg" type="audio/ogg; codecs=vorbis">
		  <source src="/ajax/audio.mp3" type="audio/mpeg">
		</audio>	
		<?	
	}
	
}

$_SESSION['id_mail'] = $ank['id'];
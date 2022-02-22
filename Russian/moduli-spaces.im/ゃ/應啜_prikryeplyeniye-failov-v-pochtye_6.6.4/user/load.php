<?
include_once '../sys/inc/start.php';
//include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

if (isset($_GET['load']) && is_file(H.'sys/mail/files/'.intval($_GET['load']).'.dat'))	
{

	$post = mysql_fetch_assoc(mysql_query("SELECT * FROM `mail` WHERE `id` = '" . intval($_GET['load']) . "' limit 1"));
	$ras = $post['ras'];	


		if ($post['id_kont'] == $user['id'] || $post['id_user'] == $user['id'])
		{
			include_once '../sys/inc/downloadfile.php';		
			DownloadFile(H.'sys/mail/files/' . $post['id'] . '.dat', retranslit($post['file']).'.' . $post['ras'], ras_to_mime($ras));
			exit;
		}
	
}
?>
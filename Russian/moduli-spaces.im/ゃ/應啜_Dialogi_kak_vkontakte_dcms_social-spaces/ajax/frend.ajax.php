<?
if(!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) die;

include_once '../sys/inc/start.php'; 
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php'; 
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

?>
<style>
.arr img {
	vertical-align: top;
}
.arr a {
	color:#fff;
}
</style>
<?

// Оповещения о новых сообщениях
if (isset($_SESSION['id_user']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_kont` = '$user[id]' AND `read` = '0' AND `show_vk` = '1'"), 0) > 0)
{
	$q = mysql_query("SELECT * FROM `mail` WHERE `id_kont` = '$user[id]' AND `read` = '0' AND `show_vk` = '1' ORDER BY `time` DESC");
	
	while ($post = mysql_fetch_assoc($q))
	{
		$ank = get_user($post['id_user']);
		
		if (!$ank)$ank['nick'] = 'Пользователь удален';
		elseif ($ank['id'] == 0)$ank['nick'] = 'Система оповещений &trade;';
		
		echo '<a href="#"><div class="arr" id="mail_vk' . $ank['id'] . '" style="text-align:left; background: #4d4d4d; padding: 10px; margin:5px; opacity: 0.9; filter: alpha(Opacity=70);color: #fff; border-radius: 6px; width: 290px;">';
		echo '<b>Новое сообщение</b><br /><table style="padding:0; margin:0;"><tr>
		<td style="vertical-align:top; width:50px;">';
		echo status($ank['id']) . '</td> 
		
		<td style="vertical-align:top; display: block;overflow: hidden; max-height:52px;">
		<b style="color:#8dadef">' . $ank['nick'] . '</b> <a style="color:#ffffff;" href="/mail.php?id=' . $ank['id'] . '">' . output_text($post['msg']) . '</a></td></tr></table>';
		echo '</div></a>';
		?>
		<script>   
		$(function() {
			$.fx.speeds._default = 100;   

		    $( "#mail_vk_modal" ).dialog({
		    autoOpen: false,
		    show: "blind",
		    hide: "blind",
		    modal: false
			});
			
			$( "#mail_vk<?=$ank['id']?>" ).click(function() 
			{
			    $( "#mail_vk_modal" ).dialog( "open" );
				loading_mail('<?=$ank['id']?>');
			    return false;
			});
		});
		</script>
		<?
	}
	
	// помечаем сообщения как прочитанные
	//mysql_query("UPDATE `mail` SET `show_vk` = '0' WHERE `id_kont` = '$user[id]'");
	$audio = true;

}

if (isset($audio))
{
	?>
	<audio autoplay="autoplay">
	  <source src="/ajax/audio.ogg" type="audio/ogg; codecs=vorbis">
	  <source src="/ajax/audio.mp3" type="audio/mpeg">
	</audio>
	<?	
}
exit;
?>
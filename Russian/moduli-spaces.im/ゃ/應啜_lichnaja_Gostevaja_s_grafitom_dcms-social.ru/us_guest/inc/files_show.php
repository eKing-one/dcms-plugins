<?
$div_style = ' style="margin: 4px 0; padding: 4px; border: 1px solid #CCCCCC; border-radius: 4px;"';
if (isset($div_style_attach))$div_style = $div_style_attach;
$select_files = mysql_query("SELECT * FROM `us_guest_files` WHERE `id_comment` = '$comment_id_attach' AND `id_user_adm` = '$ank[id]' AND `id_user` = '$user_id_attach'");
if (mysql_num_rows($select_files)) {
	$screen_work = array();
	while ($file = mysql_fetch_array($select_files)) {
		$file['ras'] = strtolower($file['ras']);
		if (is_file(H."user/us_guest/files/$file[id].dat")) {
			if (isset($div_style_attach)) {
				if (isset($num) && !$num) {
					echo '<div class="nav1" style="overflow: hidden;">';
					$num = 1;
				} else {
					echo '<div class="nav2" style="overflow: hidden;">';
					$num = 0;
				}
			} else {
			?>
			<div<? echo $div_style?>>
			<? } ?>
				<?
				if (isset($delete_attach) && $delete_attach) {
					?>
					<span style="float: right;"><a href="?delete_file=<? echo $file['id']?>"><img src="/user/us_guest/images/delete.png" alt=""></a></span>
					<?
				}
				if ($webbrowser)include('inc/screen_big.php');
				else include('inc/screen_small.php');
				if (isset($screen_work[$file['id']]))echo "<br />\n";
				?>
				<? if (!($file['icon'] == "image.png" && isset($screen_work[$file['id']]))) { include('inc/file_icon.php')?> <a href="/user/us_guest/download/<? echo $file['id']?>/<? echo htmlspecialchars($file['name'])?>.<? echo htmlspecialchars($file['ras'])?>"><? echo htmlspecialchars($file['name'].'.'.$file['ras'])?></a><? echo ($file['ras']=='jar'?" | <a href='/user/us_guest/download/$file[id]/".htmlspecialchars($file['name']).".jad'>JAD</a>":null)?> <span style="color: grey;">(<? echo size_file(filesize(H."user/us_guest/files/$file[id].dat"))?>)</span><? } ?>
				<?
				if (in_array($file['ras'], array('amr', 'mid', 'midi', 'mmf', 'mp3', 'wav', 'wma')) && $webbrowser) {
					?>
					<div>
						<object type="application/x-shockwave-flash" data="/user/us_guest/swf/player.swf" width="200" height="20" id="dewplayer" name="dewplayer">
							<param name="movie" value="/user/us_guest/swf/player.swf">
							<param name="flashvars" value="mp3=/user/us_guest/download/<? echo $file['id']?>/<? echo htmlspecialchars($file['name'].'.'.$file['ras'])?>">
							<param name="wmode" value="transparent">
						</object><br />
					</div>
					<?
				}
				?>
			</div>
			<?
		}
	}
}
?>
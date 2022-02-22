<?
function avatar($ID, $link = false, $dir = '50', $w = '50')
{
	/**
	* 
	* @var / Аватар, модифицировали функцию с целью облегчения кода
	* 
	*/
	
	$ank = mysql_fetch_array(mysql_query("SELECT pers, pers_time FROM `user` WHERE `id` = '$ID' LIMIT 1"));
	
	if ($ank['pers_time'] > time() - 60 * 60 * 24 * 7)
	{
		$arr = explode(':', $ank['pers']);
		$head = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `id` = '" . $arr[0] . "' AND `type` = 'head' LIMIT 1"));
		$foot = mysql_fetch_assoc(mysql_query("SELECT * FROM `gala_person` WHERE `id` = '" . $arr[1] . "' AND `type` = 'foot' LIMIT 1"));
		
		if ($dir == 50)
		$dir = '_small';
		else $dir = null;
		
		?>
		<div width="<?=$w?>" style="text-align: center;  display: inline-block; z-index: 5; position: relative; overflow: hidden; vertical-align: top;">
		<img src="/style/person/head<?=$dir?>/h (<?=$head['id']?>).png"  style="z-index: 1; position: relative;" />
		<br />
		<img src="/style/person/foot<?=$dir?>/f (<?=$foot['id']?>).png"  style="<?=($dir == '50' ? $head['style_small'] : $head['style'])?> position: relative;" />
		<br />
		</div>
		<?
	}
	else
	{
		$avatar = mysql_fetch_array(mysql_query("SELECT id,id_gallery,ras FROM `gallery_foto` WHERE `id_user` = '$ID' AND `avatar` = '1' LIMIT 1"));
		
		if (is_file(H."sys/gallery/$dir/$avatar[id].$avatar[ras]"))
		{
			return ($link == true ? '<a href="/foto/' . $ID . '/' . $avatar['id_gallery'] . '/' . $avatar['id'] . '/">' : false) . '
		<img class="avatar" src="/foto/foto' . $dir . '/' . $avatar['id'] . '.' . $avatar['ras'] . '" alt="Avatar"  width="' . $w . '" />' . ($link == true ? '</a>' : false);
		}
		else
		{
			return '<img class="avatar" src="/style/user/avatar.gif" width="' . $w . '" alt="No Avatar" />';  
		}
	}
}
?>
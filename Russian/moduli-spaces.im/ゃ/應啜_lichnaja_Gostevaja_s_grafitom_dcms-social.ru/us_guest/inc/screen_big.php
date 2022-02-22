<?
$file_src = H."user/us_guest/files/$file[id].dat";
$size = filesize($file_src);
$screen_src = H."user/us_guest/screens/user_$ank[id]_file_$file[id]_big.png";
if (in_array($file['ras'], array('jpg', 'jpeg', 'gif', 'png', 'bmp')) && !is_file($screen_src) && @$image_resourse = imagecreatefromstring(file_get_contents($file_src))) {
	$img_x = imagesx($image_resourse);
	$img_y = imagesy($image_resourse);
	if ($img_x == $img_y) {
		$new_x = 200; // ширина
		$new_y = 200; // высота
	} elseif ($img_x > $img_y) {
		$new_x = 200;
		$new_y = ceil($new_x / ($img_x / $img_y));
	} else {
		$new_y = 200;
		$new_x = ceil($new_y / ($img_y / $img_x));
	}
	$screen = imagecreatetruecolor($new_x, $new_y);
	$black = imagecolorallocate ($screen, 0, 0, 0);
	imagecopyresampled($screen, $image_resourse, 0, 0, 0, 0, $new_x, $new_y, $img_x, $img_y); // "склеиваем" изображения
	imagedestroy($image_resourse);
	if (imagepng($screen, $screen_src)) {
		$screen_work[$file['id']] = true;
	}
	imagedestroy($screen);
} elseif (in_array($file['ras'], array('3gp', '3gp2', 'asf', 'avi', 'flv', 'mp4', 'mpe', 'mpeg', 'mpg', 'wmv')) && !is_file($screen_src)) {
	@$movie = new ffmpeg_movie($file_src);
	@$frame_number = rand(1, $movie -> getFrameCount());
	@$frame = $movie -> getFrame($frame_number);
	@$image_resourse = $frame -> toGDImage();
	if ($image_resourse) {
		$img_x = imagesx($image_resourse);
		$img_y = imagesy($image_resourse);
		if ($img_x == $img_y) {
			$new_x = 200; // ширина
			$new_y = 200; // высота 
		} elseif ($img_x > $img_y) {
			$new_x = 200;
			$new_y = ceil($new_x / ($img_x / $img_y));
		} else {
			$new_y = 200;
			$new_y = ceil($new_y / ($img_y / $img_x));
		}
		$screen = imagecreatetruecolor($new_x, $new_y);
		$black = imagecolorallocate ($screen, 0, 0, 0);
		imagecopyresampled($screen, $image_resourse, 0, 0, 0, 0, $new_x, $new_y, $img_x, $img_y); // "склеиваем" изображения
		imagedestroy($image_resourse);
		if (imagepng($screen, $screen_src)) {
			$screen_work[$file['id']] = true;
		}
		imagedestroy($screen);
	}
} elseif (is_file($screen_src)) {
	$screen_work[$file['id']] = true;
}
if (isset($screen_work[$file['id']])) {
	?>
	<a href='/user/us_guest/download/<? echo $file['id']?>/<? echo htmlspecialchars($file['name'].'.'.$file['ras'])?>'><img src="/user/us_guest/screens/user_<? echo $ank['id']?>_file_<? echo $file['id']?>_big.png" alt=""></a>
	<?
}
if (in_array($file['ras'], array('3gp', '3gp2', 'asf', 'avi', 'flv', 'mp4', 'mpe', 'mpeg', 'mpg', 'wmv')))$file['icon'] = "video.png";
elseif (in_array($file['ras'], array('jpg', 'jpeg', 'gif', 'png', 'bmp')))$file['icon'] = "image.png";
elseif (in_array($file['ras'], array('jar', 'jad', 'sis', 'exe', 'msi', 'sisx', 'apk', 'zip', 'rar', '7z')))$file['icon'] = "app.png";
elseif (in_array($file['ras'], array('sql', 'php', 'txt', 'doc', 'docx', 'pdf')))$file['icon'] = "text.png";
elseif (in_array($file['ras'], array('amr', 'mid', 'midi', 'mmf', 'mp3', 'wav', 'wma')))$file['icon'] = "music.png";
else $file['icon'] = "unknown_file.png";
?>
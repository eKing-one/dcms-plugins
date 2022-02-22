<?
$name = $_GET['name'];
$id = eregi_replace('\.[^\.]*$', NULL, $name); // имя файла без расширения
$zip_images = H."user/us_guest/inc/zip_images.zip";
if (is_numeric($id) && is_file(H."user/us_guest/files/$id.dat")) {
	$zip = new ZipArchive;
	$zip -> open(H."user/us_guest/files/$id.dat");
	$content = $zip -> getFromName("META-INF/MANIFEST.MF");
	if (!$content)$content = $zip -> getFromName("META-INF/manifest.mf");
	$icon = false;
	if (@eregi("MIDlet-Icon:[^(\n|\r)]*(\n|\r)", $content, $jad))
	$icon=eregi_replace("(MIDlet-Icon:( )*)|(\n|\r)", NULL, $jad[0]);
	elseif (@eregi("MIDlet-1:[^(\n|\r)]*(\n|\r)", $content, $jad)) {
		$icon=eregi_replace("(MIDlet-1:( )*)|(\n|\r)", NULL, $jad[0]);
		$icon=eregi_replace("(^[^,]*,)|(,[^,]*$)", NULL, $icon);
	}
	$icon=eregi_replace('^ *| *$', NULL, $icon);
	$icon=ereg_replace("(^(/){1,})|((/){1,}$)","",$icon);
	if (!$icon)$icon=false;
	if ($icon) {
		$content = $zip -> getFromName($icon);
		header("Content-type: image/png");
		echo $content;
		exit();
	} else {
		$zip -> open($zip_images);
		$content = $zip -> getFromName('jar.png');
		header("Content-type: image/png");
		echo $content;
		exit();
	}
} else {
	$zip = new ZipArchive;
	$zip -> open($zip_images);
	$content = $zip -> getFromName($name);
	if ($content) {
		header("Content-type: image/png");
		echo $content;
		exit();
	} else {
		$content = $zip -> getFromName('unknown_file.png');
		header("Content-type: image/png");
		echo $content;
		exit();
	}
}
?>
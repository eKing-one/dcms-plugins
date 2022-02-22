<?
if (!mysql_result(mysql_query("SELECT COUNT(*) FROM `us_guest_files` WHERE `id` = '".intval($_GET['file'])."'"),0)) {
	echo "Файл не найден";
	exit();
}
$file = mysql_fetch_array(mysql_query("SELECT * FROM `us_guest_files` WHERE `id` = '".intval($_GET['file'])."'"));
$file['ras'] = strtolower($file['ras']);
$file['src'] = H."user/us_guest/files/$file[id].dat";
$file['size'] = filesize($file['src']);
if ($file['ras'] == 'jar' && strtolower(preg_replace('#^.*\.#', NULL, $_GET['name'])) == 'jad') {
	$zip = new ZipArchive;
	$zip -> open($file['src']);
	$content = $zip -> getFromName("META-INF/MANIFEST.MF");
	if(!$content)$content = $zip -> getFromName("META-INF/manifest.mf");
	$jad = preg_replace("#(MIDlet-Jar-URL:( )*[^(\n|\r)]*)#i", NULL, $content);
	$jad = preg_replace("#(MIDlet-Jar-Size:( )*[^(\n|\r)]*)(\n|\r)#i", NULL, $jad);
	$jad = trim($jad);
	$jad.="\r\nMIDlet-Jar-Size: ".$file['size'];
	$jad.="\r\nMIDlet-Jar-URL: http://$_SERVER[HTTP_HOST]/user/us_guest/download/$file[id]/$file[name].$file[ras]";
	$jad = br($jad,"\r\n");
	header('Content-Type: text/vnd.sun.j2me.app-descriptor');
	header('Content-Disposition: attachment; filename="'.$file['name'].'.jad";');
	echo $jad;
	exit();
}
include_once '../../sys/inc/downloadfile.php';
DownloadFile($file['src'], $file['name'].'.'.$file['ras'], ras_to_mime($file['ras']));
exit();
?>
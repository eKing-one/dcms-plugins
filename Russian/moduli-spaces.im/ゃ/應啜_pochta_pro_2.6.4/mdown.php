<?
include_once 'sys/inc/start.php';
//include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
include_once 'sys/inc/downloadfile.php';
if (!$_GET['id'])die('Нет ID.');
$id = intval($_GET['id']);
$f = fopen('mfiles/'.$id.'.name', 'r');
$name = htmlspecialchars(fread($f, filesize('mfiles/'.$id.'.name')));
$filename = 'mfiles/'.$id.'.dat';
if (!file_exists($filename))
die('Файл не найден');
fclose($f);
$gg = fopen('mfiles/'.$id.'.type', 'r');
$mimetype = fread($gg, filesize('mfiles/'.$id.'.type'));
fclose($gg);



function df($fn, $name, $type)
{
if (!file_exists($fn))
die('Файл не существует.');
@ob_end_clean();
$ff=0;
$size=filesize($fn);
header('HTTP/1.1 200 OK');
$ETag=md5($fn);
$ETag=substr($ETag, 0, 8) . '-' . substr($ETag, 8, 7) . '-' . substr($ETag, 15, 8);
header('ETag: "'.$etag.'"');
header('Accept-Ranges: bytes');
header('Content-Length: '.$size);
header('Content-Type: '.$type.'; charset=utf-8');
header('Connection: close');
$f=fopen($fn, 'a+');
if (preg_match('#^image/#i',$type))
header('Content-Disposition: filename="'.$name.'";');
else
header('Content-Disposition: attachment; filename="'.$name.'";');
echo fread($f, $size);
fclose($f);
}

if (!$_GET['id']){
die();
}

df($filename, $name, $mimetype);
?>
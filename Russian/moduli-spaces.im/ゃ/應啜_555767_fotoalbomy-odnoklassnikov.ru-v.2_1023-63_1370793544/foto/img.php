<?
include_once '../sys/inc/start.php';
//include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/downloadfile.php';
//include_once '../sys/inc/user.php';
//header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($time))." GMT");
//header("Expires: ".gmdate("D, d M Y H:i:s", time() + 3600)." GMT");

if (!isset($_GET['id']) || !isset($_GET['size']))exit;
$size=intval($_GET['size']);
$if_foto=intval($_GET['id']);

if ($size=='48')
{
if (is_file(H.'sys/gallery/48/'.$if_foto.'.png'))
{

DownloadFile(H.'sys/gallery/48/'.$if_foto.'.png', 'Фото.png', ras_to_mime('png'));
exit;
}
if (is_file(H.'sys/gallery/48/'.$if_foto.'.gif'))
{
DownloadFile(H.'sys/gallery/48/'.$if_foto.'.gif', 'Фото.gif', ras_to_mime('gif'));
exit;
}
if (is_file(H.'sys/gallery/48/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/48/'.$if_foto.'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}

if ($size=='128')
{
if (is_file(H.'sys/gallery/128/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/128/'.$if_foto.'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}


if ($size=='640')
{
if (is_file(H.'sys/gallery/640/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/640/'.$if_foto.'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}

if ($size=='50')
{
if (is_file(H.'sys/gallery/50/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/50/'.$if_foto.'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}
if ($size=='777')
{
if (is_file(H.'sys/gallery/tmp/'.$if_foto.'.'.$_SESSION['id'].'.jpg'))
{
DownloadFile(H.'sys/gallery/tmp/'.$if_foto.'.'.$_SESSION['id'].'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}
if ($size=='150')
{
if (is_file(H.'sys/gallery/avatar/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/avatar/'.$if_foto.'.jpg', 'Фото.jpg', ras_to_mime('jpg'));
exit;
}
}

if ($size=='0')
{
if (is_file(H.'sys/gallery/foto/'.$if_foto.'.jpg'))
{
DownloadFile(H.'sys/gallery/foto/'.$if_foto.'.jpg', 'foto_'.$if_foto.'.jpg', ras_to_mime('jpg'));
exit;
}
}
?>
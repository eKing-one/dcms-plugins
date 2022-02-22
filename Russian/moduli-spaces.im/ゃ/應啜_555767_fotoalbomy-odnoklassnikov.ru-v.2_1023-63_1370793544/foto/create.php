<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$foto['id']=intval($_GET['foto']);
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `gallery_foto` WHERE `id` = '$foto[id]' LIMIT 1"),0)==0)
{header("Location: /sys/gallery/tmp/$user[id]/?".SID);exit;}
$foto=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery_foto` WHERE `id` = '$foto[id]'  LIMIT 1"));
$gallery=mysql_fetch_assoc(mysql_query("SELECT * FROM `gallery` WHERE `id` = '$foto[id_gallery]' LIMIT 1"));
if ($gallery['id_user']!=$user['id']){header("Location: /sys/gallery/tmp/$user[id]/?".SID);exit;}

$set['title']='Редактор превью';
include_once '../sys/inc/thead.php';

if (!isset($_SESSION['y_o']))
$_SESSION['y_o'] = 0;
if (!isset($_SESSION['x_o']))
$_SESSION['x_o'] = 0;
if (!isset($_SESSION['w_o']))
$_SESSION['w_o'] = 0;
if (!isset($_SESSION['id']))
$_SESSION['id'] = 0;
$id = $_SESSION['id'];


if (isset($_GET['get']))
{
function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
	list($w_i, $h_i, $type) = getimagesize($file_input);
	if (!$w_i || !$h_i) {
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($file_input);    } else {    	echo 'Некорректный формат файла';
		return;    }
	if ($percent) {		$w_o *= $w_i / 100;
		$h_o *= $h_i / 100;
	}	if (!$h_o) $h_o = $w_o/($w_i/$h_i);
	if (!$w_o) $w_o = $h_o/($h_i/$w_i);	$img_o = imagecreatetruecolor($w_o, $h_o);
	
/*-------------------определение параметров---------------*/
if (isset($_GET['zoom']) && $_GET['zoom']=='increase') // Увеличить
$_SESSION['w_o'] = $_SESSION['w_o']+20;
else
if (isset($_GET['zoom']) && $_GET['zoom']=='reduce') // Уменьшить
$_SESSION['w_o'] = $_SESSION['w_o']-20;
else
$_SESSION['w_o'] = $_SESSION['w_o'];
$zoom = $_SESSION['w_o'];
/*--------------------alex-borisi--------------------------*/
	
	imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o+$zoom, $h_o+$zoom, $w_i+$zoom, $h_i+$zoom);
	if ($type == 2) {
		return imagejpeg($img_o,$file_output,100);
	} else {		$func = 'image'.$ext;
		return $func($img_o,$file_output);	}
}

function crop($file_input, $file_output, $crop = 'square',$percent = false) {
	list($w_i, $h_i, $type) = getimagesize($file_input);
	if (!$w_i || !$h_i) {
		echo 'Невозможно получить длину и ширину изображения';
		return;
    }
    $types = array('','gif','jpeg','png');
    $ext = $types[$type];
    if ($ext) {
    	$func = 'imagecreatefrom'.$ext;
    	$img = $func($file_input);
    } else {
    	echo 'Некорректный формат файла';
		return;
    }
	if ($crop == 'square') {
		$min = $w_i;
		if ($w_i > $h_i) $min = $h_i;
		$w_o = $h_o = $min;
		
		
	} else {
		list($x_o, $y_o, $w_o, $h_o) = $crop;		if ($percent) {
			$w_o *= $w_i / 100;
			$h_o *= $h_i / 100;
			$x_o *= $w_i / 100;
			$y_o *= $h_i / 100;
		}
    	if ($w_o < 0) $w_o += $w_i;
	    $w_o -= $x_o;
	   	if ($h_o < 0) $h_o += $h_i;
		$h_o -= $y_o;
	}
	

/*-------------------определение соотношения---------------*/
if (isset($_GET['n_o'])) // Вниз
$_SESSION['y_o'] = $_SESSION['y_o']+25;
else
if (isset($_GET['v_o'])) // Вверх
$_SESSION['y_o'] = $_SESSION['y_o']-25;
else
$_SESSION['y_o'] = $_SESSION['y_o'];
$y = $_SESSION['y_o'];

if (isset($_GET['p_o'])) // Вправо
$_SESSION['x_o'] = $_SESSION['x_o']+25;
else
if (isset($_GET['l_o'])) // Влево
$_SESSION['x_o'] = $_SESSION['x_o']-25;
else
$_SESSION['x_o'] = $_SESSION['x_o'];
$x = $_SESSION['x_o'];
/*--------------------alex-borisi--------------------------*/

	
	$img_o = imagecreatetruecolor($w_o, $h_o);
	imagecopy($img_o, $img, 0, 0, $x_o+$x, $y_o+$y, $w_o, $h_o);
	
	if ($type == 2) {
		return imagejpeg($img_o,$file_output,100);
	} else {
		$func = 'image'.$ext;
		return $func($img_o,$file_output);
	}
}
@unlink(H."sys/gallery/tmp/$foto[id].$id.jpg");
$id = mt_rand(1000000,9999999);
$_SESSION['id'] = $id;

if (isset($_GET['ok']))
{
@unlink(H."sys/gallery/avatar/$foto[id].jpg");
@unlink(H."sys/gallery/50/$foto[id].jpg");

crop(H."sys/gallery/640/$foto[id].jpg", H."sys/gallery/avatar/$foto[id].tmp.jpg");
resize(H."sys/gallery/avatar/$foto[id].tmp.jpg", H."sys/gallery/avatar/$foto[id].jpg", 150, 150);

@chmod(H."sys/gallery/avatar/$foto[id].jpg",0777);
@unlink(H."sys/gallery/avatar/$foto[id].tmp.jpg");

$_SESSION['y_o'] = 0;
$_SESSION['x_o'] = 0;
$_SESSION['w_o'] = 0;
$_SESSION['id'] = 0;
$_SESSION['message'] = 'Изображение успешно сохранено';
header("Location: /foto/$user[id]/$gallery[id]/$foto[id]/?save".SID);
exit;
}elseif (isset($_GET['del'])){
$_SESSION['y_o'] = 0;
$_SESSION['x_o'] = 0;
$_SESSION['w_o'] = 0;
$_SESSION['id'] = 0;

header("Location: /foto/$user[id]/$gallery[id]/$foto[id]/".SID);
}else{
crop(H."sys/gallery/640/$foto[id].jpg", H."sys/gallery/tmp/$foto[id].$id.jpg");
resize(H."sys/gallery/tmp/$foto[id].$id.jpg", H."sys/gallery/tmp/$foto[id].$id.jpg", 150, 150);
@chmod(H."sys/gallery/tmp/$foto[id].$id.jpg",0777);
}

header("Location: ?foto=$foto[id]".SID);exit;
}

title();

err();

aut(); // форма авторизации
echo "<center>";
echo "<table>";
echo "<tr>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&zoom=increase'><img src='/style/icons/zoomp.png' alt='*'> Увеличить</a>";
echo "</td>";

echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&zoom=reduce'><img src='/style/icons/zoomm.png' alt='*'> Уменьшить</a>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&v_o'><img src='/style/icons/tops.png' alt='*'></a>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&l_o'><img src='/style/icons/ls.png' alt='*'></a>";
echo "</td>";
echo "<td class='icon14'>";
echo "<img style='max-width:150px;' src='/foto/foto777/$foto[id].$id.jpg' alt='Превью'>";
echo "</td>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&p_o'><img src='/style/icons/rs.png' alt='*'></a>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&n_o'><img src='/style/icons/bots.png' alt='*'></a><br />";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&ok'><img src='/style/icons/ok.gif' alt='*'> Сохранить</a>";
echo "</td>";

echo "<td class='foot'>";
echo "<a href='?foto=$foto[id]&get&del'><img src='/style/icons/delete.gif' alt='*'> Отменить</a>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</center>";

include_once '../sys/inc/tfoot.php';
?>
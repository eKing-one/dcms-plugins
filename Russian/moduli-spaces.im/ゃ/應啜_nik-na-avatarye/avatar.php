<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';

only_reg();
$set['title']='Загрузка аватара';
include_once 'sys/inc/thead.php';
title();
$nad=$user['nick'];

$font_path = "impact.ttf";	// шрифт
$font_size = 13; 			// размер шрифта в px
$water_mark_text = $nad; // текст

function watermark_text($oldimage_name, $new_image_name){
    // получение значений шрифта, размера и текста, используемых для наложение
    global $font_path, $font_size, $water_mark_text;
    // получаем размеры исходного изображения
    list($owidth,$oheight) = getimagesize($oldimage_name);
    // задаем размеры для выходного изображения
    $width = 100;
$height = 100;
    // создаем выходное изображение размерами, указанными выше
    $image = imagecreatetruecolor($width, $height);

    // получаем глобальную переменную, формат изображения
    global $ext;
    switch($ext[1]){
          // если jpeg
          case 'jpeg':
               $img_src = imagecreatefromjpeg($oldimage_name);
          break;
          // если png
          case 'png':
               $img_src = imagecreatefrompng($oldimage_name);
          break;
          // ну и бонус, если нужен gif
          case 'gif':
               $img_src = imagecreatefromgif($oldimage_name);
          break;
          // ну и если формат иной выходим из функции, возвращая false
          default: return false;
    }

    // наложение на выходное изображение, исходного
    imagecopyresampled($image, $img_src, 1, 1, 1, 1, $width, $height, $owidth, $oheight);
    // задаем цвет для накладываемого текста
    $blue = imagecolorallocate($image, 154, 205, 50);
    // определяем позицию расположения водяного знака
    $pos_x = $width - (strlen($water_mark_text)-1)*$font_size;
    $pos_y = $height;
    // наложение текста на выходное изображение
    imagettftext($image, $font_size, 5, $pos_x, $pos_y, $blue, $font_path, $water_mark_text);
    // сохраняем выходное изображение, уже с водяным знаком в формате jpg и качеством 100
    imagejpeg($image, $new_image_name, 100);
    // уничтожаем изображения
    imagedestroy($image);
    unlink($oldimage_name);
    return true;
}


$demo_image= "";
// если нажата кнопка загрузки
if(isset($_POST['createmark']))
{
	// директория для хранения загружаемых изображений
 $path = "sys/avatar/";
	// массив разрешенных расширений
 $valid_formats = array("jpg", "png","gif","jpeg");
	// получаем имя загружаемого изображения
	$name = $_FILES['imgfile']['name'];
	// имя есть, то:
	if(strlen($name))
	{
		// получаем тип загружаемого файла
		$ext = explode("/", $_FILES['imgfile']['type']);
		// если расширение принадлежит массиву разрешенных расширений и размер <= 2Мб, то загружаем изображение
		if(in_array($ext[1],$valid_formats)&& $_FILES['imgfile']['size'] <= 2*1024*1024)
		{
			// загружаем изображение
			$upload_status = move_uploaded_file($_FILES['imgfile']['tmp_name'], $path.$_FILES['imgfile']['name']);
			// при успешной загрузке
			if($upload_status)
			{
				// задаем директорию и имя для сохранения нового изображения
				$new_name = $path .$user['id'].".jpg";
				// добавляем водяной знак: watermark_text() - для текста, watermark_image() - для изображения
				if(watermark_text($path.$_FILES['imgfile']['name'], $new_name))
					$demo_image = $new_name;
			}
			else
				$msg="Загрузка не удалась!";
		}
		else
			$msg="Превышен размер разружаемого файла (Max 2Мб). Либо изображение имеет не верный формат";
	}
}
	if(!empty($demo_image)){
                  echo'<h3>Успешно</h3>';

                    }
				else{
					echo '<h3>'.$msg.'</h3>';
                    }

echo "<b>Текущий:<br /></b>\n";
avatar($user['id']);
echo "<br></br>";
echo "Качественное преобразование GIF-анимации не гарантируется<br />\n";
echo "Можно загружать картинки форматов: GIF, JPG, PNG<br />\n";
?>
<html>
	<head>
		<title></title>
	</head>
	<body>



		<form name="imageUpload" id="imageUpload" method="post" enctype="multipart/form-data" >
			<fieldset>
				<legend>Загрузка изображения</legend>
				Изображение :<input type="file" name="imgfile" id="imgfile"/><br />
				<input type="submit" name="createmark" id="createmark" value="Загрузка" />
			</fieldset>



		</form>

	</body>
</html>
<?
include_once 'sys/inc/tfoot.php';
?>
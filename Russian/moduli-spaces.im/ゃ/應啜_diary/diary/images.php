<?

//Автор: DjBoBaH
//Сайт: http://my-perm.net
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$limit = 6;
if (isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id`='" .
    intval($_GET['id']) . "' LIMIT 1"), 0) != 0)
{
    $diary = mysql_fetch_assoc(mysql_query("SELECT * FROM `diary` WHERE `id`='" .
        intval($_GET['id']) . "' LIMIT 1"));
    $set['title'] = '' . $diary['name'] . ' - Изображения'; // заголовок страницы
    include_once '../sys/inc/thead.php';
    title();
    aut();
    $us = get_user($diary['id_user']);
    if (isset($user) && ($user['id'] == $us['id'] || $user['level'] > 2 && $user['level'] >
        $us['level']))
    {
        if (isset($_GET['img']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_images` WHERE `id`='" .
            intval($_GET['img']) . "' AND `id_diary`='$diary[id]' LIMIT 1"), 0) != 0)
        {
            $img = intval($_GET['img']);
            if (isset($_GET['pos']) && ($_GET['pos'] == 'up' || $_GET['pos'] == 'down'))
            {
                $pos = $_GET['pos'];
                mysql_query("UPDATE `diary_images` SET `position`='$pos' WHERE `id`='$img' LIMIT 1");
                msg('Позиция успешно изменена');
            } elseif (isset($_GET['del']))
            {
                $ras = mysql_fetch_array(mysql_query("SELECT `ras` FROM `diary_images` WHERE `id`='$img' LIMIT 1"));
                @unlink(H . "diary/images/48/$img.$ras");
                @unlink(H . "diary/images/128/$img.$ras");
                @unlink(H . "diary/images/640/$img.$ras");
                @unlink(H . "diary/images/$img.$ras");
                mysql_query("DELETE FROM `diary_images` WHERE `id`='" . intval($_GET['img']) .
                    "' LIMIT 1");
                msg('Изображение успешно удалено');
            }
        }
        if (isset($_FILES['file']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_images` WHERE `id_diary`='$diary[id]' LIMIT 1"),
            0) < $limit)
        {
            if ($imgc = @imagecreatefromstring(file_get_contents($_FILES['file']['tmp_name'])))
            {
                $file = esc(stripcslashes(htmlspecialchars($_FILES['file']['name'])));
                $ras = strtolower(preg_replace('/^.*\./', null, $file));
                $img_x = imagesx($imgc);
                $img_y = imagesy($imgc);

                if (isset($_POST['position']) && ($_POST['position'] == 'up' || $_POST['position'] ==
                    'down'))
                    $position = $_POST['position'];
                else
                    $err[] = 'Ошибка позиции';
                if ($img_x > $set['max_upload_foto_x'] || $img_y > $set['max_upload_foto_y'])
                    $err[] = 'Размер изображения превышает ограничения в ' . $set['max_upload_foto_x'] .
                        '*' . $set['max_upload_foto_y'];

                if (!isset($err))
                {
                    mysql_query("INSERT INTO `diary_images` (`id_diary`, `position`, `ras`) values ('$diary[id]', '$position', '$ras')");
                    $id_image = mysql_insert_id();

                    if ($img_x == $img_y)
                    {
                        $dstW = 48; // ширина
                        $dstH = 48; // высота
                    } elseif ($img_x > $img_y)
                    {
                        $prop = $img_x / $img_y;
                        $dstW = 48;
                        $dstH = ceil($dstW / $prop);
                    } else
                    {
                        $prop = $img_y / $img_x;
                        $dstH = 48;
                        $dstW = ceil($dstH / $prop);
                    }

                    $screen = imagecreatetruecolor($dstW, $dstH);
                    imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
                    //imagedestroy($imgc);
                    imagejpeg($screen, H . "diary/images/48/$id_image.$ras", 90);
                    @chmod(H . "diary/images/48/$id_image.$ras", 0777);
                    imagedestroy($screen);

                    if ($img_x == $img_y)
                    {
                        $dstW = 128; // ширина
                        $dstH = 128; // высота
                    } elseif ($img_x > $img_y)
                    {
                        $prop = $img_x / $img_y;
                        $dstW = 128;
                        $dstH = ceil($dstW / $prop);
                    } else
                    {
                        $prop = $img_y / $img_x;
                        $dstH = 128;
                        $dstW = ceil($dstH / $prop);
                    }

                    $screen = imagecreatetruecolor($dstW, $dstH);
                    imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
                    //imagedestroy($imgc);
                    $screen = img_copyright($screen); // наложение копирайта
                    imagejpeg($screen, H . "diary/images/128/$id_image.$ras", 90);
                    @chmod(H . "diary/images/128/$id_image.$ras", 0777);
                    imagedestroy($screen);

                    if ($img_x > 640 || $img_y > 640)
                    {
                        if ($img_x == $img_y)
                        {
                            $dstW = 640; // ширина
                            $dstH = 640; // высота
                        } elseif ($img_x > $img_y)
                        {
                            $prop = $img_x / $img_y;
                            $dstW = 640;
                            $dstH = ceil($dstW / $prop);
                        } else
                        {
                            $prop = $img_y / $img_x;
                            $dstH = 640;
                            $dstW = ceil($dstH / $prop);
                        }

                        $screen = imagecreatetruecolor($dstW, $dstH);
                        imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
                        //imagedestroy($imgc);
                        $screen = img_copyright($screen); // наложение копирайта
                        imagejpeg($screen, H . "diary/images/640/$id_image.$ras", 90);
                        imagedestroy($screen);
                        $imgc = img_copyright($imgc); // наложение копирайта
                        imagejpeg($imgc, H . "diary/images/$id_image.$ras", 90);
                        @chmod(H . "diary/images/$id_image.$ras", 0777);
                    } else
                    {
                        $imgc = img_copyright($imgc); // наложение копирайта

                        imagejpeg($imgc, H . "diary/images/640/$id_image.$ras", 90);
                        imagejpeg($imgc, H . "diary/images/$id_image.$ras", 90);
                        @chmod(H . "diary/images/$id_image.$ras", 0777);
                    }

                    @chmod(H . "diary/images/640/$id_image.$ras", 0777);


                    imagedestroy($imgc);
                    msg("Изображение успешно добавлено");
                }
            } else
                $err = 'Выбранный Вами формат изображения не поддерживается';
        }
        err();
        echo 'Лимит изображений: ' . $limit . '<br/>';
        echo '<table class="post">';
        $q = mysql_query("SELECT * FROM `diary_images` WHERE `id_diary`='$diary[id]' ORDER BY `id` ASC");
        while ($image = mysql_fetch_assoc($q))
        {
            echo '<tr>';
            echo '<td class="icon48" rowspan="2">';
            echo '<img src="/diary/images/48/' . $image['id'] . '.' . $image['ras'] .
                '" alt=""/>';
            echo '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td class="p_m">';
            echo 'Позиция:<br/>';
            if ($image['position'] == 'up')
                echo '<b>Вверху</b>';
            else
                echo '<a href="?id=' . $diary['id'] . '&img=' . $image['id'] .
                    '&pos=up">Вверху</a>';
            echo ' | ';
            if ($image['position'] == 'down')
                echo '<b>Внизу</b>';
            else
                echo '<a href="?id=' . $diary['id'] . '&img=' . $image['id'] .
                    '&pos=down">Внизу</a>';
            echo '<br/>';
            echo '<a href="?id=' . $diary['id'] . '&img=' . $image['id'] .
                '&del">Удалить</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
        if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_images` WHERE `id_diary`='$diary[id]' LIMIT 1"),
            0) < $limit)
        {
            echo '<form class="foot" enctype="multipart/form-data" action="?id=' . $diary['id'] .
                '" method="post">';
            echo 'Изображение:<br/>';
            echo '<input name="file" type="file" accept="image/*,image/jpeg" /><br />';
            echo 'Позиция<br/>';
            echo '<select name="position">';
            echo '<option value="up">Сверху</option>';
            echo '<option value="down">Снизу</option>';
            echo '</select><br/>';
            echo '<input class="submit" type="submit" value="Добавить" /><br />';
            echo '</form>';
        }
        echo '<a href="/diary/' . $diary['name'] .
            '/" title="Вернуться в дневник">Назад</a><br/>';
    } else
    {
        header("Location:/diary/$diary[name]/");
    }
} else
{
    header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';

?>
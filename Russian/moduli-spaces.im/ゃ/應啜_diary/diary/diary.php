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
err();
if (isset($_GET['d']))
{
    $name = esc(urldecode($_GET['d']));
    if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `name`='$name' LIMIT 1"),
        0) != 0)
    {
        $name = mysql_real_escape_string($name);
        $diary = mysql_fetch_assoc(mysql_query("SELECT * FROM `diary` WHERE `name`='" .
            mysql_real_escape_string($name) . "' LIMIT 1"));
        $us = get_user($diary['id_user']);
        $set['title'] = '' . $diary['name'] . ' - Дневник ' . $us['nick'] . ''; // заголовок страницы
        if ($diary['tags'] != null)
        {
            $set['meta_keywords'] = '' . $diary['tags'] . '';
        }
        $set['meta_description'] = '' . cut_text($diary['msg']) . '';
        include_once '../sys/inc/thead.php';
        title();
        aut();
        if (isset($_POST['save']) && isset($user))
        {
            if (isset($_POST['msg']) && ($user['id'] == $us['id'] || $user['level'] >
                2 && $user['level'] > $us['level']))
            {
                $msg = esc(stripcslashes(htmlspecialchars($_POST['msg'])));
                if (strlen2($msg) < 10)
                    $err[] = 'Короткое сообщение';
                if (strlen2($msg) > 10000)
                    $err[] = 'Сообщение не должно быть длиннее 10000 символов';
                $mat = antimat($msg);
                if ($mat)
                    $err[] = 'В сообщении обнаружен мат: ' . $mat;
                $msg = my_esc($msg);
                if (!isset($err))
                {
                    $diary['msg'] = esc(stripcslashes(htmlspecialchars($_POST['msg'])));
                    mysql_query("UPDATE `diary` SET `msg`='$msg' WHERE `id`='" .
                        mysql_real_escape_string($diary['id']) . "' LIMIT 1");
                    msg('Сообщение успешно изменено');
                }
            } elseif (isset($_POST['tags']) && ($user['id'] == $us['id'] || $user['level'] >
            2 && $user['level'] > $us['level']))
            {
                $tags = esc(stripcslashes(htmlspecialchars($_POST['tags'])));
                if (strlen2($tags) < 2)
                    $err[] = 'Короткие теги';
                if (strlen2($tags) > 256)
                    $err[] = 'Теги не должны быть длиннее 256-и символов';
                $mat = antimat($tags);
                if ($mat)
                    $err[] = 'В тегах обнаружен мат: ' . $mat;
                $tags = my_esc($tags);
                if (!isset($err))
                {
                    $diary['tags'] = $tags;
                    mysql_query("UPDATE `diary` SET `tags`='$diary[tags]' WHERE `id`='" .
                        mysql_real_escape_string($diary['id']) . "' LIMIT 1");
                    msg('Метки успешно изменены');
                }
            } elseif (isset($_POST['cat']) && $user['level'] > 2 && ($user['id'] == $us['id'] ||
            $user['level'] > $us['level']))
            {
                if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_cat` WHERE `id`='" .
                    intval($_POST['cat']) . "' LIMIT 1"), 0) != 0)
                {
                    $diary['id_cat'] = intval($_POST['cat']);
                    mysql_query("UPDATE `diary` SET `id_cat`='" . intval($_POST['cat']) .
                        "' WHERE `id`='$diary[id]' LIMIT 1");
                    msg('Категория успешно изменена');
                }
                else
                    $err[] = 'Ошибка категории';
            } elseif (isset($_POST['name']) && $user['level'] > 2 && ($user['id'] == $us['id'] ||
            $user['level'] > $us['level']))
            {
                $name = preg_replace('#[^A-zА-я0-9\(\)\-\_\\ ]#ui', null, $_POST['name']);
                $name = htmlspecialchars(stripslashes($name));
                if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $name))
                {
                    echo
                        "<font color='red'>В названии присутствуют запрещенные символы</font><br />";
                    echo '<a href=/diary/index.php>Дневники</a>';
                    include_once '../sys/inc/tfoot.php';
                    exit;
                }
                if (strlen2($name) < 3)
                    $err[] = 'Короткое название';
                if (strlen2($name) > 64)
                    $err[] = 'Название не должно быть длиннее 64-х символов';
                $mat = antimat($name);
                if ($mat)
                    $err[] = 'В названии обнаружен мат: ' . $mat;
                $name = mysql_real_escape_string($name);
                if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `name`='$name' LIMIT 1"),
                    0) != 0)
                    $err[] = 'Дневник с таким названием уже существует';
                if (!isset($err))
                {
                    $diary['name'] = $name;
                    mysql_query("UPDATE `diary` SET `name`='$diary[name]' WHERE `id`='$diary[id]' LIMIT 1");
                    msg('Название дневника успешно изменено');
                }
            } elseif (isset($_POST['readers']) && ($_POST['readers'] == 0 || $_POST['readers'] ==
            1 || $_POST['readers'] == 2) && ($user['id'] == $us['id'] || $user['level'] >
                2 && $user['level'] > $us['level']))
            {
                $readers = intval($_POST['readers']);
                $diary['readers'] = $readers;
                mysql_query("UPDATE `diary` SET `readers`='$diary[readers]' WHERE `id`='$diary[id]' LIMIT 1");
                msg('Приватность успешно изменена');
            }
        }
        if (isset($user) && $us['id'] != $user['id'] && ($user['level'] > $us['level'] ||
            ($diary['readers'] == 0 || $diary['readers'] == 1) || $diary['readers'] ==
            2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$us[id]') OR (`user` = '$us[id]' AND `frend` = '$user[id]')"),
            0) != 0))
        {
            if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_rating` WHERE `id_diary` = '$diary[id]' AND `id_user`='$user[id]' LIMIT 1"),
                0) == 0 && isset($_GET['plus']) && ($_GET['plus'] == 1 || $_GET['plus'] ==
                2 || $_GET['plus'] == 3 || $_GET['plus'] == 4 || $_GET['plus'] ==
                5))
            {
                $plus = intval($_GET['plus']);
                mysql_query("INSERT INTO `diary_rating` (`id_diary`, `id_user`, `rating`) values ('$diary[id]', '$user[id]', '$plus')");
                $diary['rating'] = intval(mysql_result(mysql_query("SELECT SUM(`rating`) FROM `diary_rating` WHERE `id_diary` = '$diary[id]'"),
                    0));
                mysql_query("UPDATE `diary` SET `rating`='$diary[rating]' WHERE `id`='$diary[id]' LIMIT 1");
                mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$us[id]', '[url=/info.php?id=$user[id]]$user[nick][/url] оставил отзыв +$plus к дневнику [url=/diary/$diary[name]/]$diary[name][/url]', '$time')");
                msg('Оценка успешно принята');
            }
        }
        $cat = mysql_fetch_assoc(mysql_query("SELECT * FROM `diary_cat` WHERE `id`='$diary[id_cat]' LIMIT 1"));
        err();
        echo '<table class="post">';
        echo '<tr>';
        echo '<td class="icon14">';
        echo '<img src="/diary/img/diary.png" alt=""/>';
        echo '</td>';
        echo '<td class="p_t">';
        if (isset($_GET['edit']) && $_GET['edit'] == 'name' && isset($user) && $user['level'] >
            2 && ($user['id'] == $us['id'] || $user['level'] > $us['level']))
        {
            echo '<form method="post" name="message" action="/diary/' . $diary['name'] .
                '/">';
            echo '<input name="name" type="text" maxlength="64" value="' . $diary['name'] .
                '">';
            echo '<input type="submit" name="save" value="Изменить">';
            echo '</form><br/>';
            echo '<a href="/diary/' . $diary['name'] . '/">Назад</a><br/>';
        }
        else
        {
            echo '<b>' . $diary['name'] . '</b>';
            if (isset($user) && $user['level'] > 2 && ($user['id'] == $us['id'] ||
                $user['level'] > $us['level']))
                echo ' [<a href="?edit=name" title="Изменить название">изм</a>]';
        }
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td class="main_menu" colspan="2">';
        echo '<img src="/diary/img/calendar.png" alt=""/> Опубликовано: <b>' .
            vremja($diary['time']) . '</b><br/>';
        if ($us['pol'] == 1)
            echo '<img src="/diary/img/boy.png" alt=""/> ';
        else
            echo '<img src="/diary/img/girl.png" alt=""/> ';
        echo '<a href="/diary/user.php?id=' . $us['id'] .
            '" title="Все дневники пользователя ' . $us['nick'] .
            '">Автор</a>: <a href="/info.php?id=' . $us['id'] .
            '" title="Анкета ' . $us['nick'] . '"><span style="color:' . $us['ncolor'] .
            '">' . $us['nick'] . '</span></a><br/>';
        if (isset($_GET['edit']) && $_GET['edit'] == 'cat' && isset($user) && $user['level'] >
            2 && ($user['id'] == $us['id'] || $user['level'] > $us['level']))
        {
            echo '<form method="post" action="/diary/' . $diary['name'] . '/">';
            echo '<select name="cat">';
            $c = mysql_query("SELECT * FROM `diary_cat` ORDER BY `name` ASC");
            while ($cats = mysql_fetch_assoc($c))
            {
                echo '<option value="' . $cats['id'] . '"' . ($diary['id_cat'] ==
                    $cats['id'] ? ' selected="selected"' : null) . '>' . $cats['name'] .
                    '</option>';
            }
            echo '</select><br/>';
            echo '<input type="submit" name="save" value="Изменить">';
            echo '</form><br/>';
            echo '<a href="/diary/' . $diary['name'] . '/">Назад</a><br/>';
        }
        else
        {
            echo '<img src="/diary/img/cat.png" alt=""/> Категория: <a href="/diary/index.php?r=' .
                $cat['id'] . '">' . $cat['name'] . '</a>';
            if (isset($user) && $user['level'] > 2 && ($user['id'] == $us['id'] ||
                $user['level'] > $us['level']))
                echo ' [<a href="?edit=cat" title="Изменить категорию">изм</a>]';
        }
        echo '<br/>';
        if (isset($_GET['edit']) && $_GET['edit'] == 'tags' && isset($user) && ($user['id'] ==
            $us['id'] || $user['level'] > 2 && $user['level'] > $us['level']))
        {
            echo '<form method="post" name="message" action="/diary/' . $diary['name'] .
                '/">';
            echo '<input name="tags" type="text" maxlength="128" value="' . $diary['tags'] .
                '"><br/>';
            echo '<input type="submit" name="save" value="Изменить">';
            echo '</form><br/>';
            echo '<a href="/diary/' . $diary['name'] . '/">Назад</a><br/>';
        }
        else
        {
            echo '<img src="/diary/img/tags.png" alt=""/> Метки: ';
            if ($diary['tags'] != null)
            {
                $tagss = explode(',', $diary['tags']);
                for ($i = 0; $i < count($tagss); $i++)
                {
                    echo '<a href="/diary/tags.php?tag=' . $tagss[$i] .
                        '" title="Искать метку ' . $tagss[$i] . '">' . $tagss[$i] .
                        '</a>, ';
                }
            }
            else
            {
                echo '<b>нет меток</b>';
            }
            if (isset($user) && ($user['id'] == $us['id'] || $user['level'] > 2 &&
                $user['level'] > $us['level']))
                echo ' [<a href="?edit=tags" title="Изменить метки">изм</a>]';
        }
        if (isset($user) && ($user['id'] == $us['id'] || $user['level'] > 2 && $user['level'] >
            $us['level']))
            echo '<br/><img src="/diary/img/images.png" alt=""/> <a href="/diary/images.php?id=' .
                $diary['id'] . '">Управление изображениями</a>';
        echo '</td>';
        echo '</tr>';
        if (isset($user) && $us['id'] == $user['id'] || isset($user) && $user['level'] >
            $us['level'] || ($diary['readers'] == 0 || $diary['readers'] == 1) ||
            isset($user) && $diary['readers'] == 2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$us[id]') OR (`user` = '$us[id]' AND `frend` = '$user[id]')"),
            0) != 0)
        {
            if (isset($user) && $user['id'] != $us['id'] || !isset($user))
                mysql_query("UPDATE `diary` SET `viewings`='" . ($diary['viewings'] +
                    1) . "' WHERE `id`='$diary[id]' LIMIT 1");
            if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_images` WHERE `id_diary`='$diary[id]' AND `position`='up' LIMIT 1"),
                0) > 0)
            {
                echo '<tr>';
                echo '<td class="menu" colspan="2">';
                $q = mysql_query("SELECT * FROM `diary_images` WHERE `id_diary`='$diary[id]' AND `position`='up' ORDER BY `id` ASC");
                while ($image = mysql_fetch_assoc($q))
                {
                    echo '<a href="/diary/images/' . $image['id'] . '.' . $image['ras'] .
                        '" title="Скачать оригинал">';
                    if ($set['web'])
                        echo '<img src="/diary/images/640/' . $image['id'] . '.' .
                            $image['ras'] . '" alt=""/></a> ';
                    else
                        echo '<img src="/diary/images/128/' . $image['id'] . '.' .
                            $image['ras'] . '" alt=""/></a> ';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '<tr>';
            echo '<td class="p_m" colspan="2">';
            if (isset($_GET['edit']) && $_GET['edit'] == 'msg' && isset($user) &&
                ($user['id'] == $us['id'] || $user['level'] > 2 && $user['level'] >
                $us['level']))
            {
                echo '<form method="post" name="message" action="/diary/' . $diary['name'] .
                    '/">';
                echo '<textarea name="msg">' . $diary['msg'] .
                    '</textarea><br/>';
                echo '<input type="submit" name="save" value="Изменить">';
                echo '</form><br/>';
                echo '<a href="/diary/' . $diary['name'] . '/">Назад</a><br/>';
            }
            else
            {
                echo '' . output_text($diary['msg']) . '';
                if (isset($user) && ($user['id'] == $us['id'] || $user['level'] >
                    2 && $user['level'] > $us['level']))
                    echo
                        ' [<a href="?edit=msg" title="Изменить сообщение">изм</a>]';
            }
            echo '</td>';
            echo '</tr>';
            if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_images` WHERE `id_diary`='$diary[id]' AND `position`='down' LIMIT 1"),
                0) > 0)
            {
                echo '<tr>';
                echo '<td class="menu" colspan="2">';
                $q2 = mysql_query("SELECT * FROM `diary_images` WHERE `id_diary`='$diary[id]' AND `position`='down' ORDER BY `id` ASC");
                while ($image2 = mysql_fetch_assoc($q2))
                {
                    echo '<a href="/diary/images/' . $image2['id'] . '.' . $image2['ras'] .
                        '" title="Скачать оригинал">';
                    if ($set['web'])
                        echo '<img src="/diary/images/640/' . $image2['id'] .
                            '.' . $image2['ras'] . '" alt=""/></a> ';
                    else
                        echo '<img src="/diary/images/128/' . $image2['id'] .
                            '.' . $image2['ras'] . '" alt=""/></a> ';
                }
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            if (isset($user) && $user['id'] != $us['id'] && mysql_result(mysql_query
                ("SELECT COUNT(*) FROM `diary_rating` WHERE `id_diary` = '$diary[id]' AND `id_user`='$user[id]' LIMIT 1"),
                0) == 0)
            {
                echo '<div class="str">';
                echo '<a href="/diary/' . $diary['name'] .
                    '/?plus=1" title="Повысить рейтинг дневника на 1">+1</a> ';
                echo '<a href="/diary/' . $diary['name'] .
                    '/?plus=2" title="Повысить рейтинг дневника на 2">+2</a> ';
                echo '<a href="/diary/' . $diary['name'] .
                    '/?plus=3" title="Повысить рейтинг дневника на 3">+3</a> ';
                echo '<a href="/diary/' . $diary['name'] .
                    '/?plus=4" title="Повысить рейтинг дневника на 4">+4</a> ';
                echo '<a href="/diary/' . $diary['name'] .
                    '/?plus=5" title="Повысить рейтинг дневника на 5">+5</a>';
                echo '</div>';
            }
        }
        else
        {
            echo '<tr>';
            echo '<td class="p_m" colspan="2">';
            echo '<img src="/diary/img/vnimanie.png" alt=""/> <b>Дневник пользователя могут читать только друзья</b>';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
        }
        echo '<div class="foot">';
        echo 'Просмотров: <b>' . $diary['viewings'] . '</b> |';
        echo ' Рейтинг: <b>' . $diary['rating'] . '</b><br/>';
        if (isset($_GET['edit']) && $_GET['edit'] == 'readers' && isset($user) &&
            ($user['id'] == $us['id'] || $user['level'] > 2 && $user['level'] >
            $us['level']))
        {
            echo '<form method="post" action="/diary/' . $diary['name'] . '/">';
            echo '<select name="readers">';
            echo '<option value="0"' . ($diary['readers'] == 0 ?
                ' selected="selected"' : null) .
                '>Читают и комментируют все</option>';
            echo '<option value="1"' . ($diary['readers'] == 1 ?
                ' selected="selected"' : null) .
                '>Читают все, комментируют друзья</option>';
            echo '<option value="2"' . ($diary['readers'] == 2 ?
                ' selected="selected"' : null) .
                '>Читают и комментируют друзья</option>';
            echo '</select><br/>';
            echo '<input type="submit" name="save" value="Изменить">';
            echo '</form><br/>';
            echo '<a href="/diary/' . $diary['name'] . '/">Назад</a><br/>';
        }
        else
        {
            echo 'Читают: ';
            if ($diary['readers'] == 0 || $diary['readers'] == 1)
                echo '<b>Все</b>';
            else
                echo '<b>Друзья</b>';
            echo ' | ';
            echo 'Комментируют: ';
            if ($diary['readers'] == 0)
                echo '<b>Все</b>';
            else
                echo '<b>Друзья</b>';
            if (isset($user) && ($user['id'] == $us['id'] || $user['level'] > 2 &&
                $user['level'] > $us['level']))
                echo
                    ' [<a href="?edit=readers" title="Изменить приватность">изм</a>]';
            echo '<br/>';
        }
        $count_komm = mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_komm` WHERE `id_diary` = '$diary[id]'"),
            0);
        echo '<a href="/diary/komm.php?id=' . $diary['id'] .
            '" title="Комментарии к дневнику">Комментарии</a> (' . $count_komm .
            ')<br/>';
        if (isset($user) && $user['level'] > 2 && ($user['id'] == $us['id'] || $user['level'] >
            $us['level']))
        {
            if (isset($_GET['delete']))
                echo
                    '<div class="err">Вы уверены, что хотите удалить дневник?<br/><a href="/diary/?r=' .
                    $diary['id_cat'] . '&del=' . $diary['id'] .
                    '" title="Да, удалить дневник безвозвратно">Да</a> | <a href="/diary/' .
                    $diary['name'] .
                    '/" title="Нет, отменить удаление">Нет</a></div>';
            else
                echo
                    '<a href="?delete" title="Удалить дневник"><span style="color:red">Удалить дневник</span></a><br/>';
        }
        echo '</div>';
        echo '<img src="/diary/img/back.png" alt=""/> <a href="/diary/" title="Вернуться к категориям">Дневники</a><br/>';
    }
    else
    {
        header("Location:index.php");
    }
}
else
{
    header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';

?>
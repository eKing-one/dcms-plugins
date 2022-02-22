<?

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
if (isset($_POST['add']) && isset($_POST['name']) && $_POST['name'] != null &&
    isset($_POST['msg']) && $_POST['msg'] != null && isset($_POST['readers']) &&
    ($_POST['readers'] == 0 || $_POST['readers'] == 1 || $_POST['readers'] == 2))
{
    if (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `name` = '" .
        mysql_real_escape_string($_POST['name']) . "' LIMIT 1"), 0) == 0)
    {
        $name = preg_replace('#[^A-zА-я0-9\(\)\-\_\\ ]#ui', null, $_POST['name']);
        $name = htmlspecialchars(stripslashes($name));
        if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $name))
        {
            echo "<font color='red'>В названии присутствуют запрещенные символы</font><br />";
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
        $msg = esc(stripcslashes(htmlspecialchars($_POST['msg'])));
        if (strlen2($msg) < 10)
            $err[] = 'Короткое сообщение';
        if (strlen2($msg) > 10000)
            $err[] = 'Сообщение не должно быть длиннее 10000 символов';
        $mat = antimat($msg);
        if ($mat)
            $err[] = 'В сообщении обнаружен мат: ' . $mat;
        $msg = mysql_real_escape_string($msg);
        if (isset($_POST['tags']) && $_POST['tags'] != null)
        {
            $tags = esc(stripcslashes(htmlspecialchars($_POST['tags'])));
            if (strlen2($tags) < 2)
                $err[] = 'Короткие теги';
            if (strlen2($tags) > 128)
                $err[] = 'Теги не должны быть длиннее 128-и символов';
            $mat = antimat($tags);
            if ($mat)
                $err[] = 'В тегах обнаружен мат: ' . $mat;
            $tags = mysql_real_escape_string($tags);
        }
        else
            $tags = null;
        $readers = intval($_POST['readers']);
    }
    else
    {
        $err[] = 'Дневник с таким названием уже существует';
    }
    if (!isset($err))
    {
        mysql_query("UPDATE `user` SET `balls` = '" . abs(intval($user['balls'] +
            30)) . "' WHERE `id` = '$user[id]' LIMIT 1");
        mysql_query("INSERT INTO `diary` (`id_cat`, `name`, `msg`, `id_user`, `readers`, `tags`, `time`) values ('$r', '" .
            stripcslashes($name) . "', '$msg', '$user[id]', '$readers', '$tags', '$time')");
        $q = mysql_query("SELECT * FROM `frends` WHERE `frend` = '$user[id]' AND `lenta_blog` = '1'");
        while ($f = mysql_fetch_array($q))
        {
            $a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[user]' LIMIT 1"));
            $msg_lenta = "Запись в дневнике [url=/diary/$name/] $name [/url]";
            mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$a[id]', '$msg_lenta', '$time')");
        }
        msg('Дневник создан! Подождите 1сек.');
        header("Refresh: 1; url=/diary/$name/");
        //header("Location:/diary/$name/");
    }
}

?>
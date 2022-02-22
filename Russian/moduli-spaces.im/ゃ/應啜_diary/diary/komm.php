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

if (isset($_GET['id']) && is_numeric($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary` WHERE `id` = '" .
    intval($_GET['id']) . "' LIMIT 1"), 0) != 0)
{
    $diary = mysql_fetch_array(mysql_query("SELECT * FROM `diary` WHERE `id`='" .
        intval($_GET['id']) . "'"));
    $us = get_user($diary['id_user']);
    $set['title'] = '' . $diary['name'] . ' - Комментарии';
    include_once '../sys/inc/thead.php';
    title();
    if (isset($_POST['msg']) && isset($user) && ($us['id'] == $user['id'] || $user['level'] >
        $us['level'] || $diary['readers'] == 0 || ($diary['readers'] == 1 || $diary['readers'] ==
        2) && mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$us[id]') OR (`user` = '$us[id]' AND `frend` = '$user[id]')"),
        0) != 0))
    {
        $msg = esc(stripcslashes(htmlspecialchars($_POST['msg'])));
        if (isset($_POST['translit']) && $_POST['translit'] == 1) $msg = translit($msg);
        if (strlen2($msg) > 1024)
        {
            $err = 'Сообщение слишком длинное';
        } elseif (strlen2($msg) < 2)
        {
            $err = 'Короткое сообщение';
        } elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_komm` WHERE `id_diary` = '$diary[id]' AND `id_user` = '$user[id]' AND `msg` = '" .
        mysql_real_escape_string($msg) . "' LIMIT 1"), 0) != 0)
        {
            $err = 'Ваше сообщение повторяет предыдущее';
        }
        else
        {
            mysql_query("INSERT INTO `diary_komm` (`id_diary`, `id_user`, `time`, `msg`) values('$diary[id]', '$user[id]', '$time', '" .
                my_esc($msg) . "')");
            mysql_query("UPDATE `user` SET `balls` = '" . ($user['balls'] + 1) .
                "' WHERE `id` = '$user[id]' LIMIT 1");
            if ($user['id'] != $us['id'])
            {
                if ($user['pol'] == 1) $pol = 'оставил';
                else  $pol = 'оставила';
                mysql_query("INSERT INTO `jurnal` (`id_user`, `id_kont`, `msg`, `time`) values('0', '$us[id]', '[url=/info.php?id=$user[id]]$user[nick][/url] $pol комментарий к дневнику [url=/diary/$diary[name]/]$diary[name][/url]', '$time')");
            }
            $q = mysql_query("SELECT * FROM `frends` WHERE `user` = '$user[id]' AND `lenta_blog` = '1' AND `i`='1'");
            while ($f = mysql_fetch_array($q))
            {
                $a = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$f[frend]' LIMIT 1"));
                $msg_lenta = "Добавил комментарий в дневнике [url=/diary/$diary[name]/]$diary[name][/url]";
                mysql_query("INSERT INTO `lenta` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$a[id]', '$msg_lenta', '$time')");
            }
            msg('Комментарий успешно оставлен');
        }
    } elseif (isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_komm` WHERE `id` = '" .
    intval($_GET['del']) . "' AND `id_diary` = '$diary[id]'"), 0))
    {
        if (isset($user) && ($user['level'] >= 3 || $user['id'] = $diary['id_user']))
        {
            mysql_query("DELETE FROM `diary_komm` WHERE `id` = '" . intval($_GET['del']) .
                "' LIMIT 1");
            msg('Комментарий успешно удален');
        }
    }
    err();
    aut(); // форма авторизации

    $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `diary_komm` WHERE `id_diary` = '" .
        intval($_GET['id']) . "'"), 0);
    $k_page = k_page($k_post, $set['p_str']);
    $page = page($k_page);
    $start = $set['p_str'] * $page - $set['p_str'];
    $q = mysql_query("SELECT * FROM `diary_komm` WHERE `id_diary` = '" . intval($_GET['id']) .
        "' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
    echo '<table class="post">';
    if ($k_post == 0)
    {
        echo '<tr>';
        echo '<td class="p_t">';
        echo 'Нет комментариев';
        echo '</td>';
        echo '</tr>';
    }
    while ($post = mysql_fetch_assoc($q))
    {
        $ank = get_user($post['id_user']);

        echo '<tr>';
        if ($set['set_show_icon'] == 2)
        {
            echo '<td class="icon48" rowspan="2">';
            if ($set['web'])
            {
                avatar($ank['id']);
            }
            else
            {
                avatar($ank['id'], 48);
            }
            echo '</td>';
        } elseif ($set['set_show_icon'] == 1)
        {
            echo '<td class="icon14">';
            
            echo '</td>';
        }
        echo '<td class="p_t">';

        echo '<a href="/info.php?id=' . $ank['id'] . '"><span style="color:' . $ank['ncolor'] .
            '">' . $ank['nick'] . '</span></a> (' . vremja($post['time']) . ')';
echo '' . online($ank['id']) . '';
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        if ($set['set_show_icon'] == 1) echo '<td class="p_m" colspan="2">';
        else  echo '<td class="p_m">';
        echo output_text($post['msg']) . "<br />\n";
        //////////////////////////////
        if ($post['reply'] != null)
        {
            $urepl = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = '$post[who_reply]' LIMIT 1"));
            echo '<div class="p_t">';
            echo '<span style="color: red;text-decoration:underline">Ответ ';
            echo "$urepl[nick]:";
            echo '</span> ' . esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars
                ($post['reply'])))))))) . '<br />';
            echo '</div>';
        }
        //////////////////////////////
        if (isset($user) && ($user['id']))
            if (!$post['reply']) echo '<a href="reply.php?id=' . $post['id'] .
                    '">[Ответить]</a>';
        if (isset($user) && ($user['level'] >= 3 || $user['id'] == $diary['id_user'])) 
                echo '<a href="?id=' . $diary['id'] . '&del=' . $post['id'] .
                '">[Удалить]</a><br />';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';


    if ($k_page > 1) str("komm.php?id=$diary[id]&", $k_page, $page); // Вывод страниц

    if (isset($user) && ($us['id'] == $user['id'] || $user['level'] > $us['level'] ||
        $diary['readers'] == 0 || ($diary['readers'] == 1 || $diary['readers'] == 2) &&
        mysql_result(mysql_query("SELECT COUNT(*) FROM `frends` WHERE (`user` = '$user[id]' AND `frend` = '$us[id]') OR (`user` = '$us[id]' AND `frend` = '$user[id]')"),
        0) != 0))
    {
        echo '<form method="post" name="message" action="?id=' . $diary['id'] . '">';
        if ($set['web'] && is_file(H . 'style/themes/' . $set['set_them'] .
            '/altername_post_form.php')) include_once H . 'style/themes/' . $set['set_them'] .
                '/altername_post_form.php';
        else  echo 'Сообщение|<a href="/smiles/index.php">Смайлы</a>|<a href="/bb-code.php">BB-Code</a><br /><textarea name="msg"></textarea><br />';
        if ($user['set_translit'] == 1) echo
                '<label><input type="checkbox" name="translit" value="1" /> Транслит</label><br />';
        echo '<input value="Отправить" type="submit" />';
        echo '</form>';
    }

    echo '<div class="foot">';
    echo '<a href="/diary/' . $diary['name'] . '/" title="Вернуться в дневник ' . $diary['name'] .
        '">Назад</a><br />';
    echo '<a href="index.php" title="К категориям">Дневники</a><br />';
    echo "</div>\n";
}
else
{
    header("Location: index.php?" . SID);
    exit;
}
include_once '../sys/inc/tfoot.php';

?>


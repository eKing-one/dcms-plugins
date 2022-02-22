<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

user_access('adm_set_sys',null,'index.php?'.SID);



/*******
* Модуль: Управления мета данными.
* Автор: Кредитор (Королев Руслан)
* ICQ: 441460
* Email: kpegumop@yandex.ru
* Сайт: http://gix.su - безопасные сделки
*****
* Любые скрипты на заказ, 
* безопасность ресурса, оптимизация
*******/



if (isset($_REQUEST['meta_title']))
{
$Mtitle = htmlentities(urldecode($_REQUEST['meta_title']), ENT_QUOTES, 'UTF-8');

$Mall = mysql_query("SELECT * FROM `meta_tags` WHERE `title` = '".mysql_real_escape_string($Mtitle)."' LIMIT 1");

     if (mysql_num_rows($Mall) == 1)
     {
     $Mall = mysql_fetch_assoc($Mall);
     $ZMall = TRUE;
     }
     else $Mall = NULL;

}
else 
{
$Mtitle = NULL;
$Mall = NULL;
}

$set['title'] = 'Настройки Мета Тегов для страницы ';

include_once '../sys/inc/thead.php';
title();


if (isset($_GET['delete']) && isset($ZMall))
{
mysql_query("DELETE FROM `meta_tags` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1");
}


if (isset($_POST['save']))
{

$Mall['title'] = mysql_real_escape_string($_POST['meta_title']);
$Mall['meta_keywords'] = mysql_real_escape_string($_POST['meta_keywords']);
$Mall['meta_description'] = mysql_real_escape_string($_POST['meta_description']);

     if (isset($ZMall))
     {
     mysql_query("UPDATE `meta_tags` SET `meta_keywords` = '".$Mall['meta_keywords']."', `meta_description` = '".$Mall['meta_description']."' WHERE `title` = '".$Mall['title']."' LIMIT 1");
     }
     else
     {
     mysql_query("INSERT INTO `meta_tags`(`title`, `meta_keywords`, `meta_description`) VALUES('".$Mall['title']."', '".$Mall['meta_keywords']."', '".$Mall['meta_description']."')");
     $Mall['id'] = mysql_insert_id(); 
     }

unset($_POST);

msg('Meta теги сохранены');
}

aut();

if (isset($ZMall))
{
echo '<table class="post"><tr><td class="p_m">';

echo 'Title станицы: '.htmlspecialchars($Mall['title']).'<br />';
echo 'Ключевые слова: '.htmlspecialchars($Mall['meta_keywords']).'<br />';
echo 'Описание: '.htmlspecialchars($Mall['meta_description']).'<br />';
echo '<a href="?delete='.$Mall['id'].'&amp;meta_title='.urlencode($Mall['title']).'">удалить</a>';
echo '</td></tr></table>';
}

if (isset($Mtitle))$Mall['title'] = $Mtitle;

?>

     <form method="post" action="?meta_title=<? echo urlencode($Mtitle); ?>&amp;url=<? echo urlencode($return); ?>">

     <b>TITLE страницы:</b><br />
     <input name="meta_title" value="<? echo htmlentities($Mall['title'], ENT_QUOTES, 'UTF-8'); ?>" type="text" /><br />

     <b>Ключевые слова (META KEYWORDS):</b><br />
     <textarea name="meta_keywords"><? echo htmlentities($Mall['meta_keywords'], ENT_QUOTES, 'UTF-8'); ?></textarea><br />

     <b>Описание (META DESCRIPTION):</b><br />
<textarea name="meta_description"><? echo htmlentities($Mall['meta_description'], ENT_QUOTES, 'UTF-8'); ?></textarea><br />

<br />
* Настоятельно не рекомендую Вам злоупотреблять Мета данными, это может привести к блоку в поисковиках. Для TITLE страницы вполне достаточно 2-4 слова, Для ключевых слов, около 10. Описание страницы используется поисковиками для сниппетов(текст показываемый при выдаче), поэтому достаточно 160-170 символов вместе с пробелами, больше поисковик не покажет, а Вам лишний труд и груз.
<br />

     <input value="Сохранить" name="save" type="submit" />
     </form>

<?php

if (user_access('adm_panel_show'))
{
echo '<div class="foot">';
if (isset($return))echo '&laquo;<a href="'.$return.'">'.htmlspecialchars($Mall['title']).'</a><br />';
echo '&laquo;<a href="/adm_panel/">В админку</a><br />';
echo '</div>';
}

include_once '../sys/inc/tfoot.php';
?>
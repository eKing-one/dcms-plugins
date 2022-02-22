<?


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

$ob_str = ob_get_contents();
ob_end_clean();

$urlkey = urlencode('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);


$Mquery = mysql_query("SELECT * FROM `meta_tags` WHERE `title` = '".mysql_real_escape_string($set['title'])."' LIMIT 1");

if (mysql_num_rows($Mquery) == 1) 
{
$Mall = mysql_fetch_assoc($Mquery);

$set['meta_keywords'] = htmlspecialchars($Mall['meta_keywords']);
$set['meta_description'] = htmlspecialchars($Mall['meta_description']);
}


if (!isset($Mall) || $Mall['meta_keywords'] == NULL)
{

/**
 * Генерация ключевых слов
 * @param type $text -      текст для разбора
 * @param type $return -    кол-во возращаемых слов
 * @param type $min -       минимальное кол-во символов в слове
 * @param type $max -       максимальное кол-во символов в слове
 * @return type 
 */

     function m_keywords($text, $return = 10, $min = 4, $max = 15)
     {

    $text = htmlspecialchars_decode($text);
    $min = max(1, $min);
    $max = max($min, $max);
    
    /*
     * Подготовка текста
     */

    $search = array("'ё'",
                    "'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
                    "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
                    "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
                    "'&(.?)(\w+);'" 
                    );
                    

    $replace = array ("е",
                    " ",
                    " ",
                    " ",
                    "",
        );
    
    $text = preg_replace($search, $replace, $text);

    /*Вырезаем символы и цифры*/
    $del_symbols = array(",", ".", ";", ":", "\"", "#", "\$", "%", "^",
                         "!", "@", "`", "~", "*", "-", "=", "+", "\\",
                         "|", "/", ">", "<", "(", ")", "&", "?", "№", "\t",
                         "\r", "\n", "{","}","[","]", "'", "“", "”", "•",                          "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
                         );
    
    $text = str_replace($del_symbols, array(" "), $text);

    $text = mb_strtolower($text, 'UTF-8');

    /*Вырезаем стоп слова*/
    $stop_key = array('один', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'ноль', 'более', 'была', 'были', 'было', 'быть', 'ведь', 'весь', 'вдоль', 'вместо', 'вниз', 'внизу', 'внутри', 'вокруг', 'всегда', 'всего', 'всех', 'давай', 'давать', 'даже', 'достаточно', 'если', 'есть', 'за исключением', 'здесь', 'из-за', 'иметь', 'как-то', 'когда', 'кроме', 'либо', 'может', 'навсегда', 'надо', 'него', 'однако', 'отчего', 'очень', 'после', 'потому', 'потому что', 'почти', 'снова', 'также', 'такие', 'такой', 'того', 'тоже', 'только', 'хотя', 'чего', 'чего-то', 'чтобы', 'интернет', 'сайт', 'вопросы', 'ответы', 'компьютеры', 'прайс', 'заказ');
    $text = str_replace($stop_key, array(" "), $text);

    /*Избавимся от коротких и длинных слов*/
    $reg = "'\b(?:\w*?[^А-яA-z\s]\w*?|\w{1,".($min-1)."}|\w{".($max+1).",})\b'u";
    $text = preg_replace($reg, "", $text);

    /*Выделение ключевых слов*/
    /* Слова в массив*/
    $array = explode(" ", $text);

    /* Выделяем повторяющиеся*/
    $array = array_count_values($array);
    unset($array['']);
    arsort($array);
    
    $array = array_slice($array, 0, $return);
    $array = array_keys($array);
    $keyword = implode(', ', $array);
    
     return $keyword;
     }

$set['meta_keywords'] = m_keywords($ob_str);

}


if ($set['meta_keywords'] != NULL)
{
$ob_str = preg_replace('|<meta name="keywords" content="(.*?)" />|si', '<meta name="keywords" content="'.$set['meta_keywords'].'" />', $ob_str);
}

if ($set['meta_description'] != NULL)
{
$ob_str = preg_replace('|<meta name="description" content="(.*?)" />|si', '<meta name="description" content="'.$set['meta_description'].'" />', $ob_str);
}




echo $ob_str;


/*end mod - edit meta tags*/


if (isset($user) && $user['group_access'] > 10)
{
     if ($Mall)$color = 'green';
     else $color = 'red';
echo '<a href="/adm_panel/meta_tags.php?meta_title='.urlencode($set['title']).'&amp;url='.$urlkey.'"><span style="color: '.$color.';">* meta теги страницы</span></a>';

//если хотите видеть ключевые слова, для отладки например,
//раскомментируйте строку ниже
//echo '<br />'.$set['meta_keywords'];

}



if (file_exists(H."style/themes/$set[set_them]/foot.php"))
include_once H."style/themes/$set[set_them]/foot.php";
else
{


list($msec, $sec) = explode(chr(32), microtime());
echo "<div class='foot'>";
echo "<a href='/'>На главную</a><br />\n";




echo "<a href='/users.php'>Регистраций: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user`"), 0)."</a><br />\n";
echo "<a href='/online.php'>Сейчас на сайте: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `user` WHERE `date_last` > ".(time()-600).""), 0)."</a><br />\n";
echo "<a href='/online_g.php'>Гостей на сайте: ".mysql_result(mysql_query("SELECT COUNT(*) FROM `guests` WHERE `date_last` > ".(time()-600)." AND `pereh` > '0'"), 0)."</a><br />\n";
if (isset($user) && $user['level']!=0) echo "Генерация: ".round(($sec + $msec) - $conf['headtime'], 3)." сек<br />\n";
echo "</div>\n";
echo "<div class='rekl'>\n";
rekl(3);
echo "</div>\n";
echo "</div>\n</body>\n</html>";
}
exit;
?>
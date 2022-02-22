/*******
* Модуль: Управления мета данными.
* Обновление от 02.07.2013г.
* Автор: Кредитор (Королев Руслан)
* ICQ: 441460
* Email: kpegumop@yandex.ru
* Сайт: http://gix.su - безопасные сделки
*****
* Любые скрипты на заказ, 
* безопасность ресурса, оптимизация
*******/


/**
* В обновлении убраны мелкие ошибки Notice в /adm_panel/meta_tags.php
*/


Описание: Модуль дает возможность иметь по умолчанию человеческие ключевые слова, они генерируются налету функцией. А так же создать и в последствии иметь возможность редактировать Мета данные для любой отдельно взятой страницы вашего сайта.


Установка: 
1. Выполнить запрос из файла архива meta_tags.sql.
2. Выгрузить из папки /adm_panel/ файл в одноименную папку на вашем сайте.
3. Открываем на вашем сайте файл /sys/inc/thead.php и в самом начале добавляем строку:

ob_start();

4. Oткрываем на вашем сайте файл /sys/tfoot.php и в самом начале добавляем следующий код(в архиве, в sys/inc/tfoot.php пример):



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




5. И наконец последнее. код ниже, лично я разместил сразу под тем что выше указан, но в веб версии наверное некрасиво будет смотреться, хотя его только вы и видите. поэтому придеться открыть foot.php каждой темы и вставить его где вам угодно. это ссылка на добавление/редактирование мета данных. если ссыль зеленая, то метаданные вами уже созданы для этой страницы, если красная, то соответственно нет.

if (isset($user) && $user['group_access'] > 10)
{
     if (isset($Mall))$color = 'green';
     else $color = 'red';
echo '<a href="/adm_panel/meta_tags.php?meta_title='.urlencode($set['title']).'&amp;url='.$urlkey.'"><span style="color: '.$color.';">* meta теги страницы</span></a>';

//если хотите видеть ключевые слова, для отладки например,
//раскомментируйте строку ниже
//echo '<br />'.$set['meta_keywords'];

}

6. Спасибо за покупку, хорошей посещаемости вашему ресурсу.


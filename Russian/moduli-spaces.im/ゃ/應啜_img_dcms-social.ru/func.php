<?php
/*
НАБОР НЕОБХОДИМЫХ ФУНКЦИЙ
*/
$usa = $_SERVER['HTTP_USER_AGENT'];////ЮСЫР АГЕНТ
$ip = @$_SERVER['REMOTE_ADDR'];///ИПЪ
$sid=(isset($_GET["sid"]))?$_GET["sid"]:"0";
// /Проверка введенных символов на правильность.Правила взяты с kmx.ru
$check = array("MAIL" => array("^[a-zA-Z0-9_.-]+\@[a-zA-Z0-9_.-]+\.[a-zA-Z]{2,5}$", "xxx@yyy.zz"),
"LOGIN" => array("^[a-zA-Z0-9_.\@-]{2,50}$", "латинские буквы, цифры и символы \".@-\""),
"PASSWORD" => array("^[a-zA-Z0-9.,!#%*()]{3,20}$", "от 3х английских букв"),
"INT" => array("^[0-9]$", "только цифры"),
"FLOAT" => array("^\-?[0-9]*\.?[0-9]*$", "целаячасть.дробная"),
"IP" => array("^[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}$", "XXX.XXX.XXX.XXX"),
"WORD" => array("^[0-9a-zA-Z_.-]*$", "только английские буквы и цифры"),
"URL" => array("^(https?|HTTPS?|ftp|gopher):\/\/[a-zA-Z0-9_-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z]{1,7}" . // Основное имя сервера
"(\/[a-zA-Z0-9\_\.-]*\/?)*" . // Имена возможных подкаталогов и файл
"(\?[a-zA-Z0-9\_]+=[a-zA-Z0-9]+([&][a-zA-Z0-9_]+=[a-zA-Z0-9]+)*)?$", "http://site.com/path/"),
"TIME" => array("^[0-9]{2}(:[0-9]{2})?(:[0-9]{2})?$", "ЧЧ:ММ:СС"),
"DATE" => array("^[0-9]{4}-[0-9]{2}-[0-9]{2}$", "ГГГГ-ММ-ДД"),
"DATETIME" => array("^[0-9]{4}-[0-9]{2}-[0-9]{2}([ ]+[0-9]{2}:[0-9]{2}:[0-9]{2}){0,1}$", "ГГГГ-ММ-ДД ЧЧ:ММ:СС"),
"NUMPHONE" => array("^\+?[0-9 ()-]{5,20}$", "только цифры"),
"SESSION" => array("^[0-9a-zA-Z]*$", "правильная сессия"),
"FILE" => array("^[0-9a-zA-Z_.-]*$", "только английские буквы и цифры"),
"ID" => array("^[0-9]*$", "только цифры"),
"WMR" => array("[R]+[0-9]{12,12}$", "R123456789123"),
"ICQ" => array("^[0-9]{6,9}$", "8610275"),
);
/////////////////////////////////////////////////
///ФУНКЦИЯ СОЗДАЕТ ОФОРМЛЕННЫЕ ЭЛЕМЕНТЫ//////////
/////////////////////////////////////////////////
function diz($value, $div)/////$VALUE - ТЕКСТ, $DIV - ДИВ КЛАСС ДИЗАЙНА
{
global $hr;
if($div=="header"){$F=$hr;} else {$F="";}/////ЕСЛИ хЕАДЕР ТО ЛИНИЮ
$cod = '<div class="' . $div . '">' . $value . '</div> ' . $F;
return $cod;////ВОЗВРАЩАЕМ ЭЛЕМЕНТ
}
/////////////////////////////////////////////////
//ФУНКЦИЮ ЗАБИРАЕТ ИНФОРМАЦИЮ О ЗВЕРЕ////////////
/////////////////////////////////////////////////
function info()
{
global $usa,$sid,$ip;
if($sid!=0){
$query=mysql_query("select * from zveri where ses=".$sid." and ua='".$usa."' and ip='".$ip."' and ".time()."-timeses<60*3");
$info=mysql_fetch_array($query);
if(empty($info)){$info=0;} else {mysql_query("update zveri set timeses=".time()." where ses=".$sid);}
} else {$info=0;}
return $info;
}
//////////////////////////////////////////////////////
///ПОЛЕЗНАЯ ФУНКЦИЯ.ЗАБИРАЕТ ОДИНАРНЫЕ ДАННЫЕ С БАЗЫ//
//////////////////////////////////////////////////////
function zapros($zapros)
{
global $sql;
$a = mysql_query($zapros, $sql) or die (mysql_error($sql));
$b = mysql_fetch_row($a);
return $b[0];
}
/*
ФУНКЦИЯ ДОБАВЛЕНИЯ ССЫЛОК.ПЕРЕДАЕТ СЕСИЮ И ЗАПРОСЫ.
$page - имя страницы, например "index" соответствует index.php
$get - запрос
$name - имя ссылки
Прмер вывода ссылки echo url("vhod","a=mail","Восстановление пароля");
*/
function url($page = "index", $get = "", $name = "На главную")
{
global $sid;
$us=info();
if ($us!=0) {
if (isset($get))$get = "&" . $get . "";
$sis = "" . $page . ".php?sid=" . $sid . "" . $get . "";
    }
    else{
        if ($get)$get = "?" . $get . "";
        $sis = "" . $page . ".php" . $get . "";
    }
    $sis=(isset($sis))?$sis:"index.php";
    $url = '<a href="' . $sis . '">' . $name . '</a><br/>';
    return $url;
}
//////////////////////////////////////////////////////////
///ФУНКЦИЯ ВЫВОДИТ НУЖНУЮ КАРТИНКУ.СДЕЛАНО ДЛЯ УДОБСТВА///
//////////////////////////////////////////////////////////
function img($name,$nb=1){
if($nb){$c="Ђ";} else {$c="";}
$img=$c.'<img src="img/'.$name.'.gif" alt="."/>'.$c;
return $img;
}
/////
$div = array(
"end" => "</div>",
"header" => "<div class=\"header\">",
"menu" => "<div class=\"menu\">",
"footer" => "<div class=\"footer\">",
"main"=>"<div class=\"main\">"
);

?>
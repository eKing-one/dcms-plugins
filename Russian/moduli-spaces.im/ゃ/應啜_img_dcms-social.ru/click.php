<?php
// ///////ПОДКЛЮЧАЕМ ФАЙЛИКИ
include_once("ini.php");
include_once("ip.php");
$link=(isset($_GET['link']))?$_GET['link']:"0";
$link = (int)$link; /////ВЫРАЖАЕМ ЦЫФИРКУ
// /////////ПИРИХОДИМ..... :-)
$a=(isset($_GET['a']))?$_GET['a']:"0";
if ($a == "go") {
if($brauzer=="cell"){$qw="M";}
elseif($brauzer=="opera"){$qw="O";}
else {$qw="C";}
mysql_query("update money set clickS".$qw."=clickS".$qw."+1, clickV".$qw."=clickV".$qw."+1, clickS=clickS+1, clickV=clickV+1");
$perexod=zapros("select ".$brauzer." from url");
///////////////////////////////
///////////////////////////////
$key=(isset($_GET['key']))?$_GET['key']:"0";
// ///////А БЕЗ ЦЫФРЫ УХОДИМ
if (!$link) {
header ("location: ".$perexod);
exit;
}
////////ЗАЩИТА
if($key){
if(strlen($key)!=22)
{
header ("location: ".$perexod);
exit;
}
$iplong=substr($key,0,10);
if($ip!=long2ip($iplong))
{
header ("location: ".$perexod);
exit;
}
$ts=substr($key,12,22);
$ts=(int)$ts;
if(time()-$ts>60)
{
header ("location: ".$perexod);
exit;
}
}
else
{
header ("location: ".$perexod);
exit;
}
// //////А ВДРУГ ТАКОГО САЙТА НЕТ?
$query = mysql_query("select * from sites where id_url=" . $link);
$q = mysql_num_rows($query);
if (!$q) {
header ("location: ".$perexod);
exit;
}
// ///////А ВДРУГ САЙТ НЕ АКТИВЕН?
$query = zapros("select status from sites where id_url=" . $link);
if ($query != "activ") {
header ("location: ".$perexod);
exit;
}
// ///////А ВДРУГ ЗВЕРЬ ЗАБАНЕН?
$id_zver = zapros("select id_zver from sites where id_url=" . $link);
$query = zapros("select status from zveri where id_zver=" . $id_zver);
if ($query != "activ") {
header ("location: ".$perexod);
exit;
}
// ////////АНТИНАКРУТКА.ЗАЩИТА ОТ САМОКЛИКОВ
$query = zapros("select * from trafick where id_zver=" . $id_zver . " and usa='" . $usa . "'");
if ($query) {
header ("location: ".$perexod);
exit;
}
$query = zapros("select * from trafick where usa='" . $usa . "' and ip='".$ip."'");
    if ($query) {
        header ("location: ".$perexod);
        exit;
    }
   if(substr_count($usa,"UNTRUSTED")!=0){
	header ("location: ".$site);	
   }
    // //////ШЕЙФ :-)
    $sheif=zapros("select sheif from money");
    if (($sheif) && (mt_rand(0, $sheif) == 0)) {
        header ("location: ".$perexod);
        exit;
    }
    // ////ПРОВЕРЯЕМ ПЕРЕХОД НА ОПЛАЧИВАЕМОСТЬ
	/*
    if($oper==0){
    header ("location: ".$perexod);
    exit;
}
    if (zapros("select `".$oper."` from money")==0) {
        header ("location: ".$perexod);
        exit;
    }
	*/
    // /////ВЫПОЛНЯЕМ ЗАПРОСЫ
   $give=($money[$oper]/1000);
    mysql_query("update zveri set balans=balans+" . $give. ", clickS=clickS+1, clickV=clickV+1, monS=monS+" . $give . ", monV=monV+" . $give . " where id_zver=" . $id_zver); ///ДАЕМ ДЕНЕГ
    $date = date("j:m:Y - G:i:s"); ///ЧЁ ТАМ У НАС СЕГОДНЯ?
    $refer = @$_GET['refer']; //ОТКУДА ТЫ К НАМ СВАЛИЛСЯ, ЁПТ :-)
    mysql_query("insert into trafick set id_zver=" . $id_zver . ", id_url=" . $link . ", usa='" . $usa . "', brauzer='".$brauzer."', ip='" . $ip . "', oper=".$oper.", time=NOW()"); ///ЗАПИСЫВАЕМ В СТАТУ
    // //ЛОГИРУЕМ КОМИСТРУАКЦИЮ ГИПОРБОЛЯРНЫХ ПЕРЕМЕННЫХ В АССОЦИАТИВНЫХ МАССИВАХ ВО ИЗБЕЖАНИЕ КОММУТАЦИИ КОНГРУЕНТАЛЬНОСТИ ДИДУКЦИОННОГО ИНДУКТОРА С ФОТОННЫМ ТРИАНИГИЛЯТОРОМ :-)
    $log = fopen("logi/" . $id_zver . ".log", "a");
    if (!$refer) {
        $refer = "НЕТ РЕФЕРЕР";
    }
    fwrite($log, zapros("select url from sites where id_url=" . $link) . "|" . $date . "|" . $usa . "|" . $ip . "|" . $oper_name. "|" . $refer . "\n");
    fclose($log);
    // ////
	mysql_close($sql); ///СОЕДИНЕНИЮ СПАТЬ...
	header ("location: ".$perexod);
}
// //////////////ПРОВЕРКА ДАННЫХ.ЗАСЧИТЫВАНИЕ ПЕРЕХОДА И ПРОЧИЕ НЕ НУЖНЫЕ ВЕЩИ :-)
elseif (!$a) {
    // ///////А БЕЗ ЦЫФРЫ УХОДИМ
    if (!$link) {
        header ("location: click.php?a=go");
        exit;
    }
	$refer = @$_SERVER['HTTP_REFERER']; //ОТКУДА ТЫ К НАМ СВАЛИЛСЯ, ЁПТ :-)
	$xx=(isset($refer))?$xx='&refer='.$refer.'':'';
header("Content-type: text/html; charset=utf-8");
header("Cache-Control: no-cache");
	echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" 
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Волкес | Клик</title><meta http-equiv="Refresh" content="0; URL=click.php?a=go&link='.$link.'&key='.ip2long($ip).rand(10,99).time().''.$xx.'"></head><body>
<style type="text/css">

body
{
 background-color: #FFFFFF;
 color: #000000;
 font-family: arial, tahoma, verdana, sans-serif;
 font-size: 10pt;
 padding: 2px;
 margin: 0px auto;
 max-width: 240px;
}

a
{
 text-decoration: underline;
 color: #4682B4;
}

a:active
{
 text-decoration: underline;
 color: #4682B4;
}

a:visited
{
 text-decoration: underline;
 color: #4682B4;
}

a:hover
{
 text-decoration: underline; color: #FF0000;
}
div.main
{
 background-color: #FFF0F5;

 border-color: #DB7093;
 border-style: solid;
 border-right-style: none;
 border-bottom-style: none;
 border-width: 1px;

 padding: 0px;
}

div.title
{
 background-color: #FFB6C1;
 color: #B03060;

 border-color: #FFF0F5;
 border-right-color: #DB7093;
 border-bottom-color: #DB7093;
 border-style: solid;
 border-width: 1px;

 padding: 2px;
 font-weight: bold;
}
div.ban
{
 background-color: #FFFFFF;
 color: #666666;

 border-color: #FFF0F5;
 border-right-color: #DB7093;
 border-bottom-color: #DB7093;
 border-style: solid;
 border-width: 1px;

 padding: 2px;
}
div.box
{
 background-color: #FFE4E1;
 color: #666666;

 border-color: #FFF0F5;
 border-right-color: #DB7093;
 border-bottom-color: #DB7093;
 border-style: solid;
 border-width: 1px;

 padding: 2px;
}
</style>
</head>
<body>
<div class="main">
<div class="title"><center>...::ВОЛКЕС::...</center></div>
<div class="box">
<center><a href="http://volkes.ru" style="text-decoration: none;"> <<< </a><a href="click.php?a=go&link='.$link.'&key='.ip2long($ip).rand(10,99).time().''.$xx.'">вход</a><a href="http://volkes.ru" style="text-decoration: none;"> >>> </a></center></div>
<div class="box"><center><script language="javascript" src="//volkes.ru/sjs/trs.js"></script></div> 

</center></div>
<div class="title"><center>© volkes.ru<br/><div style="display:none"><img src="http://c.wen.ru/2304275.wbmp?grom.volkes.ru" alt="волкес" /><img src="//top-fwz1.mail.ru/counter?id=2437633;t=479;l=1" alt="волкес" /></div><img src="http://counter.yadro.ru/hit?t26.5;rhttp://volkes.ru/;s1366*768*32;uhttp://volkes.ru/" alt="volkes" /></center></div>
</div></body></html>';
}
else
{
header ("location: click.php?a=go");
}
?>
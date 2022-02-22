<?php
##############################################
include 'head.php';
##############################################
ini_set('display_errors',0); ini_set ('register_globals', 0);
session_name('SID'); session_start();
##############################################
$host= "rugame.mobi"; $path="/inet/weather/?".$_SERVER ['QUERY_STRING'];
$fp=fsockopen($host,80,$errno, $errstr,10);
if(!$fp) { echo "$errstr ($errno)<br/>\n"; }else{
$data = "";$post=0; foreach($_POST as $key=>$value){
$post=1; $data.="&$key=$value";} if($data)$data=substr ($data,1);
if($post) $headers = "POST $path HTTP/1.0\r\n";else
$headers = "GET $path HTTP/1.0\r\n"; $headers.= "Host: $host\r\n";
$headers.= "Accept: text/html, application/xml;q=0.9, application/xhtml+xml, image/png, image/jpeg, image/gif,image/x-bitmap, */*;q=0.1\r\n";
$headers.= "Accept-Charset: utf-8;q=0.6 windows-1251;q=0.1*;q=0.1\r\n";
$headers.= "Accept-Encoding: utf-8\r\n";
$headers.= "Accept-Language: ru, en;q=0.9\r\n";
$headers.= "User-Agent: ".$_SERVER ['HTTP_USER_AGENT']."\r\n";
if($post){ $headers.= "Content-type: application/x-www-form-urlencoded\r\n";
$headers.= "Content-Length: ".strlen ($data)."\r\n";
$headers.= "\r\n"; $headers.= $data;}else $headers.="\r\n";
@fwrite($fp, $headers); while($file != "\r\n") $file = @fgets($fp, 128);
$file = ''; while(!feof($fp)) $file.= @fgets($fp, 4096); @fclose($fp); }
##############################################
$file=preg_replace('|<!DOCTYPE(.*?)<body>|is', '', $file);
$file = str_replace('<form action="/inet/weather/','<form action="/weather/',$file);
$file=str_replace('<a href="/inet/weather/', '<a href="/weather/', $file);
$file = str_replace('<img src="/','<img src="http://rugame.mobi/',$file);
$file=preg_replace('|Предоставлено(.*?)</a><br/>|is', '', $file);
$file=preg_replace('|<img src="http://rugame.mobi/css/thm/18/ico.gif" alt="" /> <a href="/serv.php(.*?)</a><br/>|is', '', $file);
$file=preg_replace('|<div class="footer">(.*?)</html>|is', '</div></div>', $file);
##############################################
echo $file;
##############################################
include 'foot.php';
##############################################
?>
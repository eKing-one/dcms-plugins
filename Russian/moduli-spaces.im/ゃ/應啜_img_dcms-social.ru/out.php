<?php
/*
скрипт ухода с сервера
*/
include_once("ini.php");
$url=(isset($_GET['url']))?$_GET['url']:"index.php";
header("Content-type: text/html; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN"
"http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Переход...</title>
<meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml; charset: UTF-8"/>
<link rel="stylesheet" type="text/css" href="style.css"/>
<meta http-equiv="refresh" content="1;URL=' . $url . '"/>
</head>
<body>
'.$div["main"].'
'.$div["menu"].'
<center>
Переходим!<br/>
<a href="' . $url . '">Дальше...</a>
</center>
'. $div["end"].'
'.$div["end"].'
</body>
</html>';

?>


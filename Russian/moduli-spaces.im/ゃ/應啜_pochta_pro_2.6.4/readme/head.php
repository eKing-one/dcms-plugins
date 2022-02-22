<?php
//header("Content-type: application/vnd.wap.xhtml+xml");
//header("Content-type: application/xhtml+xml; charset=utf-8");
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
@error_reporting(0);
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<title><?echo $set['title'];?></title>
<?php
if (!isset($mailid))$mailid = 0;
if (!$user) $inter = 2000;
if (!$_COOKIE['colvokomm'])$_COOKIE['colvokomm'] = $set['p_str'];
$cc = intval($_COOKIE['colvokomm']);
$comp = intval($_COOKIE['comp']);
if (isset($_GET['page']))$page = htmlspecialchars($_GET['page']);
else
$page = 'start';
if ($_SERVER['PHP_SELF'] != '/new_mess.php')
{
echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>

<script type="text/javascript">
a = 0;
function mail(){
$.ajax({url: "/mail.ajax.php",cache: false,type: "GET",data: "mailid='.$mailid.'",success: function(html){$("#mail").html(html);}});
}
$(document).ready(function(){mail();setInterval("mail()", 2000);});
function bot(){
$.ajax({url: "/bot.ajax.php",cache: false});
}
$(document).ready(function(){bot();setInterval("bot()", 4000);});
';
if ($comp == 1)
echo 'function ons(){
$.ajax({url: "/ons.ajax.php",type: "GET",data: "mailid='.$mailid.'&getid='.$mailid.'",cache: false,success: function(html){$("#ons").html(html);}});
}
$(document).ready(function(){ons();setInterval("ons()", 1500);});';
else
echo 'function onsm(){
$.ajax({url: "/onsm.ajax.php",type: "GET",data: "mailid='.$mailid.'&getid='.$mailid.'",cache: false,success: function(html){$("#onsm").html(html);}});
}
$(document).ready(function(){onsm();setInterval("onsm()", 1500);});';
echo '
function mailid(){
$.ajax({url: "/mailid.ajax.php",cache: false,type: "GET",data: "mailid='.$mailid.'&getid='.intval($_GET['id']).'&page='.$page.'&cc='.$cc.'",success: function(html){$("#mailid").html(html);}});
}
$(document).ready(function(){mailid();setInterval("mailid()", 1000);});
</script>';
}
?>
<link rel="shortcut icon" href="/style/themes/<?echo $set['set_them'];?>/favicon.ico" />
<link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />
</head>
<body>
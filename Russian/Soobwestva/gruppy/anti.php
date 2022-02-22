<?
$_POST['aftar']=preg_replace("#[(http|https|ftp)]+[(://)]+[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['aftar']);
$_POST['aftar']=preg_replace("#[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['aftar']);
$_POST['names']=preg_replace("#[(http|https|ftp)]+[(://)]+[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['names']);
$_POST['names']=preg_replace("#[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['names']);
$_POST['nick']=preg_replace("#[(http|https|ftp)]+[(://)]+[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['nick']);
$_POST['nick']=preg_replace("#[0-9a-zA-Z_.-]+.[a-zA-Z]{2,4}#i","[-REKLAM-]",$_POST['nick']);
?>
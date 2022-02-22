<?
$set['web']=false;
//header("Content-type: application/vnd.wap.xhtml+xml");
//header("Content-type: application/xhtml+xml");
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<title>
<?echo $set['title'];?>
</title>
<?
if ($webbrowser=='web')
{
echo '<script src="http://yandex.st/jquery/1.7.2/jquery.min.js"></script>
<script src="/ajax/js/arcticmodal/jquery.arcticmodal-0.2.min.js"></script>
<link rel="stylesheet" href="/ajax/js/arcticmodal/jquery.arcticmodal-0.2.css">';
}
?>
<link rel="shortcut icon" href="/style/themes/<?echo $set['set_them'];?>/favicon.ico" />
<link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />

</head>
<body><div class='body'>

<?
if (isset($_SESSION['message'])){
	echo "<div class='msg'>$_SESSION[message]</div>";
	$_SESSION['message']=NULL;
}

echo "<center><div class='logo'><img src='/style/themes/$set[set_them]/logo.png' alt='logo'/><br /></div></center>";


?>

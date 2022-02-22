<?
$set['web']=true;
$set['set_show_icon']=2;
//header("Content-type: application/xhtml+xml");
header("Content-type: text/html");
echo '<?xml version="1.0" encoding="utf-8"?>';
?><!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
<title><?echo $set['title'];?></title>
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="stylesheet" href="/style/themes/<?echo $set['set_them'];?>/style.css" type="text/css" />
<link rel="alternate" title="Новости RSS" href="/news/rss.php" type="application/rss+xml" />
<script src='/style/themes/<?echo $set['set_them'];?>/jg.js' type='text/javascript'></script>

</head> 
<body align='center'>
<div class="body">
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td>
<?
include_once H.'style/themes/'.$set['set_them'].'/title.php';
?>
<table width='100%' cellspacing="0" cellpadding="0">
<tr>

<td id="content_outer" valign="top">
<table class="content_table" align="center" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr valign="top">
                    <td>
					<div id="left_outer">
              <div id="left_top">
			  </div>
              <div id="left_inner_float">
                <div id="left_inner">
                  		<div class="moduletable_menu">
						<div class="moduletable">
			<h3>Главное меню</h3>

<?
include_once H.'style/themes/'.$set['set_them'].'/menu.php';
?>
</div>
</div>
</div>
<div id="left_bottom">
</div>
</div>
</td>
<td id="content" width="100%" align="center"><div align="center">
              <div id="content_top_bar">
                <div id="content_top">
                  <div id="content_right_top"></div>
                </div>
              </div>
            </div>
            <table class="content" width="80%" align="center" border="0" cellpadding="0" cellspacing="0">
              <tbody><tr>
                <td colspan="3"><div id="pathway"><?echo $set['title'];?>
                  </div>
                  <div id="main_content">

<?
if ($_SERVER['PHP_SELF']=='/index.php')
{
include_once H.'style/themes/'.$set['set_them'].'/news.php';
include_once H.'style/themes/'.$set['set_them'].'/main.php';
}
?>
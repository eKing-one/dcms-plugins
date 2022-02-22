<?

######################################################
#   Видео Портал файлов с ютуба dcms 6.6.4 и фиера   #         
#   Автор: Saint, JumanG.ru  & wmsait.ru  	    	 #
#   icq: 399537814, сайт: http://DCMS-FIERA.ru     	 #
#   Вы не имеете права продавать, распростронять,	 #
#   давать друзьям даный скрипт.                 	 #
#   Даная версия являет платной, и купить       	 #
#   можно только у Saint'a 							 #
#	Вы не имеете права ставить если модуль 			 #
#	даже если он слит!!  							 #
#	даже если он был вап продаан барыгой !!!  		 #
#	даже если вы его случайно где то скачали !!!	 #  
#	даже если что то еще ,							 #
#	не тратьте моё время и ваше	,					 #
#	постреченое на постройку вашего сайта ;)		 #
######################################################
#		Видео Портал файлов с ютуба by Saint 		 #
######################################################
$JumanG = $_SERVER['DOCUMENT_ROOT'];
include_once $JumanG.'/sys/inc/start.php';
include_once $JumanG.'/sys/inc/compress.php';
include_once $JumanG.'/sys/inc/sess.php';
include_once $JumanG.'/sys/inc/home.php';
include_once $JumanG.'/sys/inc/settings.php';
include_once $JumanG.'/sys/inc/db_connect.php';
include_once $JumanG.'/sys/inc/ipua.php';
include_once $JumanG.'/sys/inc/fnc.php';
$show_all=true; # показ для всех
include_once $JumanG.'/sys/inc/user.php';
$set['title']='Видео  файлы с ютуба'; 
include_once $JumanG.'/sys/inc/thead.php';
title().aut();

?><div class="p_m"><center>
Видео портал личных файлов с ютуба
</center>
</div>
<link rel="stylesheet" href="/modules/videoyou/jquery/style.css" type="text/css"/>
<script type="text/javascript" src="/modules/videoyou/jquery/jquery.js"></script>
<script type="text/javascript" src="/modules/videoyou/jquery/facebox.js"></script>
<?
echo "<script type=\"text/javascript\">

jQuery(document).ready(function($) {

$('a[rel*=facebox]').facebox({

loading_image : '/modules/videoyou/jquery/icons/loading.gif',

close_image   : '/modules/videoyou/jquery/icons/closelabel.gif'

}) 

})

</script>";
if (isset($_GET['category']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou_category` WHERE `id` = '".intval($_GET['category'])."'"),0)==1)
{
if (isset($_POST['write']) && isset($_POST['write2']))
{
$timeclear1=0;
if ($_POST['write2']=='sut')$timeclear1=$time-intval($_POST['write'])*60*60*24;
if ($_POST['write2']=='mes')$timeclear1=$time-intval($_POST['write'])*60*60*24*30;
$q = mysql_query("SELECT * FROM `videoyou` WHERE `time` < '$timeclear1'",$db);
$del_th=0;
while ($post = mysql_fetch_array($q))
{
mysql_query("DELETE FROM `videoyou` WHERE `id` = '$post[id]'",$db);
$del_th++;
}
mysql_query("OPTIMIZE TABLE `videoyou`",$db);
msg ("Удалено $del_th видео клипов");
}

if (isset($_POST['msg']) && isset($user))
{
$msg=$_POST['msg'];
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)>102400){$err='Запись слишком длинная';}
elseif (strlen2($msg)<2){$err='Короткое сообщение';}
elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `id_user` = '$user[id]' AND `msg` = '".mysql_escape_string($msg)."' LIMIT 1"),0)!=0){$err='Ваша запись повторяет предыдущую';}
else{
$msg=mysql_escape_string($msg);

if (isset($_POST['video']) && isset($user))
{
$video=$_POST['video'];
$opis=$_POST['opis'];
if (isset($_POST['translit']) && $_POST['translit']==1)$video=translit($video);
if (strlen2($video)>102400){$err='Запись слишком длинная';}
else
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `id_user` = '$user[id]' AND `video` = '".mysql_escape_string($video)."' LIMIT 1"),0)!=0){$err='Ваша запись повторяет предыдущую';}
else
{
$video=mysql_escape_string($video);
$opis=mysql_escape_string($opis);
mysql_query("INSERT INTO `videoyou` (id_user, time, video, msg, opis, id_category) values ('$user[id]', '$time', '$video', '$msg', '$opis', '".intval($_GET['category'])."')");
msg('Запись добавлена');
}
}
}
}
echo "<div class='foot'>";
echo "<a href='/videoyou'>В разделы</a>\n";
echo "</div>";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `id_category` = '".intval($_GET['category'])."'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)echo "  <div class='p_t'>Нет видео записей  </div>";
$q=mysql_query("SELECT * FROM `videoyou` WHERE `id_category` = '".intval($_GET['category'])."' ORDER BY id LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
$ank=mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id` = $post[id_user] LIMIT 1"));
$video_Saint=$post['msg'];
$video_Saint_w="650";
$video_Saint_h="350";
$video_Saint_w1="250";
$video_Saint_h1="140";
$sdfsdfdfs= $post['rating']/100;
echo '<div class="p_m">';
?><div class="list_item gradient_block1" ><table style="width:auto;"><tr><td><?




					echo "<div align='right'><object width='$video_Saint_w1' height='$video_Saint_h1'><param name='movie' value='https://www.youtube.com/v/$video_Saint?version=3'></param><param name='allowFullScreen' value='true'></param><param name='allowScriptAccess' value='always'></param><embed src='https://www.youtube.com/v/$video_Saint?version=3' type='application/x-shockwave-flash' allowfullscreen='true' allowScriptAccess='always' width='$video_Saint_w1' height='$video_Saint_h1'></embed></object></div></td><td style='vertical-align:middle;' ><br />";
					echo '<a href="#'.$post['id'].'" rel="facebox">  ';
					echo " <br/>Заголовок ".output_text($post['video'])."</a><br />";
					
				#	echo " Описание : ".output_text(mb_substr($post['opis']),0,99);

				#	альтернатива mb_substr ,на денвере не робит у меня так что по другому реализовал 	
				$opis_ic = $post['opis'];
				$opis_ic_ = iconv_substr($opis_ic,0,99,'utf-8');
				echo output_text($opis_ic_);
				echo "<br /></a> <div style='font-size:11px;'> Автор : <a href='/info.php?id=$ank[id]'><span class='cous'>$ank[nick]  </span></a> 		<span class='cous'>",vremja($post['time']),"</span> <br />";
				#	<br />Просмотры  <span class='cous'>".output_text($post['lakes'])."</span>
				if (($user['level'] > 3 || $ank['id']==$user['id']) )echo "<br /> <a href='/modules/videoyou/update.php?id=$post[id]'><span class='cous'> Редактировать</span></a> <a href='/modules/videoyou/delete.php?id=$post[id]'><span class='cous'>Удалить</span></a> </div>";

?></td></tr></table></div><?
?>
<script type='text/javascript' src='//yandex.st/share/share.js' charset='utf-8'>
</script><div class='yashare-auto-init' data-yashareL10n='ru' data-yashareType='none' data-yashareQuickServices='vkontakte,odnoklassniki,twitter,facebook,moimir,friendfeed,moikrug,pinterest,surfingbird'></div> <?
echo '</a><center> <div id="'.$post['id'].'" style="display:none;">';
echo "  <div class='p_m'>\n";
echo " <font size=3>Заголовок : ";echo output_text($post['video']);
//mysql_query("UPDATE `videoyou` SET `lakes` = '".($post['lakes']+1)."' WHERE `id` = '$post[id]' LIMIT 1");
echo "</font size> </div><object width='$video_Saint_w' height='$video_Saint_h'><param name='movie' value='https://www.youtube.com/v/$video_Saint?version=3'></param><param name='allowFullScreen' value='true'></param><param name='allowScriptAccess' value='always'></param><embed src='https://www.youtube.com/v/$video_Saint?version=3' type='application/x-shockwave-flash' allowfullscreen='true' allowScriptAccess='always' width='$video_Saint_w' height='$video_Saint_h'>
</embed></object><div class='p_m'>Описание : <br/>",output_text($post['opis']),"</div><div class='p_m'>
Время добавления <span class='cous'>",vremja($post['time']),"</span>
<div style='font-size:11px;'> Автор : <a href='/id$ank[id]'><span class='cous'>$ank[nick]
  </span></a> <br /> </div></div></center>";
# 	<br />Просмотры  <span class='cous'>".output_text($post['lakes'])."</span>

echo "  </div>
";
}

if ($k_page>1)str('?category='.intval($_GET['category']).'&amp;',$k_page,$page);
echo "<div class='p_m'> <a href='?act=adds'>
<span class='cous'> Добавить видео</span></a></div>";
if (isset($_GET['act']) && $_GET['act']=='adds')
{
echo "<div class='p_m'>
<form method=\"post\" action=\"?category=".intval($_GET['category'])."\">";
echo "Заголовок:<br /><input name=\"video\"></textarea></div>";
echo "<div class='p_m'>Ссылка на видео:<br /><input name=\"msg\"></textarea></div>";
echo "<div class='p_m'>Описание :<br /><textarea name=\"opis\"></textarea></div>";
echo "<input value=\"Добавить\" type=\"submit\" />\n";
echo "</form></div>";
}
echo "<div class='foot'>";
echo "<a href='/videoyou'>В разделы</a> ";
#:: <a href='/modules/videoyou/top.php'>top видео </a>
echo "</div>";
}else{
if (isset($_POST['name']) && isset($user) && $user['level']>1)
{
$msg=$_POST['name'];
if (!isset($_POST['icon']) || $_POST['icon']==null)
$icons='default';
else
$icons=preg_replace('#[^a-z0-9 _\-\.]#i', null, $_POST['icon']);
if (strlen2($msg)>10024){$err[]='Название слишком длинное';}
elseif (strlen2($msg)<2){$err[]='Короткое название';}
elseif(!isset($err)){
if (isset($_GET['edit']))
{
mysql_query("UPDATE `videoyou_category` SET `name` = '".my_esc($msg)."', `icon` = '$icons' WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1");
msg('Категория успешно переименована');
}else{
mysql_query("INSERT INTO `videoyou_category` (name, icon) values('".my_esc($msg)."', '$icons')");
msg('Категория успешно добавлена');
}
}
}
if (isset($_GET['delete']))
{
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `videoyou_category` WHERE `id` = '".intval($_GET['delete'])."' LIMIT 1"));
if ($user['level']>3)
{
$q=mysql_query("SELECT * FROM `images` WHERE `id_category` = '".intval($_GET['delete'])."'");
while ($post2 = mysql_fetch_assoc($q))
{
@unlink(H."sys/images/$post2[name]");
}
mysql_query("DELETE FROM `videoyou_category` WHERE `id` = '$post[id]'");
}
header("Location: index.php?".SID);
}

$q=mysql_query("SELECT * FROM `videoyou_category` ORDER BY name ASC");
$i=1;
while ($post = mysql_fetch_assoc($q))
{
echo '<div class="'.($i%2 ? "p_m":"p_m" ).'">';
$t = mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `id_category` = '$post[id]' LIMIT 1"),0);
echo "
<img src='/modules/videoyou/img/$post[icon]' alt='*' />
 <a href='/videoyou$post[id]'>$post[name]</a>
 (" . $t . ") \n";
if (isset($user) && $user['level']>1 )
echo "
<a href='?add&amp;edit=$post[id]'><img src='/modules/videoyou/img/38.gif' alt='*'></a> 
<a href='?delete=$post[id]'><img src='/modules/videoyou/img/35.png' alt='*'></a>
";
?></div><?
$i++;
}
if (isset($user) && $user['level']>3)
{
if (isset($_GET['add']) && isset($_GET['edit']))
{
$post=mysql_fetch_assoc(mysql_query("SELECT * FROM `videoyou_category` WHERE `id` = '".intval($_GET['edit'])."' LIMIT 1"));
echo "<form method='post' action='?add&amp;edit=".intval($_GET['edit'])."'>";
echo "<div class='p_m'>
<input type='text' name='name' value='$post[name]' maxlength='99' />Название категории
</div>
";
$icon=array();
$opendiricon=opendir(H.'/modules/videoyou/img/');
while ($icons=readdir($opendiricon))
{
if (preg_match('#^\.|default.png#',$icons))continue;
$icon[]=$icons;
}
closedir($opendiricon);
echo "<div class='p_m'>";
echo "<select name='icon'>\n";
echo "<option value='default.png'>По умолчанию</option>\n";
for ($i=0;$i<sizeof($icon);$i++)
{
echo "<option value='$icon[$i]'>$icon[$i]</option>\n";
}
echo "</select>Иконка<br />";
echo "<input type='submit' name='save' value='Изменить' /></form></div>";
}
elseif (isset($_GET['add']))
{
echo "<form method='post' action='?add'>";
echo "<input type='text' name='name' value='' maxlength='64' />";
$icon=array();
$opendiricon=opendir(H.'/modules/videoyou/img/');
while ($icons=readdir($opendiricon))
{
if (preg_match('#^\.|default.png#',$icons))continue;
$icon[]=$icons;
}
closedir($opendiricon);
echo "Иконка:<br />\n";
echo "<select name='icon'>\n";
echo "<option value='default.png'>По умолчанию</option>\n";
for ($i=0;$i<sizeof($icon);$i++)
{
echo "<option value='$icon[$i]'>$icon[$i]</option>\n";
}
echo "</select><br />\n";
echo "<input type='submit' name='save' value='Создать' /></form>";
}else{
echo "<div class='foot'>";
echo "<a href='?add'><img src='/modules/videoyou/img/23.png' alt='*'> Добавить категорию</a>\n";
echo "</div>";
echo "<div class='foot'>";
$k_p=mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou`",$db), 0);
$k_n= mysql_result(mysql_query("SELECT COUNT(*) FROM `videoyou` WHERE `time` > '".(time()-86400)."'",$db), 0);
if ($k_n==0)$k_n=NULL;
else $k_n='/+'.$k_n;
echo "<img src='/modules/videoyou/img/32.png' alt='*'> всего видео $k_p$k_n <br /> ";
echo "</div>";
if (isset($user) && $user['level']>1)
{
if (isset($_GET['act']) && $_GET['act']=='create')
{
echo "<form method=\"post\" class='foot' action=\"?\">\n";
echo "Будут удалены видео, добавленые ... тому назад<br />\n";
echo "<input name=\"write\" value=\"12\" type=\"text\" size='3' />\n";
echo "<select name=\"write2\">\n";
echo "<option value=\"\">       </option>\n";
echo "<option value=\"mes\">Месяцев</option>\n";
echo "<option value=\"sut\">Суток</option>\n";
echo "</select><br />\n";
echo "<input value=\"Очистить\" type=\"submit\" /><br />\n";
echo "<a href=\"?\">Отмена</a><br />\n";
echo "</form>\n";
}
echo "<div class=\"foot\">\n";
echo "<a href=\"?act=create\"><img src='/modules/videoyou/img/30.gif' alt='*'> Очистить видео записи по времени </a><br />\n";
echo "</div>\n";
}
}
}
}
include_once $JumanG.'/sys/inc/tfoot.php';
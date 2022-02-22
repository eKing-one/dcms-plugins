<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/shif.php';
include_once '../sys/inc/user.php';
if(!isset($user))header("Location: /aut.php");
$opendiricon=opendir(H.'style/icons');
while ($icons=readdir($opendiricon))
{
// запись всех тем в массив
if (ereg('^\.|default.png',$icons))continue;
$icon[]=$icons;
}
closedir($opendiricon);
if(isset($_GET['admin']) && $user['level']>0)
{
$set['title']='Нижние ссылки(админка)';
include_once '../sys/inc/thead.php';
title();
aut();
err();

include_once 'style.php';


if(isset($_GET['new']))
{
if(isset($_GET['ok']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
$sname=esc(stripcslashes(htmlspecialchars($_POST['sname'])));
$url=esc(stripcslashes(htmlspecialchars($_POST['url'])));

$icon=eregi_replace('[^a-z0-9 _\-\.]', null, $_POST['icon']);
mysql_query("INSERT INTO `links_niz` (`name`, `sname`, `url`, `icon`) VALUES ('$name', '$sname', '$url', '$icon')");
$_SESSION['new']=1;
header("location: ?admin");
}
else
{
echo "<form method='post' action='?admin&new&ok' >\n";
echo "<img src='img/img.png' /> Название:<br /><input name=\"name\" type=\"text\" value='' /><br />\n";echo "<img src='img/img.png' /> Сокращение:<br /><input name=\"sname\" type=\"text\" value='' /><br />\n";echo "<img src='img/img.png' /> Ссылка:<br /><input name=\"url\" type=\"text\" value='/' /><br />\n";echo "<img src='img/img.png' /> Иконка:<br />\n";
echo "<select name='icon'>\n";
echo "<option value='default.png'>По умолчанию</option>\n";
for ($i=0;$i<sizeof($icon);$i++)
{
echo "<option value='$icon[$i]'>$icon[$i]</option>\n";
}
echo "</select><br />\n";
echo "<input value='Добавить' type='submit' name='ok' /><br />\n";
echo "</form>\n";
}
echo "<div class='backlink'><img src='img/back.png' /> <a href='?admin'>Назад</a></div>\n";
}
elseif(isset($_GET['edit']))
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz` WHERE `id` = '$_GET[edit]' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz` WHERE `id` = '$link[id]' LIMIT 1"),0)!=0)
{
if(isset($_GET['ok']))
{
$name=esc(stripcslashes(htmlspecialchars($_POST['name'])));
$sname=esc(stripcslashes(htmlspecialchars($_POST['sname'])));
$url=esc(stripcslashes(htmlspecialchars($_POST['url'])));
$icon=eregi_replace('[^a-z0-9 _\-\.]', null, $_POST['icon']);
mysql_query("UPDATE `links_niz` SET `name` = '$name', `sname` = '$sname', `url` = '$url', `icon` = '$icon' WHERE `id` = '$link[id]' LIMIT 1");
$_SESSION['edit']=1;
header("location: ?admin");
}
else
{
echo "<form method='post' action='?admin&edit=$link[id]&ok'>\n";
echo "<img src='img/img.png' /> Название:<br />\n";
echo "<input type='text' name='name' value=\"$link[name]\" /><br />\n";
echo "<img src='img/img.png' /> Сокращение:<br />\n";
echo "<input type='text' name='sname' value=\"$link[sname]\" /><br />\n";
echo "<img src='img/img.png' /> Ссылка:<br />\n";
echo "<input type='text' name='url' value='$link[url]' /><br />\n";
echo "<img src='img/img.png' /> Иконка:<br />\n";
echo "<select name='icon'>\n";
echo "<option value='default.png'>По умолчанию</option>\n";
for ($i=0;$i<sizeof($icon);$i++)
{
echo "<option value='$icon[$i]'".($link['icon']==$icon[$i]?" selected='selected'":null).">$icon[$i]</option>\n";
}
echo "</select><br />\n";
echo "<input type='submit' value='Сохранить' name='ok' /><br /></form>\n";
}
echo "<div class='backlink'><img src='img/back.png' /> <a href='?admin'>Назад</a></div>\n";
}
else
{
echo "<div class='err'><img src='img/err.png' /> Обект не найден!</div>\n";
echo "<div class='backlink'><img src='img/back.png' /> <a href='?admin'>Назад</a></div>\n";
}
}
elseif(isset($_GET['del']))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz` WHERE `id` = '$_GET[del]' LIMIT 1"),0)==0)
{
echo "<div class='err'><img src='img/err.png' /> Обект не найден!</div>\n";
echo "<div class='backlink'><img src='img/back.png' /> <a href='?admin'>Назад</a></div>\n";
}
else
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz` WHERE `id` = '$_GET[del]' LIMIT 1"));
if(isset($_GET['ok']))
{
mysql_query("DELETE FROM `links_niz` WHERE `id` = '$link[id]'");
mysql_query("DELETE FROM `links_niz_user` WHERE `id_link` = '$link[id]'");
$_SESSION['del']=1;
header("location: ?admin");
}
else
{
echo "<img src='img/err.png' /> Вы уверены, что хотите удалить ету ссылку?<br />\n";
echo "<small> <a href='?admin&del=$link[id]&ok'>Да</a></small>\n";
echo " <small> <a href='?admin'>Нет</a></small><br/>\n";
}
echo "<div class='backlink'><img src='img/back.png' /> <a href='?admin'>Назад</a></div>\n";
}
}
else
{
$new=$_SESSION['new'];
if($new==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно добавлена</div>\n";
$_SESSION['new']=0;
}
$edit=$_SESSION['edit'];
if($edit==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно отредактирована</div>\n";
$_SESSION['edit']=0;
}
$del=$_SESSION['del'];
if($del==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно удалена</div>\n";
$_SESSION['del']=0;
}
echo "<div class='backlink'><img src='img/back.png' /> <a href='?'>Назад</a></div>\n";
echo "<img src='img/add.png' /> <a href='?admin&new'><span style='color:#79358c'>Добавить ссылку</span></a><br/>";
$links = mysql_query("SELECT * FROM `links_niz` ORDER BY `name` DESC");
if (mysql_num_rows($links)==0)
{
echo "   <tr>\n";
echo "<img src='img/err.png' /> Нету ссылок</b>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_array($links))
{
if($num==1)
{
echo "<div style='padding:1px;background-color:#edeff4;border-top:1px dotted #CCC'>";
$num=0;
}
else
{
echo "<div 
style='padding:1px;background-color:#fff;border-top:1px dotted #CCC'>";
$num=1;
}
echo "<img src='/style/icons/$post[icon]' /> <b>$post[name]</b> - $post[sname] ($post[url]) \n";
echo "&nbsp;&nbsp;<a class='icolink' 
href='?admin&del=$post[id]' title='удалить' style='color:red;font-size:small'><b>x</b></a>\n";
echo " | <a class='icolink' href='?admin&edit=$post[id]' title='редактировать' style='color:green;font-size:small'><b>ред</b></a>\n";
echo "</div>\n";
}
echo "<div class='backlink'><img src='img/back.png' /> <a href='/umenu.php'>Назад</a></div>\n";
}
}
else
{


$set['title']='Нижние ссылки';
include_once '../sys/inc/thead.php';
title();
aut();
err();
include_once 'style.php';


if(isset($_GET['add']))
{
if(isset($_GET['link']))
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz` WHERE `id` = '$_GET[link]' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz` WHERE `id` = '$link[id]' LIMIT 1"),0)==0)
{
echo "<div class='err'><img src='img/err.png' /> Обект не найден!</div>\n";
}
else
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz_user` WHERE `id_link` = '$link[id]' AND `id_user` = '$user[id]' LIMIT 1"),0)==0)
{
$pos=mysql_result(mysql_query("SELECT MAX(`pos`) FROM `links_niz_user` WHERE `id_user` = '$user[id]'"), 0)+1;
mysql_query("INSERT INTO `links_niz_user` (`id_link`, `id_user`, `pos`) VALUES ('$link[id]', '$user[id]', '$pos')");
$_SESSION['new_user']=1;
}
header("location: /links");
}
}
else
{
echo "<div class='backlink'><img src='img/back.png' /> <a href='?'>Назад</a></div>\n";
echo "<b>Выберите из списка:</b><br/>\n";
$links = mysql_query("SELECT * FROM `links_niz` ORDER BY `name` DESC");
if (mysql_num_rows($links)==0)
{
echo "   <tr>\n";
echo "<img src='img/err.png' /> Нету ссылок</b>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_array($links))
{
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz_user` WHERE `id_link` = '$post[id]' AND `id_user` = '$user[id]' LIMIT 1"),0)==0)
{
echo "<img src='/style/icons/$post[icon]' /> <a href='?add&link=$post[id]'><b>$post[name]</b> - $post[sname]</a><br/>\n";
}
}
echo "<b>Добавте свою:</b><br/>\n";
if(isset($_GET['my']))
{
$link_name=esc(stripcslashes(htmlspecialchars($_POST['link_name'])));
$link=esc(stripcslashes(htmlspecialchars($_POST['link'])));
$icon=eregi_replace('[^a-z0-9 _\-\.]', null, $_POST['icon']);
if(strlen2($link_name)>6)
{
$err='';
echo "<div class='err'><img src='img/err.png' /> Слишком длинное название</div>\n";
}
if(strlen2($link_name)<2)
{
$err='';
echo "<div class='err'><img src='img/err.png' /> Слишком короткое название</div>\n";
}
if(!isset($err))
{
$pos=mysql_result(mysql_query("SELECT MAX(`pos`) FROM `links_niz_user` WHERE `id_user` = '$user[id]'"), 0)+1;
mysql_query("INSERT INTO `links_niz_user` (`link`, `link_name`, `icon`, `pos`, `id_user`) VALUES ('$link', '$link_name', '$icon', '$pos', '$user[id]')");
$_SESSION['new_my']=1;
header("location: ?");
}
}
echo "<form method='post' action='?add&my' >\n";
echo "<img src='img/img.png' /> Название:(2-6 символов)<br /><input name=\"link_name\" type=\"text\" value='' /><br />\n";echo "<img src='img/img.png' /> 
Ссылка:<br /><input name=\"link\" type=\"text\" value='' /><br />\n";echo "<img src='img/img.png' /> Иконка:<br />\n";
echo "<select name='icon'>\n";
echo "<option value='default.png'>По умолчанию</option>\n";
for ($i=0;$i<sizeof($icon);$i++)
{
echo "<option value='$icon[$i]'>$icon[$i]</option>\n";
}
echo "</select><br />\n";
echo "<input value='Добавить' type='submit' name='ok' /><br />\n";
echo "</form>\n";
echo "<div class='backlink'><img src='img/back.png' /> <a href='/umenu.php'>Назад</a></div>\n";
}
}
elseif (isset($_GET['up']))
{
$link=mysql_fetch_assoc(mysql_query("SELECT * FROM `links_niz_user` WHERE `id` = '".intval($_GET['up'])."' AND `id_user` = '$user[id]' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz_user` WHERE `pos` < '$link[pos]' AND `id_user` = '$user[id]' LIMIT 1"),0)!=0)
{
mysql_query("UPDATE `links_niz_user` SET `pos` = '".($link['pos'])."' WHERE `pos` = '".($link['pos']-1)."' AND `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `links_niz_user` SET `pos` = '".($link['pos']-1)."' WHERE `id` = '".intval($_GET['up'])."' AND `id_user` = '$user[id]' LIMIT 1");
$_SESSION['up']=1;
header("location: /links");
}
else header("Location: /links");
}
elseif (isset($_GET['down']))
{
$link=mysql_fetch_assoc(mysql_query("SELECT * FROM `links_niz_user` WHERE `id` = '".intval($_GET['down'])."' AND `id_user` = '$user[id]' LIMIT 1"));
if(mysql_result(mysql_query("SELECT COUNT(*) FROM `links_niz_user` WHERE `pos` > '$link[pos]' AND `id_user` = '$user[id]' LIMIT 1"),0)!=0)
{
mysql_query("UPDATE `links_niz_user` SET `pos` = '".($link['pos'])."' WHERE `pos` = '".($link['pos']+1)."' AND `id_user` = '$user[id]' LIMIT 1");
mysql_query("UPDATE `links_niz_user` SET `pos` = '".($link['pos']+1)."' WHERE `id` = '".intval($_GET['down'])."' AND `id_user` = '$user[id]' LIMIT 1");
$_SESSION['down']=1;
header("location: /links");
}
else header("Location: /links");
}
elseif(isset($_GET['del']))
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz_user` WHERE `id` = '$_GET[del]' AND `id_user` = '$user[id]' LIMIT 1"));
$links = mysql_query("SELECT * FROM `links_niz_user` WHERE `id_user` = '$user[id]' AND `pos` > '$link[pos]' ORDER BY `pos` ASC");
while ($p = mysql_fetch_array($links))
{
mysql_query("UPDATE `links_niz_user` SET `pos` = '".($p['pos']-1)."' WHERE `id` = '$p[id]' LIMIT 1");
}
mysql_query("DELETE FROM `links_niz_user` WHERE `id` = '$link[id]' AND `id_user` = '$user[id]'");
$_SESSION['del_user']=1;
header("Location: ?");
}
elseif(isset($_GET['show_icons']))
{
mysql_query("UPDATE `user` SET `show_foot_type` = 'icons' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_icons']=1;
header("Location: ?");
}
elseif(isset($_GET['show_text']))
{
mysql_query("UPDATE `user` SET `show_foot_type` = 'text' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_text']=1;
header("Location: ?");
}
elseif(isset($_GET['show_on']))
{
mysql_query("UPDATE `user` SET `show_foot` = 'on' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_on']=1;
header("Location: ?");
}
elseif(isset($_GET['show_off']))
{
mysql_query("UPDATE `user` SET `show_foot` = 'off' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_off']=1;
header("Location: ?");
}
elseif(isset($_GET['show_left']))
{
mysql_query("UPDATE `user` SET `foot_sit` = 'left' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_left']=1;
header("Location: ?");
}
elseif(isset($_GET['show_center']))
{
mysql_query("UPDATE `user` SET `foot_sit` = 'center' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_center']=1;
header("Location: ?");
}
elseif(isset($_GET['show_right']))
{
mysql_query("UPDATE `user` SET `foot_sit` = 'right' WHERE `id` = '$user[id]' LIMIT 1");
$_SESSION['show_right']=1;
header("Location: ?");
}
else
{
$new_user=$_SESSION['new_user'];
if($new_user==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно добавлена</div>\n";
$_SESSION['new_user']=0;
}
$new_my=$_SESSION['new_my'];
if($new_my==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно добавлена</div>\n";
$_SESSION['new_my']=0;
}
$up=$_SESSION['up'];
if($up==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка сдвинута вверх</div>\n";
$_SESSION['up']=0;
}
$down=$_SESSION['down'];
if($down==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка сдвинута вниз</div>\n";
$_SESSION['down']=0;
}
$del_user=$_SESSION['del_user'];
if($del_user==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Ссылка успешно удалена</div>\n";
$_SESSION['del_user']=0;
}
$show_icons=$_SESSION['show_icons'];
if($show_icons==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Включен показ ссылок ввиде иконок</div>\n";
$_SESSION['show_icons']=0;
}
$show_text=$_SESSION['show_text'];
if($show_text==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Включен показ ссылок ввиде текста</div>\n";
$_SESSION['show_text']=0;
}
$show_on=$_SESSION['show_on'];
if($show_on==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Показ нижних ссылок успешно включен</div>\n";
$_SESSION['show_on']=0;
}
$show_off=$_SESSION['show_off'];
if($show_off==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Показ нижних ссылок успешно выключен</div>\n";
$_SESSION['show_off']=0;
}
$show_left=$_SESSION['show_left'];
if($show_left==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Включен показ ссылок слева</div>\n";
$_SESSION['show_left']=0;
}
$show_center=$_SESSION['show_center'];
if($show_center==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Включен показ ссылок в центре</div>\n";
$_SESSION['show_center']=0;
}
$show_right=$_SESSION['show_right'];
if($show_right==1)
{
echo "<div style='border-bottom: 1px #4050C0 solid;border-top: 1px #4050C0 solid;border-left: 1px #4050C0 solid;border-right: 1px #4050C0 solid;background: #70D0F0;color: #000;
font-size: 13px;text-align: center;'><img src='img/ok.png' /> Включен показ ссылок справа</div>\n";
$_SESSION['show_right']=0;
}
if($user['level']>0)echo "<div class='busi'><img src='img/img.png' alt=''/> <a href='?admin'>Панель управления</a></div>\n";
echo "<img src='img/add.png' /> <a href='?add'><span style='color:#79358c'>Добавить ссылку</span></a><div class='block_hr'></div>";
$links = mysql_query("SELECT * FROM `links_niz_user` WHERE `id_user` = '$user[id]' ORDER BY `pos` ASC");
if (mysql_num_rows($links)==0)
{
echo "   <tr>\n";
echo "<img src='img/err.png' /> Нету ссылок</b>\n";
echo "   </tr>\n";
}
while ($post = mysql_fetch_array($links))
{
$link=mysql_fetch_array(mysql_query("SELECT * FROM `links_niz` WHERE `id` = '$post[id_link]' LIMIT 1"));
echo "<a href='?up=$post[id]'><img src='img/up.png' /></a> \n";
echo "<a href='?down=$post[id]'><img src='img/down.png' /></a> \n";
if($post['link']=='0')echo "<b>$link[name]</b> - $link[sname] | <img src='/style/icons/$link[icon]' />\n";
else echo "<b>$post[link_name]</b> | <img src='/style/icons/$post[icon]' />\n";
echo " <span style='font-size:small'>[<a href='?del=$post[id]'><span style='color:red'><b>x</b></span></a>]</span><br/>\n";
}
echo "<div class='block_hr'></div>".($user['show_foot']=='on'?'<a href="?show_off" style="color:red">Выключить нижнее меню</a>':'<a href="?show_on" style="color:green">Включить нижнее меню</a>')."<br/>\n";
echo "<span style='color:#79358c'>Показ ссылок:</span> ".($user['show_foot_type']=='icons'?'Графично':'<a href="?show_icons">Графично</a>')." | ".($user['show_foot_type']=='text'?'Текст':'<a href="?show_text">Текст</a>')."<br/>\n";

echo "<span style='color:#79358c'>Расположение нижнего меню:</span> ".($user['foot_sit']=='left'?'Слева':'<a href="?show_left">Слева</a>')." | ".($user['foot_sit']=='center'?'В центре':'<a href="?show_center">В центре</a>')." | ".($user['foot_sit']=='right'?'Справа':'<a href="?show_right">Справа</a>')."\n";
echo "<div class='backlink'><img src='img/back.png' /> <a href='/umenu.php'>Назад</a></div>\n";
}
}
include_once '../sys/inc/tfoot.php';
?>
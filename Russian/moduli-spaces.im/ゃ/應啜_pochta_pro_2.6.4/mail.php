<?
include_once 'sys/inc/start.php';
include_once 'sys/inc/compress.php';
include_once 'sys/inc/sess.php';
$comp = $_COOKIE['comp'];
include_once 'sys/inc/home.php';
include_once 'sys/inc/settings.php';
include_once 'sys/inc/db_connect.php';
include_once 'sys/inc/ipua.php';
include_once 'sys/inc/fnc.php';
include_once 'sys/inc/user.php';
//only_reg();
if (!$user){msg('Вы не зарегистрированы!');exit();}

/*if (!$_COOKIE['colvokomm'])
{
$_COOKIE['colvokomm'] = 8;
header('Location: /mail.php?id='.intval($_GET['id']));
}*/

if ($_COOKIE['comp'] == 1)$adsvv = 'телефона';
else
$adsvv = 'компьютера';

$comp = $_COOKIE['comp'];

$_SESSION['plus'] = '';
if ((!isset($_SESSION['refer']) || $_SESSION['refer']==NULL)
&& isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!=NULL &&
!ereg('mail\.php',$_SERVER['HTTP_REFERER']))
$_SESSION['refer']=str_replace('&','&amp;',ereg_replace('^http://[^/]*/','/', $_SERVER['HTTP_REFERER']));


if (!isset($_GET['id'])){header("Location: /konts.php?".SID);exit;}
$ank=get_user($_GET['id']);
if (!$ank){header("Location: /konts.php?".SID);exit;}


$set['title']='Почта: '.$ank['nick'];
include_once 'sys/inc/thead.php';
title();


// добавляем в контакты
if ($user['add_konts']==2 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"),0)==0)
mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$user[id]', '$ank[id]', '$time')");
// обновление сведений о контакте
mysql_query("UPDATE `users_konts` SET `new_msg` = '0' WHERE `id_kont` = '$ank[id]' AND `id_user` = '$user[id]' LIMIT 1");
// помечаем сообщения как прочитанные
mysql_query("UPDATE `mail` SET `read` = '1' WHERE `id_kont` = '$user[id]' AND `id_user` = '$ank[id]'");
mysql_query("DELETE FROM `mail` WHERE `msg` = 'NOWWRTING' AND `time` < '".(time()-3)."'");

if (isset($_POST['msg']) && $ank['id']!=0)
{


$msg=trim($_POST['msg']);
if (isset($_POST['translit']) && $_POST['translit']==1)$msg=translit($msg);
if (strlen2($msg)>2048)$err[]='Сообщение превышает 2048 символа';
if (trim($msg) == '')$err[]='Пустое сообщение!';
$mat=antimat($msg);
if ($mat)$err[]='В тексте сообщения обнаружен мат: '.$mat;

if (!isset($err) && mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' AND `time` > '".($time-3)."' AND `msg` = '".my_esc($msg)."'"),0)==0)
{
// отправка сообщения
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values('$user[id]', '$ank[id]', '".my_esc($msg)."', '$time')");
$ankkkk=get_user($ank['id']);
// добавляем в контакты
if ($user['add_konts']==1 && mysql_result(mysql_query("SELECT COUNT(*) FROM `users_konts` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]'"),0)==0)
mysql_query("INSERT INTO `users_konts` (`id_user`, `id_kont`, `time`) VALUES ('$user[id]', '$ank[id]', '$time')");
// обновление сведений о контакте
mysql_query("UPDATE `users_konts` SET `time` = '$time' WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]'");
msg('Сообщение успешно отправлено');
$jkadhasd = array('Привет, как дела?', 'Ололо!', 'Привет!', 'ЫЫЫ Тест!', 'Как дела?', 'Я тебя люблю!', 'Хм, что-то не то.', 'ЭТО РАНДОМ!', 'Это рандом, детка!');
header("Location: /mail.php?id=$mailid");
}
}

aut();
err();

echo <<<m
<div id="propose" style="display:none;position:fixed;left:0;bottom:0;opacity:0.9">
<span id="uvt" style="position:fixed;left:0;top:220px;padding:10px;background:#F2F4FE;color:navy;width:180px;text-align:center;border:1px solid navy;">
Пустое уведомление.
</span>
</div>
m;

if (isset($_FILES['file']))
{
$f = fopen('mfiles/id.dat', 'r');
$id = fread($f, filesize('mfiles/id.dat'));
fclose($f);
if (!move_uploaded_file($_FILES['file']['tmp_name'], 'mfiles/'.$id.'.dat'))
{
die('Ошибка');
}
else
{
$f = fopen('mfiles/id.dat', 'w+');
fwrite($f, ($id + 1));
fclose($f);
$f = fopen('mfiles/'.$id.'.name', 'w+');
fwrite($f, $_FILES['file']['name']);
fclose($f);
$type=$_FILES['file']['type'];
$f = fopen('mfiles/'.$id.'.type', 'w+');
fwrite($f, $type);
fclose($f);
mysql_query("INSERT INTO `mail` (`id_user`, `id_kont`, `msg`, `time`) values ('$user[id]', '".intval($_GET['id'])."', 'Файл: [url=/mdown.php?id=$id]".my_esc($_FILES['file']['name'])."[/url]', '$time')");
}
}

if (isset($_GET['changestatus']))
{
if ($_COOKIE['comp'] == 1)setcookie("comp", "0");
else
setcookie("comp", "1");
header("Location: /mail.php?id=$mailid");
}

if (isset($_GET['webmail']))$loo = 'onsubmit';

if (($ank['date_last'] + 400) > time())$ons = '<b><span style="color:green">Онлайн</span></b>';
else
$ons = '<b><span style="color:red">Оффлайн</span></b>';

if ($ank['id']!=0){

if ($comp != 1)
{
echo '<div class="p_m" >'; //style=\'background:#ddffdd\'
echo "<a href='?id=".intval($_GET['id'])."'>Обновить</a> <b>[<a href='?id=$mailid&changestatus'>Я с $adsvv</a>]</b>
<br />
<form method='post' action='/mail.php?id=$ank[id]&amp;$passgen'>\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "Сообщение:<br />\n<textarea name='msg'></textarea><br />\n";
echo "<input type='submit' value='Отправить' /> &nbsp; &nbsp; <span id='onsm'></span>\n";
echo "</form>\n";
echo "</div>\n";
}
else
{//background:black;padding:4px
//echo "<style>.smile:hover{background:black;padding:4px};.smile{padding:4px}</style>";
echo('<center>
<table width="100%" border="0" class="msg">
<tr width="100%" class="msg">
<td valign="center" onclick="addsmile(\':-)\')" align="center">
<img class="smile" src="/style/smiles/smile.gif" alt=":-)" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\':-(\')" align="center">
<img class="smile" src="/style/smiles/sad.gif" alt=":-(" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\':wink:\')" align="center">
<img class="smile" src="/style/smiles/wink.gif" alt=":wink:" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\':-P\')" align="center">
<img class="smile" src="/style/smiles/blum3.gif" alt=":-P" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\'*ROFL*\')" align="center">
<img src="/style/smiles/rofl.gif" alt="*ROFL*" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\':-D\')" align="center">
<img class="smile" src="/style/smiles/biggrin.gif" alt=":-D" />&nbsp;
</td>
<td valign="center" onclick="addsmile(\':-[\')" align="center">
<img class="smile" src="/style/smiles/blush2.gif" alt=":-[" />&nbsp;
</td>
</tr>
</table>
</center>');
echo '<div class="p_m">';//style=\'background:#ddffdd\'
echo "<div style='float:right;text-align:right;position:relative' id='ons'>$ons</div>";
echo "<form id='submitform' method='post' action='/mail.php?id=$ank[id]&amp;$passgen'>\n";
if ($set['web'] && is_file(H.'style/themes/'.$set['set_them'].'/altername_post_form.php'))
include_once H.'style/themes/'.$set['set_them'].'/altername_post_form.php';
else
echo "<textarea name='msg' id='message'  cols='36' rows='4'></textarea>&nbsp;<img id='lols' style='margin-bottom:42px' src='/b.png' onclick='document.getElementById(\"myfile\").click();' /><br />\n";
echo "</form>\n";//onKeyPress=\"return submitenter(this,event)\"
echo "</div>\n";
echo <<<sss
<div style="display:none">
<form id='sendfile' enctype='multipart/form-data' method='POST' target='fam'><input id='myfile' type='file' name='file' /><input type='hidden' name='MAX_FILE_SIZE' value='100000000' /></form>
<iframe onload='if(a==1){document.getElementById("lols").src = "/ok.png";document.getElementById("lols").onclick = function(){ newfile();return true; }}else{a=1;}' src='about:blank' name='fam' frameborder='0'></iframe>
</div>
 
<script type="text/javascript">
function addsmile(smile)
{
document.getElementById("message").value = document.getElementById("message").value + ' ' + smile + ' ';
document.getElementById("message").focus;
document.getElementById("message").focus;
}
</script>

<script type="text/javascript">
function validateSize(fileInput) {
  var fileObj, size;
  if ( typeof ActiveXObject == "function" ) { // IE
    fileObj = (new ActiveXObject("Scripting.FileSystemObject")).getFile(fileInput.value);
  }else {
    fileObj = fileInput.files[0];
  }
  size = fileObj.size;
}
jQuery('#myfile').change(function () {
  document.forms[1].submit();
});
function newfile()
{
document.getElementById("lols").src = '/b.png';
document.getElementById("lols").onclick = function(){document.getElementById("myfile").click();}
}
</script>
sss;
}

}
$_COOKIE['colvokomm'] = 8;
?>
<SCRIPT TYPE="text/javascript">
document.getElementById('message').onkeydown = function(e)
{
var keycode = e.which;

if ((keycode == 13) && !(e.ctrlKey) && !(e.shiftKey))
{
//document.forms[0].submit();
$.ajax({url: "/mail.php?id=<?=intval($_GET['id'])?>", data: {"msg" : document.getElementById('message').value}, type: "POST", cache: false});
this.value = '';
return false;
}
if ((keycode == 13) && (e.ctrlKey))
{
this.value += '\r\n';
return false; 
}
if ((keycode == 13) && (e.shiftKey))
{
return false; 
}
else
{
return true;
}
}
</SCRIPT>
<script type="text/javascript">
function addHandler(object, event, handler, useCapture) {
    if (object.addEventListener) {
        object.addEventListener(event, handler, useCapture ? useCapture : false);
    } else if (object.attachEvent) {
        object.attachEvent('on' + event, handler);
    } else alert("Add handler is not supported");
}
// Добавляем обработчики
/* Gecko */
addHandler(window, 'DOMMouseScroll', wheel);
/* Opera */
addHandler(window, 'mousewheel', wheel);
/* IE */
addHandler(document, 'mousewheel', wheel);
// Обработчик события
b = <?=$_COOKIE['colvokomm']?>;
function wheel(event) {
    var delta; // Направление скролла
    // -1 - скролл вниз
    // 1  - скролл вверх
    event = event || window.event;
    // Opera и IE работают со свойством wheelDelta
    if (event.wheelDelta) {
        delta = event.wheelDelta / 120;
        // В Опере значение wheelDelta такое же, но с противоположным знаком
        if (window.opera) delta = -delta;
    // В реализации Gecko получим свойство detail
    } else if (event.detail) {
        delta = -event.detail / 3;
    }
    // Запрещаем обработку события браузером по умолчанию
    //if (event.preventDefault)  event.preventDefault();
    //event.returnValue = false;
   	b = b - delta;
	if (b < 7)b = 7;
    document.cookie = 'colvokomm=' + b + '; path=/;';
}
</script>
<?php
echo "<div id='mailid'>";
echo "<table class='post'>\n";
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
if ($k_post==0)
{
echo "   <tr>\n";
echo "  <td class='p_t'>\n";
echo "Нет сообщений\n";
echo "  </td>\n";
echo "   </tr>\n";

}

if (isset($_GET['del']))
{
$q = mysql_fetch_assoc(mysql_query("SELECT * FROM `mail` WHERE `id` = '".my_esc($_GET['del'])."'"));
if ($q['id_kont'] == $user['id'])$oloj = 'kont';
if ($q['id_user'] == $user['id'])$oloj = 'user';
if ($oloj)mysql_query("UPDATE `mail` SET `vidno$oloj` = '0' WHERE `id` = '".my_esc($_GET['del'])."'");
mysql_query("OPTIMIZE TABLE `mail`");
}

$q=mysql_query("SELECT * FROM `mail` WHERE `id_user` = '$user[id]' AND `id_kont` = '$ank[id]' OR `id_user` = '$ank[id]' AND `id_kont` = '$user[id]' AND `msg` <> 'NOWWRTING' ORDER BY id DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q))
{
echo '<tr>';
$ank2=get_user($post['id_user']);
$lolka = $post['vidnokont'];
$lolka2 = $post['vidnouser'];
if ((($lolka == 1) && ($ank2['id'] != $user['id'])) || (($lolka2 == 1) && ($ank2['id'] == $user['id'])))
{
echo "  <td class='p_t' width='10'>\n";
if ($ank2)
{
if ($ank2['id'] != $user['id'])
echo "<a href=\"/info.php?id=$ank2[id]\">$ank2[nick]</a>\n";
else
echo "<a href=\"/info.php?id=$ank2[id]\"><b><font color='green'>$ank2[nick]</font></b></a>\n";
}
else
echo "[DELETED] (+$kont[count])\n";
echo "  </td>\n";
/*echo "   </tr>\n";


echo "   <tr>\n";*/
if ($set['set_show_icon']==1)echo "  <td class='p_m' colspan='2'>\n"; else echo "  <td class='p_m'>\n";
if ($post['read']==0)echo "<font color='red'>\n";
else
echo "<font color='black'>\n";
echo output_text($post['msg'])."</font>\n";
echo "<div style='text-align: right;color:navy;float:right'>".vremja($post['time'])." <a href='?id=$mailid&del=$post[id]'>[<font color='red'>X</font>]</a></div>\n";
echo "  </td>\n";
echo "   </tr>\n";





}
}
echo "</table>\n";
if ($k_page>1)str("mail.php?id=$ank[id]&amp;",$k_page,$page); // Вывод страниц
echo "</div>";

include_once 'sys/inc/tfoot.php';
?>
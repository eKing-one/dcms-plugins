<?
include_once '../sys/inc/start.php';
if(isset($_GET['showinfo']) || !isset($_GET['f']) || isset($_GET['komm']))

include_once '../sys/inc/compress.php';
include_once '../sys/inc/downloadfile.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';

if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']  || isset($user) && $user['level']>0)
{

if(isset($_GET['d']) && esc($_GET['d'])!=NULL){
$l=preg_replace("#\.{2,}#",NULL,esc(urldecode($_GET['d'])));
$l=preg_replace("#\./|/\.#",NULL,$l);
$l=preg_replace("#(/){1,}#","/",$l);
$l='/'.preg_replace("#(^(/){1,})|((/){1,}$)#","",$l);
}else{
$l='/';
}

if($l=='/'){
$dir_id['upload']=0;
$id_dir=0;
$l='/';
}else if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND (`dir` = '/$l' OR `dir` = '$l/' OR `dir` = '$l') LIMIT 1"),0)!=0){
$dir_id=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND (`dir` = '/$l' OR `dir` = '$l/' OR `dir` = '$l') LIMIT 1"));
$id_dir=$dir_id['id'];
}else{
$dir_id['upload']=0;
$id_dir=0;
$l='/';
}

if(isset($_GET['f'])){
$f=esc(urldecode($_GET['f']));
$name=eregi_replace('\.[^\.]*$', NULL, $f);
$ras=strtolower(eregi_replace('^.*\.', NULL, $f));
$ras=str_replace('jad', 'jar', $ras);

if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_dir` = '".$dir_id['id']."' AND `name`='$name' AND `ras` = '$ras' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"),0)!=0){
$file_id=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_dir` = '".$dir_id['id']."' AND `name`='$name' AND `ras` = '$ras' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"));
$ras=$file_id['ras'];
$file=H."sys/gruppy/obmen/files/$file_id[id].dat";
$name=$file_id['name'];
$size=$file_id['size'];

if(!isset($_GET['showinfo']) && !isset($_GET['komm']) && is_file(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat')){

if($ras=='jar' && strtolower(eregi_replace('^.*\.', NULL, $f))=='jad'){
include_once H.'sys/inc/zip.php';
$zip=new PclZip(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat');
$content = $zip->extract(PCLZIP_OPT_BY_NAME, "META-INF/MANIFEST.MF" ,PCLZIP_OPT_EXTRACT_AS_STRING);
$jad=eregi_replace("(MIDlet-Jar-URL:( )*[^(\n|\r)]*)", NULL, $content[0]['content']);
$jad=eregi_replace("(MIDlet-Jar-Size:( )*[^(\n|\r)]*)(\n|\r)", NULL, $jad);
$jad=trim($jad);
$jad.="\r\nMIDlet-Jar-Size: ".filesize(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat')."";
$jad.="\r\nMIDlet-Jar-URL: /gruppy/obmen.php?s=$gruppy[id]&d=$dir_id[dir]&f=$file_id[name].$file_id[ras]";
$jad=br($jad,"\r\n");
header('Content-Type: text/vnd.sun.j2me.app-descriptor');
header('Content-Disposition: attachment; filename="'.$file_id['name'].'.jad";');
echo $jad;
exit;
}



@mysql_query("UPDATE `gruppy_obmen_files` SET `k_loads` = '".($file_id['k_loads']+1)."' WHERE `id` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]' LIMIT 1");
include_once '../sys/inc/downloadfile.php';
DownloadFile(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat', $name.'.'.$ras, ras_to_mime($ras));


exit;
}else if(isset($_GET['komm']) && is_file(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat')){
$set['title']=$gruppy['name'].' - Обменник - Комментарии - '.$file_id['name'];
$_SESSION['page']=1;

include_once '../sys/inc/thead.php';
title();

if(isset($user) && $gruppy['admid']==$user['id'] || $user['id']==$file_id['id_user']){
include 'inc/komm_act.php';
}

include_once 'inc/komm.php';
echo "<div class='foot'>\n";
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;showinfo">К описанию</a><br/>';
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'">В папку</a><br/>';
echo '&#187;&nbsp;<a href="index.php?s='.$gruppy['id'].'">В сообщество</a></div>';
include_once '../sys/inc/tfoot.php';
exit;
}else{
$set['title']=$gruppy['name'].' - Обменник - '.$file_id['name'];

include_once '../sys/inc/thead.php';
title();

if(isset($user) && $gruppy['admid']==$user['id']){
include 'inc/file_act.php';
}

err();
echo "<div class='nav2'>";
if(is_file("inc/file/$ras.php")){
include "inc/file/$ras.php";
}else{
include_once 'inc/file.php';
}

if($file_id['ras']=='jar'){
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.jad">Скачать</a> <a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'">JAR</a> ('.$file_id['k_loads'].')<br/>';
}else{
echo "<div class='mess'>";
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'"><b><u>Скачать</u></b></a> ('.$file_id['ras'].')<br/>';
echo "</div>";
echo '<font color=\"#000000\" size=\"4\">Скачали</font>: <b>'.$file_id['k_loads'].' раз.</b><br/>';
}
echo "</div>";
echo "<div class='nav1'>";
echo '<input type="text" value="http://'.$_SERVER['HTTP_HOST'].'/gruppy/obmen.php?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'"/><br/>';
echo "</div>";
echo "<div class='nav2'>";
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;komm">Комментарии</a> ('.mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_komm` WHERE `id_file` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]'"), 0).')<br/>';
echo "</div>";
if(isset($user) && $gruppy['admid']==$user['id']){
include 'inc/file_form.php';
}
echo "<div class='foot'>";
echo '&#187;&nbsp;<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'">В папку</a><br/>';
echo "</div>";
echo "<div class='foot'>";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a></div>';





include_once '../sys/inc/tfoot.php';
}
}
}else{
include_once 'inc/dir.php';
}
}
else
{
$set['title']=$gruppy['name'].' - Обменник';
include_once '../sys/inc/thead.php';
title();
echo'Вам недоступен просмотр файлообменника данного сообщества';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>

<?
#-----------------------------------------------------#
#          ********* SMART-ZONA *********             #
#             Made by   :  DELOREAN                   #
#               E-mail  :  delorean@smart-zona.org    #
#             WAP-Site  :  http://smart-zona.org      #
#                  ICQ  :  344-789-632                #
#   Вы ИМЕЕТЕ право вносить изменения в код скрипта   #
#-----------------------------------------------------#	
/////////////////////////////////
//////////////Шапка//////////////
/////////////////////////////////
Error_Reporting(E_ALL & ~E_NOTICE);
if(empty($_GET['zip']) and empty($_GET['download']) & empty($_GET['img'])){
list($msec,$sec)=explode(chr(32),microtime()); 
$HeadTime=$sec+$msec; 
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Eraser</title>
<style type="text/css">
body {
font-family:Comic Sans MS;
color:#FF0000;
background-color:#FFFFCC;}
a {
color:#00AA00;
text-decoration:underline;}
a:hover {
color: red;
text-decoration: none; 
position: relative;
top: 1px; left: 1px;}
a:visited {
color: 006600;}
b {
color:#008800;
font-style:bold;}
table{
background-color: #FFFFFF;
border: 1px solid #FF8800;}
td{
margin: 0px;
padding: 0px; 
border: 1px solid #FF8800;
background-color: #FFCE52;
color: #f00; }</style>
</head><body>';}
/////////////////////////////////
//////////Файл менеджер//////////
/////////////////////////////////
if(empty($_GET['r']) & empty($_GET['input']) & empty($_GET['ren']) & empty($_GET['setchmod']) & empty($_GET['download']) & empty($_GET['up']) & empty($_GET['upload']) & empty($_GET['chmod']) & empty($_GET['rename']) & empty($_GET['rmdir']) & empty($_GET['made']) & empty($_GET['create']) & empty($_GET['del']) & empty($_GET['deldir']) & empty($_GET['f']) & empty($_GET['edit']) & empty($_GET['zip'])& empty($_GET['img'])){
echo'<b><font color="#FF0000">Файл менеджер</font></b><hr>';
if(empty($_GET['d'])){$d="./";}
else{$d=$_GET['d'];}
if($d=="./"){$vverh='.'.$d;}
if($d!=="./"){$vverh=$d.'../';}
echo'<a href="?d='.$vverh.'">На уровень вверх</a><br>
<a href="?create='.$d.'">Создать файл здесь</a><br>
<a href="?up='.$d.'">Загрузить файл сюда</a><br>
Вы находитель в каталоге: <font color="#FF0000"><b>'.$d.'</b></font><br>';
echo '<font color="#FF0000">Директории:</font><table>';
$dir = opendir($d);
while($file = readdir($dir)){
if(is_dir($d.'/'.$file)){
if($file != "." && $file != ".."){
$mod=substr(sprintf("%o",fileperms($d.'/'.$file)),-3);
echo'<tr><td width="350"><img src="?img=1" alt=""><a href="?d='.$d.$file.'/">'.$file.'</a></td><td>DIR</td><td>'.$mod.'</td><td><a href="?zip='.$d.$file.'/"><font color="#0000FF">[zip]</font></a></td><td><a href="?deldir='.$d.$file.'/"><font color="#FF0000">[del]</font></a></td><td><a href="?ren='.$d.$file.'/"><font color="#000000">[rename]</font></a></td><td><a href="?chmod='.$d.$file.'/"><font color="#FF3300">[chmod]</font></a></td><td><a href="?rmdir='.$d.$file.'/"><font color="#00FF00">[revome]</font></a></td></tr>';}}}
echo'</table><hr><font color="#FF0000">Файлы:</font><table>';
$dir = opendir($d);
while($file = readdir($dir)){
if(is_file($d.'/'.$file)){
$mod=substr(sprintf("%o",fileperms($d.'/'.$file)),-3);
echo'<tr><td width="350"><img src="?img=2" alt=""><a href="?r='.$d.$file.'">'.$file.'</a></td><td>'; echo round(filesize("$d/$file")/1024,1); echo' кб.</td><td>'.$mod.'</td><td><a href="?f='.$d.$file.'"><font color="#0000FF">[edit]</font></a></td><td><a href="?del='.$d.$file.'"><font color="#FF0000">[del]</font></a></td><td><a href="?ren='.$d.$file.'"><font color="#000000">[rename]</font></a></td><td><a href="?chmod='.$d.$file.'"><font color="#FF3300">[chmod]</font></a></td><td><a href="?download='.$d.$file.'"><font color="#00FF00">[down]</font></a></td></tr>';}}
echo'</table>';}
/////////////////////////////////
/////////Переименование//////////
/////////////////////////////////
if(isset($_GET['ren'])){
echo'<b><font color="#FF0000">Переименование</font></b><hr>';
echo'<form action="?rename='.$_GET['ren'].'" method="post">
<input name="new_name" value="'.$_GET['ren'].'"><br>
<input type="submit" value="Переименовать!">';}
/////////////////////////////////
////////////Картинки/////////////
/////////////////////////////////
if(isset($_GET['img'])){
$images = array("",
"R0lGODlhEwAQALMAAAAAAP///5ycAM7OY///nP//zv/OnPf39////wAAAAAAAAAAAAAAAAAAAAAA".
"AAAAACH5BAEAAAgALAAAAAATABAAAARREMlJq7046yp6BxsiHEVBEAKYCUPrDp7HlXRdEoMqCebp".
"/4YchffzGQhH4YRYPB2DOlHPiKwqd1Pq8yrVVg3QYeH5RYK5rJfaFUUA3vB4fBIBADs=",
"R0lGODlhEwAQAKIAAAAAAP///8bGxoSEhP///wAAAAAAAAAAACH5BAEAAAQALAAAAAATABAAAANJ".
"SArE3lDJFka91rKpA/DgJ3JBaZ6lsCkW6qqkB4jzF8BS6544W9ZAW4+g26VWxF9wdowZmznlEup7".
"UpPWG3Ig6Hq/XmRjuZwkAAA7");
header("Content-type: image/gif");
echo base64_decode($images[$img]);}
/////////////////////////////////
/////////Аплоад файлов///////////
/////////////////////////////////
if(isset($_GET['up'])){
echo'<b><font color="#FF0000">Аплоад файлов</font></b><hr>';
echo'<form method="post" enctype="multipart/form-data">
<input type="hidden" name="upload" value="'.$_GET['up'].'">
<input type="file" name="file"><br>
Сохранить как:<br><input type="text" name="new_name" value=""><br>
<input type="submit" value="Загрузить"></form>';}
/////////////////////////////////
/////////Аплоад файлов///////////
/////////////////////////////////
if(isset($_POST['upload'])){
$new_name=trim($_POST['new_name']);
if(copy($_FILES["file"]["tmp_name"], $_POST['upload'].$new_name)){
echo 'Файл успешно загружен';}
else{echo 'Загрузка файла не удалась!';}}
/////////////////////////////////
////////////Download/////////////
/////////////////////////////////
if(isset($_GET['download'])){
$file = file_get_contents($_GET['download']);
$name = explode("/",$_GET['download']);
$name = $name[count($name)-1];
header('Content-type: text/plain');
header("Content-disposition: attachment; filename=$name");
echo $file;}
/////////////////////////////////
/////////////Chmods//////////////
/////////////////////////////////
if(isset($_GET['chmod'])){
echo'<b><font color="#FF0000">Chmods</font></b><hr>';
$mod=substr(sprintf("%o",fileperms($_GET['chmod'])),-3);
echo'<form action="?setchmod='.$_GET['chmod'].'" method="post">
<input name="chmods" value="'.$mod.'"><br>
<input type="submit" value="Задать chmod!">';}
/////////////////////////////////
/////////////Chmods//////////////
/////////////////////////////////
if(isset($_GET['setchmod'])){
echo'<b><font color="#FF0000">Chmods</font></b><hr>';
$chm = chmod($_GET['setchmod'],'0'.$_POST['chmods']);
if($chm){echo'Chmod '.$_POST['chmods'].' заданы!';}
if(!$chm){echo'Ошмбка задавания chmod '.$_POST['chmods'].'!';}}
/////////////////////////////////
///////Удаление директории///////
/////////////////////////////////
if(isset($_GET['rmdir'])){
echo'<b><font color="#FF0000">Удаление директории</font></b><hr>';
$dir = opendir($_GET['rmdir']);
while($dirs = readdir($dir)){
if(is_dir($_GET['rmdir'].$dirs)){
if($dirs != "." && $dirs != ".."){
$poddir = rmdir($_GET['rmdir'].$dirs);}}}
closedir($dir);
$ddir = rmdir($_GET['rmdir']);
if($ddir){echo'Директория удалена!';}
if(!$ddir){echo'Ошибка удаления!';}
if($poddir){echo'<br>Поддиректории удалены!';}
if(!$poddir){echo'<br>Ошибка удаления поддиректорий!';}}
/////////////////////////////////
////////Переименование///////////
/////////////////////////////////
if(isset($_GET['rename'])){
echo'<b><font color="#FF0000">Переименование</font></b><hr>';
$name = rename($_GET['rename'],$new_name);
if($name){echo'Переименовано!';}
if(!$name){echo'Ошибка переименования!';}}
/////////////////////////////////
//////////Чтение файла///////////
/////////////////////////////////
if(isset($_GET['r'])){
echo'<b><font color="#FF0000">Чтение файла</font></b><hr>';
$file=file($_GET['r']);
if($file){
for($i=0; $i<count($file); $i++){
$file[$i] = htmlspecialchars($file[$i]);
echo '<small>'.$file[$i].'</small><br>';}}
if(!$file){echo'Ошибка чтения файла!';}}
/////////////////////////////////
/////////Удаление файла//////////
/////////////////////////////////
if(isset($_GET['del'])){
echo'<b><font color="#FF0000">Удаление файла</font></b><hr>';
$delete = unlink($_GET['del']);
if($delete){print 'Файл <b>'.$_GET['del'].'</b> удален!<hr>';}
if(!$delete){print 'Ошибка удаления файла <b>'.$_GET['del'].'</b>!';}}
/////////////////////////////////
//Удаление файлов из каталогов///
/////////////////////////////////
if(isset($_GET['deldir'])){
echo'<b><font color="#FF0000">Удаление файлов из каталогов</font></b><hr>';
$dir = opendir($_GET['deldir']);
while($files = readdir($dir)){
if(is_file($_GET['deldir'].$files)){
$del = unlink($_GET['deldir'].$files);}
if(is_dir($_GET['deldir'].$files) && $files !="." && $files !=".."){
$odir = opendir($_GET['deldir'].$files);
while($reddir = readdir($odir)){
if(is_file($_GET['deldir'].$files.'/'.$reddir)){
$delet = unlink($_GET['deldir'].$files.'/'.$reddir);}}}}
if($del){print 'Файлы из директории <b>'.$_GET['deldir'].'</b> удалены!';}
if(!$del){print 'Ошибка удаления файлов из из директории <b>'.$_GET['deldir'].'</b>!';}
if($delet){print'<br>Файлы из подкаталогов в директории <b>'.$_GET['deldir'].'</b> удалены!';}
if(!$delet){print'<br>Ошибка удаления Файлов из подкаталогов в директории <b>'.$_GET['deldir'].'</b>!';}}
/////////////////////////////////
//////Редактирование файла///////
/////////////////////////////////
if(isset($_GET['f'])){
echo'<b><font color="#FF0000">Редактирование файла</font></b><hr>';
$file = file_get_contents($_GET['f']);
$file = htmlspecialchars($file);
echo'<form action="?edit='.$_GET['f'].'" method="post">
<textarea cols="100" rows="15" name="text">'.$file.'</textarea><br>
<input type="submit" value="Редактировать!">';}
/////////////////////////////////
/////////Создание файла//////////
/////////////////////////////////
if(isset($_GET['create'])){
echo'<b><font color="#FF0000">Создание файла</font></b><hr>';
echo'<form action="?made='.$_GET['create'].'" method="post">
<input name="new_name" value="Название нового файла"><br>
<textarea cols="100" rows="15" name="new_file">Содержимое нового файла</textarea><br>
<input type="submit" value="Создать!">';}
/////////////////////////////////
/////////Создание файла//////////
/////////////////////////////////
if(isset($_GET['made'])){
echo'<b><font color="#FF0000">Создание файла</font></b><hr>';
$fp = fopen($_GET['made'].$_POST['new_name'],"w");
fputs($fp,$_POST['new_file']);                                   
fclose($fp);
chmod($_GET['made'].$_POST['new_name'], 0777);
if($fp){echo'Файл создан!';}
if(!$fp){echo'Ошибка создания файла!';}}
/////////////////////////////////
//////Редактирование файла///////
/////////////////////////////////
if(isset($_GET['edit'])){
echo'<b><font color="#FF0000">Редактирование файла</font></b><hr>';
$fp = fopen($_GET['edit'],"w");
fputs($fp,$_POST['text']);                                   
fclose($fp);
chmod($_GET['edit'], 0777);
if($fp){echo'Файл отредактирован!';}
if(!$fp){echo'Ошибка записи файла!';}}
/////////////////////////////////
//////////Зипирование////////////
/////////////////////////////////
if(isset($_GET['zip'])){

class zipfile
{

    var $datasec = array(); 
    var $ctrl_dir = array(); 
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00"; 
    var $old_offset = 0;

    function add_dir($name)

    {
        $name = str_replace("\\", "/", $name);

        $fr = "\x50\x4b\x03\x04";
        $fr .= "\x0a\x00";   
        $fr .= "\x00\x00";   
        $fr .= "\x00\x00";   
        $fr .= "\x00\x00\x00\x00"; 
        $fr .= pack("V",0); 
        $fr .= pack("V",0); 
        $fr .= pack("V",0); 
        $fr .= pack("v", strlen($name) ); 
        $fr .= pack("v", 0 ); 
        $fr .= $name;
        $fr .= pack("V",$crc); 
        $fr .= pack("V",$c_len); 
        $fr .= pack("V",$unc_len); 
        $this -> datasec[] = $fr;
        $new_offset = strlen(implode("", $this->datasec));
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";    
        $cdrec .="\x0a\x00";    
        $cdrec .="\x00\x00";    
        $cdrec .="\x00\x00";   
        $cdrec .="\x00\x00\x00\x00"; 
        $cdrec .= pack("V",0); 
        $cdrec .= pack("V",0); 
        $cdrec .= pack("V",0); 
        $cdrec .= pack("v", strlen($name) ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $ext = "\x00\x00\x10\x00";
        $ext = "\xff\xff\xff\xff";
        $cdrec .= pack("V", 16 ); 
        $cdrec .= pack("V", $this -> old_offset ); 
        $this -> old_offset = $new_offset;
        $cdrec .= $name;
        $this -> ctrl_dir[] = $cdrec;
    }


    function add_file($data, $name)
    { $name = str_replace("\\", "/", $name);
$fr = "\x50\x4b\x03\x04";
        $fr .= "\x14\x00";    
        $fr .= "\x00\x00";    
        $fr .= "\x08\x00";    
        $fr .= "\x00\x00\x00\x00"; 

        $unc_len = strlen($data);
        $crc = crc32($data);
        $zdata = gzcompress($data);
        $zdata = substr( substr($zdata, 0, strlen($zdata) - 4), 2); 
        $c_len = strlen($zdata);
        $fr .= pack("V",$crc); 
        $fr .= pack("V",$c_len); 
        $fr .= pack("V",$unc_len); 
        $fr .= pack("v", strlen($name) ); 
        $fr .= pack("v", 0 ); 
        $fr .= $name;
        $fr .= $zdata;
        $fr .= pack("V",$crc); 
        $fr .= pack("V",$c_len); 
        $fr .= pack("V",$unc_len); 
        $this -> datasec[] = $fr;
        $new_offset = strlen(implode("", $this->datasec));
        $cdrec = "\x50\x4b\x01\x02";
        $cdrec .="\x00\x00";    
        $cdrec .="\x14\x00";  
        $cdrec .="\x00\x00";   
        $cdrec .="\x08\x00";   
        $cdrec .="\x00\x00\x00\x00"; 
        $cdrec .= pack("V",$crc); 
        $cdrec .= pack("V",$c_len); 
        $cdrec .= pack("V",$unc_len);
        $cdrec .= pack("v", strlen($name) ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("v", 0 ); 
        $cdrec .= pack("V", 32 ); 

        $cdrec .= pack("V", $this -> old_offset ); 
        $this -> old_offset = $new_offset;

        $cdrec .= $name;
        $this -> ctrl_dir[] = $cdrec;
    }

    function file() { 
        $data = implode("", $this -> datasec);
        $ctrldir = implode("", $this -> ctrl_dir);
        return
            $data.
            $ctrldir.
            $this -> eof_ctrl_dir.
            pack("v", sizeof($this -> ctrl_dir)).    
            pack("v", sizeof($this -> ctrl_dir)). 
            pack("V", strlen($ctrldir)).   
            pack("V", strlen($data)).    
            "\x00\x00"; }}
$abort = ignore_user_abort(1);
$zipfile = new zipfile();
$fdir = opendir($_GET['zip']);
while($file = readdir($fdir)){
if ($file != '.' and $file != '..'){
if (is_file($_GET['zip'].$file)){$zipfile->add_file(file_get_contents($_GET['zip'].$file),$file);}
if (is_dir($_GET['zip'].$file)){
$sdir = opendir($_GET['zip'].$file);
while($sfile = readdir($sdir)){
if ($sfile != '.' and $sfile != '..'){
if (is_file($_GET['zip'].$file.'/'.$sfile)){$zipfile->add_file(file_get_contents($_GET['zip'].$file.'/'.$sfile), $file.'/'.$sfile);}}}}}}
$name = explode("/",$_GET['zip']);
$file = $name[count($name)-2];
header('Content-type: application/octet-stream');
header("Content-disposition: attachment; filename=$file.zip");
echo $zipfile->file();}
/////////////////////////////////
//////////////Ноги///////////////
/////////////////////////////////
if(empty($_GET['zip']) and empty($_GET['download']) & empty($_GET['img'])){
echo'<br><a href="?">В начало</a>
<br><a href="http://na.hui">Made by Delorean</a><br>';
list($msec,$sec)=explode(chr(32),microtime());  
echo round((($sec+$msec)-$HeadTime),4).' cek.'; 
echo'</body></html>';}
?>
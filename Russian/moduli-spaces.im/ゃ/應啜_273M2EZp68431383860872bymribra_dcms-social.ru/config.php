<?
function altec($msg) {
$msg = htmlspecialchars($msg);
$search = array('|', '\'', '$', '\\', '^', '%', '`', "\0", "\x00", "\x1A");
$replace = array('&#124;', '&#39;', '&#36;', '&#92;', '&#94;', '&#37;', '&#96;', '', '', '');
$msg = str_replace($search, $replace, $msg);
$msg = stripslashes(trim($msg));
return $msg;
} 
function utf_strlen($str) {
  if (function_exists('mb_strlen')) return mb_strlen($str, 'utf-8');
  if (function_exists('iconv_strlen')) return iconv_strlen($str, 'utf-8');
  if (function_exists('utf8_decode')) return strlen(utf8_decode($str));
  return strlen(utf_to_win($str));
} 
function times($time){
global $user;
if ($time==NULL)$time=time();
if (isset($user))$time=$time+$user['set_timesdvig']*60*60;
$date = date("d.m.Y"); 
define("DATE", $date);
$timepp="".date("d.m.Y", $time)."";
$timep="".date("j M  в H:i", $time)."";
$timeppp="".date("H:i", $time)."";
$time_p[0]=date("j n ", $time);
$time_p[1]=date("H:i", $time);
if ($time_p[0]==date("j n Y"))$timep=date("H:i:s", $time);
if (isset($user)){
if ($time_p[0]==date("j n Y", time()+$user['set_timesdvig']*60*60))$timep=date("H:i:s", $time);
if ($time_p[0]==date("j n Y", time()-60*60*(24-$user['set_timesdvig'])))$timep="Вчера в $time_p[1]";
}else{
if ($time_p[0]==date("j n Y"))$timep=date("H:i:s", $time);
if ($time_p[0]==date("j n Y", time()-60*60*24))$timep="Вчера в $time_p[1]";
}
$timep=str_replace("Jan","Янв",$timep);
$timep=str_replace("Feb","Фев",$timep);
$timep=str_replace("Mar","Марта",$timep);
$timep=str_replace("May","Мая",$timep);
$timep=str_replace("Apr","Апр",$timep);
$timep=str_replace("Jun","Июня",$timep);
$timep=str_replace("Jul","Июля",$timep);
$timep=str_replace("Aug","Авг",$timep);
$timep=str_replace("Sep","Сент",$timep);
$timep=str_replace("Oct","Окт",$timep);
$timep=str_replace("Nov","Ноября",$timep);
$timep=str_replace("Dec","Дек",$timep);
if($timepp==DATE)return $timeppp; else return $timep;
}
###
function group_img($id){
if(is_file('pic/'.$id.'.png')){
return '<img src="img.php?name='.$id.'.png&dir=group/pic/&prev=50" width="50" height="50" alt=""/>';
}elseif(is_file('pic/'.$id.'.gif')){
return '<img src="img.php?name='.$id.'.gif&dir=group/pic/&prev=50" width="50" height="50" alt=""/>';
}elseif(is_file('pic/'.$id.'.jpg')){
return '<img src="img.php?name='.$id.'.jpg&dir=group/pic/&prev=50" width="50" height="50" alt=""/>';
}else{
return '<img src="images/gr50.png" alt="!"/>';
}
}
##Фото превью
function group_foto($id, $prev){
if(is_file('foto/'.$id.'.png')){
return '<img src="img.php?name='.$id.'.png&dir=group/foto/&prev='.$prev.'" width="50" height="50" alt=""/>';
}elseif(is_file('foto/'.$id.'.gif')){
return '<img src="img.php?name='.$id.'.gif&dir=group/foto/&prev='.$prev.'" width="50" height="50" alt=""/>';
}elseif(is_file('foto/'.$id.'.jpg')){
return '<img src="img.php?name='.$id.'.jpg&dir=group/foto/&prev='.$prev.'" width="50" height="50" alt=""/>';
}else{
return '<img src="images/gr50.png" alt="!"/>';
}
}

function group_foto1($id, $prev){

if(is_file('foto/'.$id.'.png')){
return '<img src="img.php?name='.$id.'.png&dir=group/foto/&prev='.$prev.'" width="128" height="128" alt=""/>';
}elseif(is_file('foto/'.$id.'.gif')){
return '<img src="img.php?name='.$id.'.gif&dir=group/foto/&prev='.$prev.'" width="128" height="128" alt=""/>';
}elseif(is_file('foto/'.$id.'.jpg')){
return '<img src="img.php?name='.$id.'.jpg&dir=group/foto/&prev='.$prev.'" width="128" height="128" alt=""/>';
}else{
return '<img src="images/gr50.png" alt="!"/>';
}
}


function truncate_utf8($string, $len, $wordsafe = FALSE, $dots = true) {
 $slen = strlen($string);
 if ($slen <= $len) {
 return $string;
 }
 if ($wordsafe) {
 $end = $len;
 while (($string[--$len] != ' ') && ($len > 0)) {};
 if ($len == 0) {
 $len = $end;
 }
 }
 if ((ord($string[$len]) < 0x80) || (ord($string[$len]) >= 0xC0)) {
 return substr($string, 0, $len) . ($dots ? ' ...' : '');
 }
 while (--$len >= 0 && ord($string[$len]) >= 0x80 && ord($string[$len]) < 0xC0) {};
 return substr($string, 0, $len) . ($dots ? ' ...' : '');
 }
?>
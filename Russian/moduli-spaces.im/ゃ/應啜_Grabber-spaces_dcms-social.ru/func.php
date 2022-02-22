<?php
function auth(){
global $cf,$slogin,$spass;
If(!file_exists($cf) || filemtime($cf) < time()-180){
$host = 'http://spaces.ru/mysite/?name='.$slogin.'&password='.$spass;
$auth = get_headers($host,1);
if($auth['Location']){
request($auth['Location']); 
}
else {die('<b>Ошибка авторизации на сервере!</b><br/>
Проверьте правильность логина и пароля, прописанных в <b>conf.php</b>');}
}
}

function request($url,$return){
global $cf;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cf);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cf);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
curl_setopt($ch, CURLOPT_USERAGENT, 'Nokia N90');
$data = curl_exec($ch);
curl_close($ch);
If($return == true){
$data= preg_replace('/(\s)\s+/',' ',$data);
$data= preg_replace('/\n/','',$data);
return strip_tags($data,'<div>,<table>,<tr>,<td>,<a>,<img>,<br/>');
}
}

function what_da_fuck($data){
$data = preg_match('|<div class="busi_switcher">(.*?)</div>|is',$data,$rez);
If(preg_match('|Категории|is',$rez[1])){
return (object) array("result"=>"true","type"=>"folders");
}
elseIf(preg_match('|Новые|is',$rez[1])){
return (object) array("result"=>"true","type"=>"files");
}
else return false;
}

function pagination($data){
global $id,$sort,$dleftr,$dpages;
$lr = preg_match('|<div class="pagination_ar">(.*?)</div>|is',$data,$rez);
$rez[1] = preg_replace('#<a href="([^"]+)p=([0-9]+)" class="(prev|next)">#is','<a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort='.$sort.'&amp;page=\\2">',$rez[1]);
Echo '<div class="'.$dleftr.'">'.$rez[1].'</div>';
$plr = preg_match('|<div class="page_links">(.*?)</div>|is',$data,$prez);
$prez[1] = preg_replace('#<a href="([^"]+)p=([0-9]+)">#is','<a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort='.$sort.'&amp;page=\\2">',$prez[1]);
Echo '<div class="'.$dpages.'">'.strip_tags($prez[1],'<a>').'<br/>
Страница: <div><form method="get" action="'.$scp.'">
<input name="page" size="3" style="-wap-input-format:\'5N\'" maxlength="5"/>
<input type="hidden" name="opt" value="list"/>
<input type="hidden" name="id" value="'.$id.'"/> <input type="hidden" name="sort" value="'.$sort.'"/>
<input type="submit" value="Пошел"/> </form></div></div>';
}
function gde_ya($data){
global $scp,$dsort,$sort,$page;
$nav = preg_match('|<div class="location_bar" id="header_path">(.*?)</div>|is',$data,$rez);
$rez[1] = preg_match_all('|<a href="([^"]+)dir=([0-9]+)([^"]+)">([^<]+)</a>|is',$rez[1],$gd);
echo '<div class="'.$dsort.'"><a href="'.$scp.'">Загрузки</a> ';
If($rez[1] >0){

for($i=0; $i<$rez[1]; $i++)
{
Echo '&rarr; <a href="'.$scp.'?opt=list&amp;id='.intval($gd[2][$i]).'&amp;page='.$page.'&amp;sort='.$sort.'">'.$gd[4][$i].'</a> ';
}
}
$cur = preg_match('|<div class="main"> <div class="backlink"> <a class="arrow_link" href="([^"]+)dir=([0-9]+)([^"]+)"> &larr; ([^<]+)</a> </div>|is',$data,$curr);
if($cur){
Echo '&rarr; <a href="'.$scp.'?opt=list&amp;id='.intval($curr[2]).'&amp;sort='.$sort.'">'.$curr[4].'</a> ';
}
$pl = preg_match('|<div class="list_item gradient_block1"> ([^<]+)</div>|is',$data,$place);
if($pl){
Echo '&rarr; <b>'.$place[1].'</b>';
}
echo '</div>';
}

function printlist($data,$list){
global $scp,$id,$sort,$dfiles,$dscrins,$dwarn,$dfolders;
switch($list){
case 'folders':
if($sort<1){
$fol = preg_match_all('#<a href="([^"]+)dir=([0-9]+)([^"]+)" class="arrow_link"> <img src="http://new-i09.spaces.ru/folder.gif" alt="" class="icon"/>([^<]+)</a> \(([0-9]+)\)#is',$data,$rez);
if($fol >0){
For($i=0; $i<$fol; $i++){
If($rez[2][$i] != 1691 && $rez[2][$i] != 1692 && $rez[2][$i] != 1693 && $rez[2][$i] != 1694 && $rez[2][$i] != 1733 && $rez[2][$i] != 1734 && $rez[2][$i] != 1735 && $rez[2][$i] != 1736){
echo '<div class="'.$dfolders.'"><img src="'.$scp.'folder.gif" alt="+"/> <a href="'.$scp.'?opt=list&amp;id='.$rez[2][$i].'">'.$rez[4][$i].'</a> ('.$rez[5][$i].')</div>';
}
}
}
else echo '<div class="'.$dwarn.'"><span style="color: #ff0000;">Директория не найдена</span></div>';
}
else {

$fl = preg_match_all('#<div class="left" style="margin-right:5px;">(.*?)</div><div class="overfl_hid"> <a href="http://spaces.ru/(files|pictures|music)/([^"]+)LII=([^"]+)&amp;from=([^"]+)read=([0-9]+)([^"]+)" class="arrow_link strong_link"> <img src="([^"]+)" alt="" class="icon"/> ([^<]+)</a>(|\(18\+\))([^\(]+)\(([0-9\.]+) (Кб|Мб)\)#is',$data,$res);
if($fl>0){
For($i=0; $i<$fl; $i++){
echo '<div class="'.$dscrins.'">'.strip_tags($res[1][$i],'<img>').'</div><div class="'.$dfiles.'"><img src="'.$res[8][$i].'" alt="+"/> <a href="'.$scp.'?opt='.$res[2][$i].'&amp;read='.$res[6][$i].'&amp;LII='.$res[4][$i].'&amp;id='.$id.'&amp;sort='.$sort.'">'.$res[9][$i].'</a> ('.$res[12][$i].' '.$res[13][$i].') <span style="color:#ff0000;">'.$res[10][$i].'</span></div>';
}
pagination($data);
}
else echo '<div class="'.$dwarn.'"><span style="color: #ff0000;">Директория пуста</span></div>';
}
break;
case 'files':
$fl = preg_match_all('#<div class="left" style="margin-right:5px;">(.*?)</div><div class="overfl_hid"> <a href="http://spaces.ru/(files|pictures|music)/([^"]+)LII=([^"]+)&amp;from=([^"]+)read=([0-9]+)([^"]+)" class="arrow_link strong_link"> <img src="([^"]+)" alt="" class="icon"/> ([^<]+)</a>(|\(18\+\))([^\(]+)\(([0-9\.]+) (Кб|Мб)\)#is',$data,$res);
if($fl>0){
For($i=0; $i<$fl; $i++){
echo '<div class="'.$dscrins.'">'.strip_tags($res[1][$i],'<img>').'</div><div class="'.$dfiles.'"><img src="'.$res[8][$i].'" alt="+"/> <a href="'.$scp.'?opt='.$res[2][$i].'&amp;read='.$res[6][$i].'&amp;LII='.$res[4][$i].'&amp;id='.$id.'&amp;sort='.$sort.'">'.$res[9][$i].'</a> ('.$res[12][$i].' '.$res[13][$i].') <span style="color:#ff0000;">'.$res[10][$i].'</span></div>';
}
pagination($data);
}
else echo '<div class="'.$dwarn.'"><span style="color: #ff0000;">Файлы не найдены</span></div>';
break;
}
}

function sort_list(){
global $id,$sort,$list,$dsort,$scp;
switch($list){
case 'files':
If($sort ==0 || $sort == 2){
echo '<div class="'.$dsort.'"><a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=1">Новинки</a> | <b>Популярные</b> &rarr; <b>Сейчас</b> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=3">За всё время</a></div>';
}
elseIf($sort == 3){
echo '<div class="'.$dsort.'"><a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=1">Новинки</a> | <b>Популярные</b> &rarr; <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=2">Сейчас</a> | <b>За всё время</b></div>';
}
else {
echo '<div class="'.$dsort.'"><b>Новинки</b> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=2">Популярные</a></div>';
}
break;

case 'folders':
If($sort == 2){
echo '<div class="'.$dsort.'"><a href="'.$scp.'?opt=list&amp;id='.$id.'">Папки</a> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=1">Новинки</a> | <b>Популярные</b> &rarr; <b>Сейчас</b> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=3">За всё время</a></div>';
}
elseIf($sort == 3){
echo '<div class="'.$dsort.'"><a href="'.$scp.'?opt=list&amp;id='.$id.'">Папки</a> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=1">Новинки</a> | <b>Популярные</b> &rarr; <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=2">Сейчас</a> | <b>За всё время</b></div>';
}
elseIf($sort == 1){
echo '<div class="'.$dsort.'"><a href="'.$scp.'?opt=list&amp;id='.$id.'">Папки</a> | <b>Новинки</b> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=3">Популярные</a></div>';
}
else {
echo '<div class="'.$dsort.'"><b>Папки</b> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=1">Новинки</a> | <a href="'.$scp.'?opt=list&amp;id='.$id.'&amp;sort=2">Популярные</a></div>';
}
break;
}
}

?>
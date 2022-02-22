<?php
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;
include dirname(__file__).'/conf.php';
include dirname(__file__).'/func.php';
auth();
include dirname(__file__).'/header.php';
$id = isset($_GET['id'])?intval($_GET['id']): '1';
$page = isset($_GET['page'])?intval($_GET['page']): '0';
$sort = isset($_GET['sort'])?intval($_GET['sort']): '0';

switch($_GET['opt']){

default:
$host= "http://spaces.ru/shared_zone/";
$data = request($host,1);
$data = preg_match_all('#<a href="([^"]+)dir=([0-9]+)([^"]+)" class="arrow_link"> <img src="http://new-i09.spaces.ru/folder.gif" alt="" class="icon"/>([^<]+)</a> \(([0-9]+)\)#is',$data,$rez);
For($i=0; $i<$data; $i++){
If($rez[2][$i] != 1739){
echo '<div class="'.$dfolders.'"><img src="'.$scp.'folder.gif" alt="+"/> <a href="'.$scp.'?opt=list&amp;id='.$rez[2][$i].'">'.$rez[4][$i].'</a> ('.$rez[5][$i].')</div>';
}
}
break;

case 'list':
$host= "http://spaces.ru/shared_zone/?dir=".$id."&p=".$page."&list=".$sort;
$data = request($host,1);
$list = what_da_fuck($data)?what_da_fuck($data)->type : false;
If($list){

//Echo htmlspecialchars($data);
gde_ya($data);
sort_list();
printlist($data,$list);
}
else {
echo '<div class="'.$dwarn.'"><span style="color: #ff0000;">Ничего не найдено!</span></div>';
}
echo '<div class="'.$backl.'"><a href="'.$scp.'">&#8592; В загрузки</a></div>';

break;

case 'files':
case 'pictures':
case 'music':
$host= "http://spaces.ru/".$_GET['opt']."/?".$_SERVER['QUERY_STRING'].'&passed=1&from=shared_zone&LI='.$id;
$data = request($host,1);
gde_ya($data);
if($_GET['opt'] != 'music'){
$nam = preg_match('#<div class="file_name"> <img src="([^<]+)" alt="" class="text-top"/> ([^<]+) </div> <div class="preview_block">(.*?)</div>(.*?)<div class="sliderw">(.*?)</div> </div>(.*?)(|</div>)</div>#is',$data,$res);
$mtype ='2';
}
else {
$nam = preg_match('#<div class="file_name"> <img src="([^<]+)" alt="" class="text-top"/> ([^<]+) </div> <div class="row4"> (.*?) </div> <div class="preview_block">(.*?)</div>(.*?)<div class="sliderw">(.*?)</div> </div>(.*?)(|</div>)</div>#is',$data,$res);
$mtype ='1';
}
if($nam){
if($mtype=='2'){
echo '<div class="'.$dfiles.'"><img src="'.$res[1].'" alt="+"/><b>'.$res[2].'</b></div>';
echo '<div class="'.$dscrins.'">'.strip_tags($res[3],'<img>').'</div>';
echo '<div class="'.$dopis.'">'.strip_tags($res[4],'<br/>').'</div>';
$res[5] = preg_replace('#<a href="http://spaces.ru/(files|pictures|music)/([^"]+)LT=([0-9]+)&amp;LI=([0-9]+)&amp;read=([0-9]+)&amp;LII=([0-9]+)&amp;SN=([0-9]+)([^"]+)">([^<]+)</a>#is','<a href="'.$scp.'?opt=\\1&amp;LT=\\3&amp;id=\\4&amp;read=\\5&amp;LII=\\6&amp;SN=\\7&amp;sort='.$sort.'">\\9</a>',$res[5]);
echo '<div class="'.$dleftr.'">'.strip_tags($res[5],'<a>').'</div>';
}

else {
echo '<div class="'.$dfiles.'"><img src="'.$res[1].'" alt="+"/><b>'.$res[2].'</b></div>';
echo '<div class="'.$dscrins.'">'.strip_tags($res[4],'<img>').'</div>';
echo '<div class="'.$dopis.'">'.strip_tags($res[5]).'</div>';
$res[6] = preg_replace('#<a href="http://spaces.ru/(files|pictures|music)/([^"]+)LT=([0-9]+)&amp;LI=([0-9]+)&amp;read=([0-9]+)&amp;LII=([0-9]+)&amp;SN=([0-9]+)([^"]+)">([^<]+)</a>#is','<a href="'.$scp.'?opt=\\1&amp;LT=\\3&amp;id=\\4&amp;read=\\5&amp;LII=\\6&amp;SN=\\7&amp;sort='.$sort.'">\\9</a>',$res[6]);
echo '<div class="'.$dleftr.'">'.strip_tags($res[6],'<a>').'</div>';
}


$ddl = preg_match_all('#<div class="(|row4)"> <a href="([^"]+)" class="iconized"> <img src="http://new-i09.spaces.ru/dload.(gif|png)" alt="" class="icon" /> ([^<]+) </a>([^<]+)</div>#is',$data,$dnload);
for($i=0; $i<$ddl; $i++){
$finfo = new SplFileInfo($dnload[2][$i]);
$fn = $finfo->getFilename();
$ex = substr($fn,-3);
$rf = $sprefix.'_'.intval($_GET['read']).'.'.$ex;
$tf = $path."/".$read;
$furl = str_replace($fn,$rf,$dnload[2][$i]);
echo '<div class="'.$dload.'"><img src="'.$scp.'dload.png" alt="+"/> <a href="'.$furl.'">'.$dnload[4][$i].'</a> '.$dnload[5][$i].'</div>';
}

}
else {
echo '<div class="'.$dwarn.'"><span style="color: #ff0000;">Файл не найден!</span></div>';
} 





//echo htmlspecialchars($data);
echo '
<div class="'.$backl.'"><a href="'.$scp.'">&#8592; В загрузки</a></div>';
break;
}
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$totaltime = ($endtime - $starttime);
echo "<div><span style='color:red;'>".round($totaltime,2)." сек.</span></div>";
include dirname(__file__).'/footer.php';

?>









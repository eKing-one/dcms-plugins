<?php

$list=null;
$num=1;
if($l=='/'){
$set['title'] = 'Файловый обменник';
}else{
$set['title']=$gruppy['name'].' - Обменник - '.$dir_id['name'];
}

$_SESSION['page']=1;
include_once '../sys/inc/thead.php';

title();

if(isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
include 'inc/upload_act.php';
include 'inc/admin_act.php';
}

err();

if($l!='/'){
echo "<div class='nav1'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">Обменник</a><br/>';
echo '</div>';
}

//echo '<table class="post">';
$q=mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy` = '$gruppy[id]' AND (`dir_osn` = '/$l' OR `dir_osn` = '$l/' OR `dir_osn` = '$l') ORDER BY `name`,`num` ASC");
while($post = mysql_fetch_array($q)){
$list[]=array('dir'=>1,'post'=>$post);
}

$q=mysql_query("SELECT * FROM `gruppy_obmen_files` WHERE `id_dir` = '$id_dir' AND `id_gruppy` = '$gruppy[id]' ORDER BY `time` DESC");
while($post = mysql_fetch_array($q)){
$list[]=array('dir'=>0,'post'=>$post);
}

$k_post=sizeof($list);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];

if($k_post==0){
echo '<tr><div class="msg">Папка пуста</div></tr>';
}

for($i=$start;$i<$k_post && $i<$set['p_str']*$page;$i++){

if($list[$i]['dir']==1){
$post=$list[$i]['post'];
if($num==1){
echo "<div class='nav2'>\n";
$num=0;
}else{
echo "<div class='nav1'>\n";
$num=1;}
echo '<tr><td class="icon14"><img src="img/video.png" alt=""/></td> ';
echo '<a href="?s='.$gruppy['id'].'&amp;d='.$post['dir'].'">'.esc(stripcslashes(htmlspecialchars($post['name']))).'</a> <b></b>';

$k_f=0;
$k_n=0;

$q3=mysql_query("SELECT * FROM `gruppy_obmen_dir` WHERE `id_gruppy`='$gruppy[id]' AND `dir_osn` like '$post[dir]%'");
while($post2 = mysql_fetch_array($q3)){
$k_f=$k_f+mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_dir` = '$post2[id]' AND `id_gruppy` = '$gruppy[id]'"),0);
$k_n=$k_n+mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_dir` = '$post2[id]' AND `time` > '".(time()-86400)."' AND `id_gruppy` = '$gruppy[id]'"),0);
}

$k_f=$k_f+mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_dir` = '$post[id]' AND `id_gruppy` = '$gruppy[id]'"),0);
$k_n=$k_n+mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_files` WHERE `id_dir` = '$post[id]' AND `time` > '".(time()-86400)."' AND `id_gruppy` = '$gruppy[id]'"),0);

if($k_n==0){
$k_n=NULL;
}else{
$k_n='/+'.$k_n;
}


echo ' ('.$k_f.''.$k_n.')</div></tr>';
}else{
$post=$list[$i]['post'];
$ras=$post['ras'];
$file=H."sys/gruppy/obmen/files/$post[id].dat";
$name=$post['name'];
$size=$post['size'];

echo '<tr><td class="icon14">';
include 'inc/icon14.php';
echo '</td>';
echo '<td class="p_t">';

if($set['echo_rassh']==1){
$ras=".$post[ras]";
}else{
$ras=NULL;
}

echo '<a href="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.$post['name'].'.'.$post['ras'].'&amp;showinfo">'.esc(stripcslashes(htmlspecialchars($post['name']))).''.$ras.'</a><br/></td></tr>';
//echo '<tr><td class="p_m" colspan="2">';
include 'inc/opis.php';
echo '</td></tr>';
}
}

echo '</table>';

if($k_page>1){
str('?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;',$k_page,$page);
}

if(isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
include 'inc/upload_form.php';
include 'inc/admin_form.php';
}
echo "<div class='navi'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a><br/>';
echo '</div>';
?>

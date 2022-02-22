<?php
if(isset($_POST['msg']) && isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
$msg=$_POST['msg'];

if(isset($_POST['translit']) && $_POST['translit']==1){
$msg=translit($msg);
}

if(strlen2($msg)>1024){
$err='Сообщение слишком длинное';
}else if(strlen2($msg)<2){
$err='Короткое сообщение';
}else if(mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_komm` WHERE `id_file` = '$file_id[id]' AND `id_user` = '$user[id]' AND `msg` = '".my_esc($msg)."' AND `id_gruppy` = '$gruppy[id]' LIMIT 1"),0)!=0){
$err='Ваше сообщение повторяет предыдущее';
}else{
$msg=mysql_escape_string($msg);
mysql_query("INSERT INTO `gruppy_obmen_komm` (`id_gruppy`, `id_file`, `id_user`, `time`, `msg`) values('$gruppy[id]', '$file_id[id]', '$user[id]', '$time', '$msg')");
mysql_query("UPDATE `user` SET `balls` = '".($user['balls']+1)."' WHERE `id` = '$user[id]' LIMIT 1");
msg('Сообщение успешно добавлено');
}
}



err();

$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_obmen_komm` WHERE `id_file` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo '<table class="post">';

if($k_post==0){
echo '<tr><div class="p_t">Нет комментариев</div></tr>';
}

$q=mysql_query("SELECT * FROM `gruppy_obmen_komm` WHERE `id_file` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]' ORDER BY `id` DESC LIMIT $start, $set[p_str]");
while ($post = mysql_fetch_array($q)){
$ank=get_user($post['id_user']);
if($num==1){
echo "<div class='rowdown'>\n";
$num=0;
}else{
echo "<div class='rowup'>\n";
$num=1;}
echo '<img src="/style/themes/'.$set['set_them'].'/user/'.$ank['pol'].'.png" alt="" />';
echo ' '.online($ank['id']).'<a href="/info.php?id='.$ank['id'].'"><span style="color:'.$ank['ncolor'].'">'.$ank['nick'].'</span></a> ';
if ($ank['status_vip']==1)echo "<img src='/status_vip/img/vip.png'></img> \n";
echo '</div>';
echo '</div>';
echo'</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';
echo output_text($post['msg']).'';

if(isset($user) && ($gruppy['admid']==$user['id'] || $user['id']==$file_id['id_user'])){
echo '<div style="text-align: right;"><font color="#afb0a3">'.vremja($post['time']).' <a href="?s='.$gruppy['id'].'&amp;komm&amp;page='.$page.'&amp;del_post='.$post['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'"><img src="/style/icons/bdel.png"></img></a></div>';
}

echo'</td>';
echo'</tr>';
}

echo '</table>';

if($k_page>1){
str('?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;komm&amp;',$k_page,$page);
}

if(isset($user) && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || $user['id']==$gruppy['admid'])){
echo '<form method="post" name="message" action="?s='.$gruppy['id'].'&amp;d='.$dir_id['dir'].'&amp;f='.urlencode($file_id['name']).'.'.$file_id['ras'].'&amp;komm">';
echo 'Сообщение:<br/><textarea name="msg"></textarea><br/>';

if($user['set_translit']==1){
echo '<label><input type="checkbox" name="translit" value="1"/> Транслит</label><br/>';
}

echo '<input value="Отправить" type="submit"/></form>';
}
?>

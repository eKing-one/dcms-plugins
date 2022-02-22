<?
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';
$num=1;
if(isset($_GET['s']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy` WHERE `id` = '".intval($_GET['s'])."' LIMIT 1"),0)==1)
{
$s=intval($_GET['s']);
$gruppy=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy` WHERE `id` = '$s' LIMIT 1"));
include_once 'inc/ban.php';
$set['title']=$gruppy['name'].' - Опросы'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
if($gruppy['konf_gruppy']==0 || isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']  || isset($user) && $user['level']>0)
{
if(isset($_GET['open']))
{
if(isset($user) && isset($_GET['id']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id` = '".intval($_GET['id'])."' AND `id_gruppy`='$gruppy[id]' AND `time_close`>'$time' LIMIT 1"),0)==1)
{
$v=mysql_fetch_assoc(mysql_query("SELECT * FROM `gruppy_votes` WHERE `id` = '".intval($_GET['id'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1"));
if(isset($_POST['otvet']) && ($_POST['otvet']==1 || $_POST['otvet']==2 || $v['otvet_3']!=NULL && $_POST['otvet']==3 || $v['otvet_4']!=NULL && $_POST['otvet']==4 || $v['otvet_5']!=NULL && $_POST['otvet']==5 || $v['otvet_6']!=NULL && $_POST['otvet']==6 || $v['otvet_7']!=NULL && $_POST['otvet']==7 || $v['otvet_8']!=NULL && $_POST['otvet']==8 || $v['otvet_9']!=NULL && $_POST['otvet']==9 || $v['otvet_10']!=NULL && $_POST['otvet']==10) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `id_vote`='$v[id]' LIMIT 1"),0)==0 && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']))
{
$id_otvet=intval($_POST['otvet']);
mysql_query("INSERT INTO `gruppy_votes_otvet` (`id_gruppy`, `id_vote`, `id_otvet`, `id_user`) values ('$gruppy[id]', '$v[id]', '$id_otvet', '$user[id]')");
msg('Голос успешно принят');
}
}
if(isset($user) && $user['id']==$gruppy['admid'] && isset($_GET['close']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id` = '".intval($_GET['close'])."' AND `id_gruppy`='$gruppy[id]' AND `time_close`>'$time' LIMIT 1"),0)==1)
{
mysql_query("UPDATE `gruppy_votes` SET `time_close`='$time' WHERE `id`='".intval($_GET['close'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
msg('Опрос успешно закрыт для голосования');
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy`='$gruppy[id]' AND `time_close`>'$time'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="p_t">';
echo 'Открытых опросов нет';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy_votes` WHERE `id_gruppy`='$gruppy[id]' AND `time_close`>'$time' ORDER BY `time_create` DESC LIMIT $start, $set[p_str]");
while ($votes = mysql_fetch_assoc($q))
{
echo '<tr>';
if($num==1){
echo "<div class='frends'>\n";
$num=0;
}else{
echo "<div class='frends2'>\n";
$num=1;}
echo '<td class="icon14">';
echo '<img src="img/votes.png" alt="" />';
echo ''.$votes['vote'].' ('.vremja($votes['time_create']).')';
echo '</div>';
echo '</div>';
echo '</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';
if(isset($user) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `id_vote`='$votes[id]' LIMIT 1"),0)==0 && (mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_users` WHERE `id_gruppy` = '$gruppy[id]' AND `id_user`='$user[id]' AND `invit`='0' AND `activate`='0' LIMIT 1"),0)==1 || isset($user) && $user['id']==$gruppy['admid']))
{
echo'<form method="post" action="?s='.$gruppy['id'].'&open&id='.$votes['id'].'">';
echo'<input type="radio" name="otvet" value="1"/> '.output_text($votes['otvet_1']).'<br/>';
echo'<input type="radio" name="otvet" value="2"/> '.output_text($votes['otvet_2']).'<br/>';
if($votes['otvet_3']!=NULL)echo'<input type="radio" name="otvet" value="3"/> '.output_text($votes['otvet_3']).'<br/>';
if($votes['otvet_4']!=NULL)echo'<input type="radio" name="otvet" value="4"/> '.output_text($votes['otvet_4']).'<br/>';
if($votes['otvet_5']!=NULL)echo'<input type="radio" name="otvet" value="5"/> '.output_text($votes['otvet_5']).'<br/>';
if($votes['otvet_6']!=NULL)echo'<input type="radio" name="otvet" value="6"/> '.output_text($votes['otvet_6']).'<br/>';
if($votes['otvet_7']!=NULL)echo'<input type="radio" name="otvet" value="7"/> '.output_text($votes['otvet_7']).'<br/>';
if($votes['otvet_8']!=NULL)echo'<input type="radio" name="otvet" value="8"/> '.output_text($votes['otvet_8']).'<br/>';
if($votes['otvet_9']!=NULL)echo'<input type="radio" name="otvet" value="9"/> '.output_text($votes['otvet_9']).'<br/>';
if($votes['otvet_10']!=NULL)echo'<input type="radio" name="otvet" value="10"/> '.output_text($votes['otvet_10']).'<br/>';
echo'<input type="submit" value="Голосовать">';
echo'</form>';
}
else
{
$all_otvets=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]'"),0);
$otvet_1=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='1'"),0);
echo'1. '.output_text($votes['otvet_1']).': '.$otvet_1.' из '.$all_otvets.'<br/>';
$otvet_2=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='2'"),0);
echo'2. '.output_text($votes['otvet_2']).': '.$otvet_2.' из '.$all_otvets.'<br/>';
if($votes['otvet_3']!=NULL){
$otvet_3=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='3'"),0);
echo'3. '.output_text($votes['otvet_3']).': '.$otvet_3.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_4']!=NULL){
$otvet_4=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='4'"),0);
echo'4. '.output_text($votes['otvet_4']).': '.$otvet_4.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_5']!=NULL){
$otvet_5=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='5'"),0);
echo'5. '.output_text($votes['otvet_5']).': '.$otvet_5.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_6']!=NULL){
$otvet_6=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='6'"),0);
echo'6. '.output_text($votes['otvet_6']).': '.$otvet_6.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_7']!=NULL){
$otvet_7=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='7'"),0);
echo'7. '.output_text($votes['otvet_7']).': '.$otvet_7.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_8']!=NULL){
$otvet_8=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='8'"),0);
echo'8. '.output_text($votes['otvet_8']).': '.$otvet_8.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_9']!=NULL){
$otvet_9=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='9'"),0);
echo'9. '.output_text($votes['otvet_9']).': '.$otvet_9.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_10']!=NULL){
$otvet_10=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='10'"),0);
echo'10. '.output_text($votes['otvet_10']).': '.$otvet_10.' из '.$all_otvets.'<br/>';
}
}
if(isset($user) && $user['id']==$gruppy['admid'])echo'<a href="?s='.$gruppy['id'].'&open&close='.$votes['id'].'">Закончить опрос</a>';
echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
}
elseif(isset($_GET['results']))
{
if(isset($user) && $user['id']==$gruppy['admid'] && isset($_GET['del']) && mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id` = '".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' AND `time_close`<'$time' LIMIT 1"),0)==1)
{
mysql_query("DELETE FROM `gruppy_votes` WHERE `id`='".intval($_GET['del'])."' AND `id_gruppy`='$gruppy[id]' LIMIT 1");
msg('Опрос успешно удален');
}
$k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy`='$gruppy[id]' AND `time_close`<'$time'"),0);
$k_page=k_page($k_post,$set['p_str']);
$page=page($k_page);
$start=$set['p_str']*$page-$set['p_str'];
//echo '<table class="post">';
if ($k_post==0)
{
echo '<tr>';
echo '<div class="p_t">';
echo 'Закрытых опросов нет';
echo '</div>';
echo '</tr>';
}
$q=mysql_query("SELECT * FROM `gruppy_votes` WHERE `id_gruppy`='$gruppy[id]' AND `time_close`<'$time' ORDER BY `time_close` ASC LIMIT $start, $set[p_str]");
while ($votes = mysql_fetch_assoc($q))
{
echo '<tr>';
if($num==1){
echo "<div class='frends'>\n";
$num=0;
}else{
echo "<div class='frends2'>\n";
$num=1;}
//echo '<td class="icon14">';
echo '<img src="img/votes.png" alt="" />';
echo ''.$votes['vote'].' ('.vremja($votes['time_create']).')';
echo '</div>';
echo '</div>';
echo '</tr>';
echo '<tr>';
//echo '<td class="p_m" colspan="2">';
$all_otvets=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]'"),0);
$otvet_1=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='1'"),0);
echo'1. '.output_text($votes['otvet_1']).': '.$otvet_1.' из '.$all_otvets.'<br/>';
$otvet_2=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='2'"),0);
echo'2. '.output_text($votes['otvet_2']).': '.$otvet_2.' из '.$all_otvets.'<br/>';
if($votes['otvet_3']!=NULL){
$otvet_3=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='3'"),0);
echo'3. '.output_text($votes['otvet_3']).': '.$otvet_3.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_4']!=NULL){
$otvet_4=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='4'"),0);
echo'4. '.output_text($votes['otvet_4']).': '.$otvet_4.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_5']!=NULL){
$otvet_5=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='5'"),0);
echo'5. '.output_text($votes['otvet_5']).': '.$otvet_5.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_6']!=NULL){
$otvet_6=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='6'"),0);
echo'6. '.output_text($votes['otvet_6']).': '.$otvet_6.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_7']!=NULL){
$otvet_7=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='7'"),0);
echo'7. '.output_text($votes['otvet_7']).': '.$otvet_7.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_8']!=NULL){
$otvet_8=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='8'"),0);
echo'8. '.output_text($votes['otvet_8']).': '.$otvet_8.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_9']!=NULL){
$otvet_9=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='9'"),0);
echo'9. '.output_text($votes['otvet_9']).': '.$otvet_9.' из '.$all_otvets.'<br/>';
}
if($votes['otvet_10']!=NULL){
$otvet_10=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes_otvet` WHERE `id_gruppy` = '$gruppy[id]' AND `id_vote`='$votes[id]' AND `id_otvet`='10'"),0);
echo'10. '.output_text($votes['otvet_10']).': '.$otvet_10.' из '.$all_otvets.'<br/>';
}
if(isset($user) && $user['id']==$gruppy['admid'])echo'<a href="?s='.$gruppy['id'].'&results&del='.$votes['id'].'">Удалить опрос</a>';
echo '</td>';
echo '</tr>';
}
echo '</table>';
if ($k_page>1)str("?s=$gruppy[id]&",$k_page,$page); // Вывод страниц
}
elseif(isset($_GET['create']) && isset($user) && $user['id']==$gruppy['admid'])
{
if(isset($_POST['vote']) && isset($_POST['otvet_1']) && isset($_POST['otvet_2']) && isset($_POST['num']) && is_numeric($_POST['num']) && isset($_POST['type']) && ($_POST['type']=='1' || $_POST['type']=='2' || $_POST['type']=='3'))
{
$vote=htmlspecialchars($_POST['vote']);
$mat=antimat($vote);
if ($mat)$err[]='В вопросе обнаружен мат: '.$mat;
if (strlen2($vote)>1024){$err[]='Вопрос слишком длинный';}
elseif (strlen2($vote)<2){$err[]='Вопрос короткий';}

$otvet_1=htmlspecialchars($_POST['otvet_1']);
$mat=antimat($otvet_1);
if ($mat)$err[]='В ответе 1 обнаружен мат: '.$mat;
if (strlen2($otvet_1)>32){$err[]='Ответ 1 слишком длинный';}
elseif (strlen2($otvet_1)<1){$err[]='Ответ 1 короткий';}

$otvet_2=htmlspecialchars($_POST['otvet_2']);
$mat=antimat($otvet_2);
if ($mat)$err[]='В ответе 2 обнаружен мат: '.$mat;
if (strlen2($otvet_2)>32){$err[]='Ответ 2 слишком длинный';}
elseif (strlen2($otvet_2)<1){$err[]='Ответ 2 короткий';}

if(isset($_POST['otvet_3']) && $_POST['otvet_3']!=NULL)
{
$otvet_3=$_POST['otvet_3'];
$mat=antimat($otvet_3);
if ($mat)$err[]='В ответе 3 обнаружен мат: '.$mat;
if (strlen2($otvet_3)>32){$err[]='Ответ 3 слишком длинный';}
elseif (strlen2($otvet_3)<1){$err[]='Ответ 3 короткий';}
}
else
{
$otvet_3=NULL;
}

if(isset($_POST['otvet_4']) && $_POST['otvet_4']!=NULL)
{
$otvet_4=$_POST['otvet_4'];
$mat=antimat($otvet_4);
if ($mat)$err[]='В ответе 4 обнаружен мат: '.$mat;
if (strlen2($otvet_4)>32){$err[]='Ответ 4 слишком длинный';}
elseif (strlen2($otvet_4)<1){$err[]='Ответ 4 короткий';}
}
else
{
$otvet_4=NULL;
}

if(isset($_POST['otvet_5']) && $_POST['otvet_5']!=NULL)
{
$otvet_5=$_POST['otvet_5'];
$mat=antimat($otvet_5);
if ($mat)$err[]='В ответе 5 обнаружен мат: '.$mat;
if (strlen2($otvet_5)>32){$err[]='Ответ 5 слишком длинный';}
elseif (strlen2($otvet_5)<1){$err[]='Ответ 5 короткий';}
}
else
{
$otvet_5=NULL;
}

if(isset($_POST['otvet_6']) && $_POST['otvet_6']!=NULL)
{
$otvet_6=$_POST['otvet_6'];
$mat=antimat($otvet_6);
if ($mat)$err[]='В ответе 6 обнаружен мат: '.$mat;
if (strlen2($otvet_6)>32){$err[]='Ответ 6 слишком длинный';}
elseif (strlen2($otvet_6)<1){$err[]='Ответ 6 короткий';}
}
else
{
$otvet_6=NULL;
}

if(isset($_POST['otvet_7']) && $_POST['otvet_7']!=NULL)
{
$otvet_7=$_POST['otvet_7'];
$mat=antimat($otvet_7);
if ($mat)$err[]='В ответе 7 обнаружен мат: '.$mat;
if (strlen2($otvet_7)>32){$err[]='Ответ 7 слишком длинный';}
elseif (strlen2($otvet_7)<1){$err[]='Ответ 7 короткий';}
}
else
{
$otvet_7=NULL;
}

if(isset($_POST['otvet_8']) && $_POST['otvet_8']!=NULL)
{
$otvet_8=$_POST['otvet_8'];
$mat=antimat($otvet_8);
if ($mat)$err[]='В ответе 8 обнаружен мат: '.$mat;
if (strlen2($otvet_8)>32){$err[]='Ответ 8 слишком длинный';}
elseif (strlen2($otvet_8)<1){$err[]='Ответ 8 короткий';}
}
else
{
$otvet_8=NULL;
}

if(isset($_POST['otvet_9']) && $_POST['otvet_9']!=NULL)
{
$otvet_9=$_POST['otvet_9'];
$mat=antimat($otvet_9);
if ($mat)$err[]='В ответе 9 обнаружен мат: '.$mat;
if (strlen2($otvet_9)>32){$err[]='Ответ 9 слишком длинный';}
elseif (strlen2($otvet_9)<1){$err[]='Ответ 9 короткий';}
}
else
{
$otvet_9=NULL;
}

if(isset($_POST['otvet_10']) && $_POST['otvet_10']!=NULL)
{
$otvet_10=$_POST['otvet_10'];
$mat=antimat($otvet_10);
if ($mat)$err[]='В ответе 10 обнаружен мат: '.$mat;
if (strlen2($otvet_10)>32){$err[]='Ответ 10 слишком длинный';}
elseif (strlen2($otvet_10)<1){$err[]='Ответ 10 короткий';}
}
else
{
$otvet_10=NULL;
}

$num=intval($_POST['num']);
$type=intval($_POST['type']);
if($type=='1')$time_close=$time+60*60*24*$num; elseif($type=='2')$time_close=$time+60*60*24*7*$num; else $time_close=$time+60*60*24*30*$num;
if(!isset($err)){
mysql_query("INSERT INTO `gruppy_votes` (`id_gruppy`, `vote`, `otvet_1`, `otvet_2`, `otvet_3`, `otvet_4`, `otvet_5`, `otvet_6`, `otvet_7`, `otvet_8`, `otvet_9`, `otvet_10`, `time_create`, `time_close`) values ('$gruppy[id]', '".my_esc($vote)."', '".my_esc($otvet_1)."', '".my_esc($otvet_2)."', '".my_esc($otvet_3)."', '".my_esc($otvet_4)."', '".my_esc($otvet_5)."', '".my_esc($otvet_6)."', '".my_esc($otvet_7)."', '".my_esc($otvet_8)."', '".my_esc($otvet_9)."', '".my_esc($otvet_10)."', '$time', '$time_close')");
msg('Опрос успешно создан');
}
}
err();
echo'<b>Создание опроса</b><br/>';
echo'<form method="post" action="?s='.$gruppy['id'].'&create">';
echo'Вопрос<br/>';
echo'<input type="text" name="vote"><br/>';
echo'Варианты ответа(необходимо указать минимум 2)<br/>';
echo'1. <input type="text" name="otvet_1"><br/>';
echo'2. <input type="text" name="otvet_2"><br/>';
echo'3. <input type="text" name="otvet_3"><br/>';
echo'4. <input type="text" name="otvet_4"><br/>';
echo'5. <input type="text" name="otvet_5"><br/>';
echo'6. <input type="text" name="otvet_6"><br/>';
echo'7. <input type="text" name="otvet_7"><br/>';
echo'8. <input type="text" name="otvet_8"><br/>';
echo'9. <input type="text" name="otvet_9"><br/>';
echo'10. <input type="text" name="otvet_10"><br/>';
echo'Длительность опроса<br/>';
echo'<input type="text" name="num" size="3">';
echo'<select name="type">';
echo'<option value="1">Дней</option>';
echo'<option value="2">Недель</option>';
echo'<option value="3">Месяцев</option>';
echo'</select><br/>';
echo'<input type="submit" value="Создать">';
echo'</form>';
echo'<img src="img/back.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'">Перейти к опросам</a><br/>';
}
else
{
$open=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy` = '$gruppy[id]' AND `time_close`>'$time'"),0);
$close=mysql_result(mysql_query("SELECT COUNT(*) FROM `gruppy_votes` WHERE `id_gruppy` = '$gruppy[id]' AND `time_close`<'$time'"),0);
echo'<div class="nav1"><img src="img/6od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&open">Открытые</a> ('.$open.')</div>';
echo'<div class="nav2"><img src="img/ank.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&results">Результаты</a> ('.$close.')</div>';
}
echo '<div class="foot"><img src="img/3od.png" alt="" class="icon"/> <a href="?s='.$gruppy['id'].'&create">Создать опрос</a></div>';
echo "<div class='foot'>\n";
echo '<img src="img/back.png" alt="" class="icon"/> <a href="index.php?s='.$gruppy['id'].'">В сообщество</a></div>';
}
else
{
echo'Вам недоступен просмотр опросов данного сообщества';
}
}
else
{
header("Location:index.php");
}
include_once '../sys/inc/tfoot.php';
?>

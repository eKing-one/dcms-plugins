<?
$rebus_last = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `rebus_st` <> '0' ORDER BY id DESC"));
if ($rebus_last!=NULL && $rebus_last['rebus_st']!=4 && $rebus_last['rebus_st']!=0)
{
$rebus_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `rebus_vopros` WHERE `id` = '$rebus_last[vopros]' LIMIT 1"));
$rebus_post = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `msg` like '%$rebus_vopros[otvet]%' AND `rebus_st` = '0' AND `time` >= '".($time-$rebus_last['time'])."' ORDER BY `id` ASC LIMIT 1"));
if($rebus_post!=NULL){

$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$rebus_post[id_user]' LIMIT 1"));


$add_balls=0;
if ($rebus_last['rebus_st']==1){$add_balls=25;$pods='не используя подсказок';}

if ($rebus_last['rebus_st']==1){$add_otvet=1;$pods='';}

if ($rebus_last['rebus_st']==2){$add_balls=10;$pods='используя одну подсказку';}

if ($rebus_last['rebus_st']==2){$add_otvet=1;$pods='';}

if ($rebus_last['rebus_st']==3){$add_balls=5;$pods='используя обе посказки';}

if ($rebus_last['rebus_st']==3){$add_otvet=1;$pods='';}

$msg="Молодец, [b]$ank[nick][/b].\nТы первым дал верный ответ: [b]$rebus_vopros[otvet][/b] $pods.\n[b]$ank[nick][/b] получает $add_balls балла Следующий вопрос через $set[rebus_new] сек.";


mysql_query("DELETE FROM`chat_post` WHERE `room`= '$room[id]'");

mysql_query("INSERT INTO `chat_post` (`rebus_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('4', '$time', '$msg', '$room[id]', '$anagr_vopros[id]', '0')");

mysql_query("UPDATE `user` SET `balls` = '".($ank['balls']+$add_balls)."' WHERE `id` = '$ank[id]' LIMIT 1");


}
}
$rebus_last1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `rebus_st` = '1' ORDER BY id DESC"));
if ($rebus_last1!=NULL && $rebus_last['rebus_st']!=4 && $rebus_last1['time']<time()-$set['rebus_time'])
{
$rebus_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `rebus_vopros` WHERE `id` = '$rebus_last1[vopros]' LIMIT 1"));
$msg="На вопрос никто не ответил.\nПравильный ответ: $rebus_vopros[otvet].\nСледующий вопрос через $set[rebus_new] сек.";
mysql_query("INSERT INTO `chat_post` (`rebus_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('4', '$time', '$msg', '$room[id]', '$rebus_vopros[id]', '0')");
}
$rebus_last = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `rebus_st` <> '0' ORDER BY id DESC"));
if ($rebus_last==NULL || $rebus_last['rebus_st']==4 && $rebus_last['time']<time()-$set['rebus_new'])
{
// задается вопрос
$k_vopr=mysql_result(mysql_query("SELECT COUNT(*) FROM `rebus_vopros`"),0);
$rebus_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `rebus_vopros` LIMIT ".rand(0,$k_vopr).", 1"));
$msg="[b]Вопрос:[/b] \"$rebus_vopros[vopros]\"\n[b]Ответ:[/b] слово из ".strlen2($rebus_vopros['otvet'])." букв";
mysql_query("INSERT INTO `chat_post` (`rebus_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('1', '$time', '$msg', '$room[id]', '$rebus_vopros[id]', '0')");
}
if ($rebus_last!=NULL && $rebus_last['rebus_st']==1 && $rebus_last['time']<time()-$set['rebus_help'])
{
$rebus_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `rebus_vopros` WHERE `id` = '$rebus_last[vopros]' LIMIT 1"));
if (function_exists('iconv_substr'))
$help=iconv_substr($rebus_vopros['otvet'], 0, 1, 'utf-8');
else
$help=substr($rebus_vopros['otvet'], 0, 2);
for ($i=0;$i<strlen2($rebus_vopros['otvet'])-1 ;$i++ ) {
$help.='*';
}
$msg="[b]Вопрос:[/b] \"$rebus_vopros[vopros]\"\n[b]Первая подсказка:[/b] $help (".strlen2($rebus_vopros['otvet'])." букв)";
mysql_query("INSERT INTO `chat_post` (`rebud_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('2', '$time', '$msg', '$room[id]', '$rebus_vopros[id]', '0')");
}
if ($rebus_last!=NULL && $rebus_last['rebus_st']==2 && $rebus_last['time']<time()-$set['rebus_help'])
{
$rebus_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `rebus_vopros` WHERE `id` = '$rebus_last[vopros]' LIMIT 1"));
if (function_exists('iconv_substr'))
$help=iconv_substr($rebus_vopros['otvet'], 0, 2, 'utf-8');
else
$help=substr($rebus_vopros['otvet'], 0, 4);
for ($i=0;$i<strlen2($rebus_vopros['otvet'])-2 ;$i++ ) {
$help.='*';
}
$msg="[b]Вопрос:[/b] \"$rebus_vopros[vopros]\"\n[b]Вторая подсказка:[/b] $help (".strlen2($rebus_vopros['otvet'])." букв)";
mysql_query("INSERT INTO `chat_post` (`rebus_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('3', '$time', '$msg', '$room[id]', '$rebus_vopros[id]', '0')");
}
?>

<?
$anagrama_last = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `anagrama_st` <> '0' ORDER BY id DESC"));
if ($anagrama_last!=NULL && $anagrama_last['anagrama_st']!=4 && $anagrama_last['anagrama_st']!=0)
{
$anagrama_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_anagrama` WHERE `id` = '$anagrama_last[vopros]' LIMIT 1"));
$anagrama_post = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `msg` like '%$anagrama_vopros[otvet]%' AND `anagrama_st` = '0' AND `time` >= '".($time-$anagrama_last['time'])."' ORDER BY `id` ASC LIMIT 1"));
if($anagrama_post!=NULL){

$ank=mysql_fetch_assoc(mysql_query("SELECT * FROM `user` WHERE `id` = '$anagrama_post[id_user]' LIMIT 1"));


$add_balls=0;

if ($anagrama_last['anagrama_st']==1){$add_balls=25;}
if ($anagrama_last['anagrama_st']==2){$add_balls=10;}
if ($anagrama_last['anagrama_st']==3){$add_balls=5;}
$msg="Молодец, [b]$ank[nick][/b].\nТы первым дал верный ответ: [b]$anagrama_vopros[otvet][/b] .\n[b]$ank[nick][/b] получает $add_balls баллов.\nСледующий вопрос через $set[anagrama_new] сек.";
mysql_query("INSERT INTO `chat_post` (`anagrama_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('4', '$time', '$msg', '$room[id]', '$anagrama_vopros[id]', '0')");

mysql_query("UPDATE `user` SET `balls` = '".($ank['balls']+$add_balls)."' WHERE `id` = '$ank[id]' LIMIT 1");
mysql_query("UPDATE `user` SET `iqs` = '".($user['iqs']+$add_balls)."' WHERE `id` = '$user[id]' LIMIT 1");
}
}
$anagrama_last1 = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `anagrama_st` = '1' ORDER BY id DESC"));
if ($anagrama_last1!=NULL && $anagrama_last['anagrama_st']!=4 && $anagrama_last1['time']<time()-$set['anagrama_time'])
{
$anagrama_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_anagrama` WHERE `id` = '$anagrama_last1[vopros]' LIMIT 1"));



$msg="На вопрос никто не ответил.\nСледующий вопрос через $set[anagrama_new] сек.";
mysql_query("INSERT INTO `chat_post` (`anagrama_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('4', '$time', '$msg', '$room[id]', '$anagrama_vopros[id]', '0')");
}
$anagrama_last = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_post` WHERE `room` = '$room[id]' AND `anagrama_st` <> '0' ORDER BY id DESC"));
if ($anagrama_last==NULL || $anagrama_last['anagrama_st']==4 && $anagrama_last['time']<time()-$set['anagrama_new'])
{
// задается вопрос
$k_vopr=mysql_result(mysql_query("SELECT COUNT(*) FROM `chat_anagrama`"),0);
$anagrama_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_anagrama` LIMIT ".rand(0,$k_vopr).", 1"));
$arr = preg_split("//u", $anagrama_vopros['vopros'], -1, PREG_SPLIT_NO_EMPTY);
shuffle($arr);

$msg="[b]Анаграмма:[/b] ".implode('',$arr)."\n слово из ".strlen2($anagrama_vopros['otvet'])." букв";
mysql_query("INSERT INTO `chat_post` (`anagrama_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('1', '$time', '$msg', '$room[id]', '$anagrama_vopros[id]', '0')");
}
if ($anagrama_last!=NULL && $anagrama_last['anagrama_st']==1 && $anagrama_last['time']<time()-$set['anagrama_help'])
{
$anagrama_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_anagrama` WHERE `id` = '$anagrama_last[vopros]' LIMIT 1"));
if (function_exists('iconv_substr'))
$help=iconv_substr($anagrama_vopros['otvet'], 0, 1, 'utf-8');
else
$help=substr($anagrama_vopros['otvet'], 0, 2);
for ($i=0;$i<strlen2($anagrama_vopros['otvet'])-1 ;$i++ ) {
	$help.='*';
}

$msg="[b]Первая подсказка:[/b] $help (".strlen2($anagrama_vopros['otvet'])." букв)";
mysql_query("INSERT INTO `chat_post` (`anagrama_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('2', '$time', '$msg', '$room[id]', '$anagrama_vopros[id]', '0')");
}
if ($anagrama_last!=NULL && $anagrama_last['anagrama_st']==2 && $anagrama_last['time']<time()-$set['anagrama_help'])
{
$anagrama_vopros = mysql_fetch_assoc(mysql_query("SELECT * FROM `chat_anagrama` WHERE `id` = '$anagrama_last[vopros]' LIMIT 1"));
if (function_exists('iconv_substr'))
$help=iconv_substr($anagrama_vopros['otvet'], 0, 2, 'utf-8');
else
$help=substr($anagrama_vopros['otvet'], 0, 4);
for ($i=0;$i<strlen2($anagrama_vopros['otvet'])-2 ;$i++ ) {
	$help.='*';
}

$msg="[b]Вторая подсказка:[/b] $help (".strlen2($anagrama_vopros['otvet'])." букв)";
mysql_query("INSERT INTO `chat_post` (`anagrama_st`, `time`, `msg`, `room`, `vopros`, `privat`) values('3', '$time', '$msg', '$room[id]', '$anagrama_vopros[id]', '0')");
}
?>
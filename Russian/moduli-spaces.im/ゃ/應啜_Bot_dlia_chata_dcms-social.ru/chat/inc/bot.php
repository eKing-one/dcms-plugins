<?
$post=htmlspecialchars($_POST['msg']);
if ($post!=NULL){

$bot = mysql_fetch_assoc(mysql_query("SELECT * FROM `bot` WHERE `fraza` like '%$post%' ORDER BY RAND() LIMIT 1"));
$result=mysql_result(mysql_query("SELECT COUNT(*) FROM `bot` WHERE `fraza` like '%$post%'"),0);
$bot_otv=$bot['otvet'];


$b_otv=mysql_fetch_assoc(mysql_query("SELECT * FROM `bot_d` ORDER BY RAND() LIMIT 1"));
if ($bot_otv==NULL)
$bot_otv=$b_otv[post];

$bot_post=$user['nick'].', '.$bot_otv; mysql_query("INSERT INTO `chat_post` (`id_user`, `time`, `msg`, `room`, `privat`) values('138', '".$time."', '".$bot_post."', '".$room['id']."', '0')");
}

?>

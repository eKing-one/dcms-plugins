<?
if(isset($_POST['msg'])){

$post = $_POST['msg'];
$flirt = mysql_fetch_assoc(mysql_query("SELECT * FROM `flirt` WHERE `fraza` like '%$post%' ORDER BY RAND() LIMIT 1"));
$result=mysql_result(mysql_query("SELECT COUNT(*) FROM `flirt` WHERE `fraza` like '%$post%'"),0);
$flirt_otv=$flirt['otvet'];


$b_otv=mysql_fetch_assoc(mysql_query("SELECT * FROM `bot_d` ORDER BY RAND() LIMIT 1"));
if ($flirt_otv==NULL)
$flirt_otv=$b_otv[post];

$flirt_post=$user['nick'].', '.$flirt_otv; mysql_query("INSERT INTO `chat_post` (`id_user`, `time`, `msg`, `room`, `privat`) values('14', '".$time."', '".$flirt_post."', '".$room['id']."', '0')");
}
?>

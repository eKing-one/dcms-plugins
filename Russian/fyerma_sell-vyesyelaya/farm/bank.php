<?php
include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title']='农民银行'; // заголовок страницы
include_once '../sys/inc/thead.php';
title();
err();
aut();
if (isset($user)){
$action=htmlspecialchars(trim($_GET['action']));

switch ($action){

default:
echo '你好, '.$user['nick'].'! ';
echo '你的账户有:<br/>';
echo '硬币: '.$user['balls'].'<br/>';
echo '钱: '.$user['farm_gold'].'<br/>';
echo '1 比 100 的汇率<br/>';
echo '<form action="bank.php?action=change" method="post">';
echo '你想换多少硬币:<br/>';
echo '<input name="num" type="text" value=""/><br/>';
echo '<input type="submit" value="交换"/>';
echo '</form>';
echo '&raquo; <a href="change.php">汇款</a><br/>';
break;

case 'change':
$num=(int)$_POST['num'];
if(!$num || $num<1){echo 'П好参数！';break;};
if($user['balls']<$num){echo '你没有那么多硬币！ <br>&raquo;<a href="bank.php"> 回到银行</a><br/>';break;};
$baks=$num*100;
dbquery("UPDATE `user` SET `farm_gold`=`farm_gold`+'$baks',`balls`=`balls`-'$num' WHERE `id`='".$user['id']."'");
echo 'Обмен успешно завершен!';
echo '<br/>&raquo; <a href="bank.php">回到银行</a><br/>';
break;
};
echo "<div class='foot'>";
echo "&raquo; <a href='my.php'>我的农场。</a><br/>";
echo "&laquo; <a href='index.php'>后退</a><br/>";
echo "</div>";
}else{
echo '<div class="msg">农场仅供授权用户使用！</div>';};
include_once '../sys/inc/tfoot.php';
?>
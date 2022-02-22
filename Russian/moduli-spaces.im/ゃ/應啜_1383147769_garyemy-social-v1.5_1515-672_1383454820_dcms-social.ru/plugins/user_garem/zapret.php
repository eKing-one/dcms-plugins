<?php
  define('H', $_SERVER['DOCUMENT_ROOT'].'/');
  require H.'sys/inc/start.php'; $e = 1;
  require H.'sys/inc/compress.php';
  require H.'sys/inc/sess.php';
	require H.'sys/inc/settings.php';
	require H.'sys/inc/db_connect.php';
	require H.'sys/inc/ipua.php';
	require H.'sys/inc/fnc.php';
	require H.'sys/inc/user.php';
   $kraja = mysql_fetch_assoc(mysql_query("SELECT * FROM `cms_garem_zapret` WHERE `id_user` = '$user[id]' LIMIT 1"));
   $set['title']='Запретить кражу в гарем';
   require H.'sys/inc/thead.php';
	 title().aut().only_reg();
#-------------------------------------------------------------------------------
   $nizs = "<div class='foot'><img src='/style/icons/str2.gif' alt='i'> <a href='/id$user[id]'>$user[nick]</a> | <b>Запрет</b></div>\n";
#-------------------------------------------------------------------------------  
echo $nizs;

function wtime($timediff){
  $oneMinute=60;
   $oneHour=60*60;
    $oneDay=60*60*24;
   $dayfield=floor($timediff/$oneDay);
    $hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour);
     $minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute);
   $secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute));
     $time_1="$dayfield дней. $hourfield ч. $minutefield м. $secondfield сек.";
   return $time_1;
}

#-------------------------------------------------------------------------------
$sum = 5 ; $sum1 = 10 ; $sum2 = 15 ;
#-------------------------------------------------------------------------------
if (isset($_GET['clean'])){
if (isset($_GET['ok'])){
              mysql_query("DELETE FROM `cms_garem_zapret` WHERE `id_user` = '$user[id]'");
              $_SESSION['message']='Готово :)';
              header ("Location: ?");
}
?>
  <div class='mess'><center>
    Вы действительно хотите удалить <b>ЗАПРЕТ</b>?<br />
    [<a href='?clean&amp;ok'><img src='/style/icons/ok.gif'> удалить</a>] [<a href='?'><img src='/style/icons/delete.gif'> отмена</a>]
  </center></div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}
#-------------------------------------------------------------------------------
if (isset($_GET['ok']) && $kraja['time'] <= $time)
    {

  switch (isset($_GET['qaz'])?trim($_GET['qaz']):false){
     default: $a_g = $sum; $tim = 7 ;  break;
     case 1:  $a_g = $sum1; $tim = 14 ; break;
     case 2:  $a_g = $sum2;  $tim = 31 ;break;
}

if ($user['money'] < $a_g ){
?>
  <div class='mess'>
   У вас нету <?= $a_g ?> монет!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}

  mysql_query("UPDATE `user` SET `money` = '".($user['money']-$a_g)."' WHERE `id` = '$user[id]' LIMIT 1");
  mysql_query("INSERT INTO `cms_garem_zapret` (`id_user`, `time`) values('$user[id]', '".($time+$tim*60*60*24)."')");
   $_SESSION['message']='Готово :)';
   header ("Location: ?");
}elseif ($kraja['time']<=$time){
?>
  <div class='mess'><b>Запретить кражу:</b><br />
    на 7дн. 5м. [<a href='?ok'>Купить</a>]<br />
    на 14дн. 10м. [<a href='?ok&amp;qaz=1'>Купить</a>]<br />
    на 31дн. 15м. [<a href='?ok&amp;qaz=2'>Купить</a>]
  </div>
<?
}else{
?>
  <div class='mess'>
    - Осталось жить ещё <?=wtime($kraja['time']-time())?>
  </div>
  <div class='mess'>
   - <a href='?clean'>Удалить запрет</a> надоел хочу быть популярнее :)
  </div>
<?
}

echo $nizs;
	require H.'sys/inc/tfoot.php';
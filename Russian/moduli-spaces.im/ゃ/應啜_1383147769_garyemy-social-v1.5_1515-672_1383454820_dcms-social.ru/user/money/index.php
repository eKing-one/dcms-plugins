<?php
  define('H', $_SERVER['DOCUMENT_ROOT'].'/');
  require H.'sys/inc/start.php';
  require H.'sys/inc/compress.php';
  require H.'sys/inc/sess.php';
	require H.'sys/inc/settings.php';
	require H.'sys/inc/db_connect.php';
	require H.'sys/inc/ipua.php';
	require H.'sys/inc/fnc.php';
	require H.'sys/inc/user.php';
      $set['title']='Дополнительные услуги';
      require H.'sys/inc/thead.php';  title().aut();
      if (!isset($user))
               header("location: /");

   $c = mysql_result(mysql_query("SELECT COUNT(*) FROM `liders` WHERE `id_user` = '$user[id]' AND `time` > '$time'"),0);
   $c2 = mysql_result(mysql_query("SELECT COUNT(*) FROM `user_set` WHERE `id_user` = '$user[id]' AND `ocenka` > '$time'"), 0);
   $c3 = mysql_result(mysql_query("SELECT COUNT(*) FROM `zna4ki_pok` WHERE `id_user` = '$user[id]' AND `time` > '$time'"),0);
?>
   <div class='foot'>
    <img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'><?=$user['nick']?></a> | Доп. услуги
   </div>
   <div class='nav1'>
     <b>Личный счет:</b><br />
     - <b><font color='red'><?=$user['balls']?></font></b> баллов.<br />
     - <b><font color='green'><?=$user['money']?></font></b> <?=$sMonet[0]?>
   </div>
  <div class='nav2'>
   <font color='red'>&rarr; <a href='money.php'><font color='red'>Получить <?=$sMonet[2]?></font></a></font>
  </div>
  <div class='foot'>
    <b><font color='blue'>Услуги за</font> <?=$sMonet[2]?></b>
  </div>
  <div class="nav1">
  &rarr; <a href="liders.php">Лидер сайта</a> <span class="<?=($c == 0 ? 'off">[отключена]' : 'on">[включена]')?></span>
  </div>
  <div class="nav2">
  &rarr; <a href='plus5.php'>Оценка</a> <img src='/style/icons/6.png' alt='*'> <span class="<?=($c2==0?'off">[отключена]':'on">[включена]')?></span>
  </div>
  <div class='nav1'>&rarr; <a href='/plugins/user_garem/zapret/'>Запрет на кражу</a></div>

   <div class='foot'>
    <img src='/style/icons/str2.gif' alt='*'> <a href='/info.php'><?=$user['nick']?></a> | Доп. услуги
   </div>
<?
require H.'sys/inc/tfoot.php';
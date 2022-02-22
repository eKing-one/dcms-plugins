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

switch (isset($_GET['act'])?trim($_GET['act']):false){
default:
  $ank2 = get_user(intval($_GET['id']));
  $ank_id = $ank2['id'];
  if (!$ank2)header ("Location: /"); // если нет такого пользователя
   $set['title']="Гарем: $ank2[nick]";
   require H.'sys/inc/thead.php';
   title().aut();

// Удадение
if (isset($_GET['del']) && $ank2['id']==$user['id'] && mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_garem` = '$user[id]' AND `id_user` = '".intval($_GET['del'])."'"),0))
{
  $del = intval($_GET['del']);
  $ank_del = get_user($del);
  
if (isset($_GET['ok'])){
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_garem` = '$ank_id' AND `id_user` = '$del'"),0)==1){
              mysql_query("DELETE FROM `cms_garem` WHERE `id_garem` = '$ank_id' AND `id_user` = '$del'");
              mysql_query("UPDATE `user_set` SET `garem_uk` = `garem_uk`-1 WHERE `id_user` = '$user[id]' LIMIT 1"); // -1 счётчик людей
              mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$del', '$del', 'garem_del', '$time')");
   $_SESSION['message']=$ank_del['nick'].' удален(а) из гарема';
   header ("Location: ?");
  }
}
?>
  <div class='mess'><center>
    Вы действительно хотите удалить <b><?= $ank_del['nick'] ?></b>?<br />
    [<a href='?del=<?= $del ?>&amp;ok'><img src='/style/icons/ok.gif'> удалить</a>] [<a href='?'><img src='/style/icons/delete.gif'> отмена</a>]
  </center></div>
<?
   require H.'sys/inc/tfoot.php';
}
#-------------------------------------------------------------------------------
   $nizs = "<div class='foot'><img src='/style/icons/str2.gif' alt='i'> <a href='/id$ank_id'>$ank2[nick]</a> | <b>Гарем</b></div>\n";
#-------------------------------------------------------------------------------  
echo $nizs;
  $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_garem` = $ank_id"),0);
  $k_page = k_page($k_post,$set['p_str']);
  $page = page($k_page);
  $start = $set['p_str'] * $page - $set['p_str'];
  
if (!$k_post)
    echo "<div class='mess'>Гарем пуст :(</div>\n";


$q = mysql_query("SELECT `id_user`,`id`,`id_garem`,`time` FROM `cms_garem` WHERE `id_garem` = $ank_id ORDER BY id DESC LIMIT $start, $set[p_str]");

while ($post = mysql_fetch_assoc($q))
{
	$ank = get_user($post['id_user']);
  $gar_del = $ank_id==$user['id']?"<a href='?del=$ank[id]'><img style='float: right;' src='/style/icons/del.png'></a>":null;

   echo "<div class='nav".($e++%2?'1':'2')."'>";
   echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a> ".online($ank['id']).medal($ank['id'])." (".vremja($post['time']).")";
   echo "$gar_del</div>\n";
}

if ($k_page > 1)
       str('?',$k_page,$page);
echo $nizs;
break;
################################################################################
case 'exit':
  $set['title']='Покинуть гарем';
  require H.'sys/inc/thead.php';
  title().aut().only_reg();
        $file_id = mysql_fetch_assoc(mysql_query("SELECT * FROM `cms_garem` WHERE `id_user` = '$user[id]' LIMIT 1"));
        $id_garem = $file_id['id_garem'] == 0 ? null : $file_id['id_garem'] ;
//------------------------------------------------------------------------------
if (mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user` = '$user[id]'"),0)==0){
?>
  <div class='mess'>
   Вас нет в гаремах!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}

if (isset($_GET['go'])){
if ($user['money'] < 5 ){
?>
  <div class='mess'>
   У вас нету 5 монет!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}

       if ($id_garem != 0){
       mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$id_garem', '$id_garem', 'garem_exit', '$time')");
       mysql_query("UPDATE `user_set` SET `garem_uk` = `garem_uk`-1 WHERE `id_user` = '$id_garem' LIMIT 1");
                          }
        mysql_query("UPDATE `user` SET `money` = '".($user['money']-5)."' WHERE `id` = '$user[id]' LIMIT 1");
        mysql_query("DELETE FROM `cms_garem` WHERE `id_user` = '$user[id]'");
  $_SESSION['message']='Готово';
  header ("Location: /id$user[id]");
}
?>
<div class='mess'><a href='?go'>Выкупить себя</a> [5 монет]</div>
<?
break;
################################################################################
case 'kupit':
  $ank = get_user(intval($_GET['id']));
  if (!$ank)
        only_reg(); // если нет такого пользователя
   $uSet = mysql_fetch_array(mysql_query("SELECT * FROM `user_set` WHERE `id_user` = '$ank[id]' LIMIT 1"));
   $cost = $uSet['cost']*100+100; // 
  $set['title']="Кража: $ank[nick]";
  require H.'sys/inc/thead.php';
  title().aut().only_reg();
?>
  <div class='foot'>
   <img src='/style/icons/str2.gif' alt='i'> <a href="/id<?=$ank['id']?>"><?=$ank['nick']?></a> | <b>Кража в гарем</b>
  </div>
<?

   $kraja = mysql_fetch_assoc(mysql_query("SELECT * FROM `cms_garem_zapret` WHERE `id_user` = '$ank[id]' LIMIT 1"));


if ($kraja['time'] >= $time){
?>
  <div class='mess'>
   Данный пользователь запретил красть себя в гареме!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}elseif (mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user` = '$ank[id]' AND `id_garem` = '$user[id]'"),0)==1){
?>
  <div class='mess'>
   Данный пользователь уже у вас в гареме!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}elseif ($user['pol'] == $ank['pol']){
?>
  <div class='mess'>
   Разрешено красть только людей противоположного пола!
  </div>
<?
  require H.'sys/inc/tfoot.php';
  exit;
}elseif (isset($_POST['ok'])){
   if ($user['balls'] < $cost)$err[] = "У вас нет $cost"."б.!";
   if (!isset($err)){
    $file_id = mysql_fetch_assoc(mysql_query("SELECT * FROM `cms_garem` WHERE `id_user` =  '$ank[id]' LIMIT 1"));
    $id_garem = $file_id['id_garem'] == 0 ? null : $file_id['id_garem'] ;

if (mysql_result(mysql_query("SELECT COUNT(*) FROM `cms_garem` WHERE `id_user` = '$ank[id]'"),0)==1)
                                              mysql_query("DELETE FROM `cms_garem` WHERE `id_user` = '$ank[id]'");
                                              
   if ($id_garem != 0){
       mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$id_garem', '$id_garem', 'garem_yk', '$time')");
       mysql_query("UPDATE `user_set` SET `garem_uk` = `garem_uk`-1 WHERE `id_user` = '$id_garem' LIMIT 1");
   }
     mysql_query("UPDATE `user` SET `balls` = '".($user['balls']-$cost)."' WHERE `id` = '$user[id]' LIMIT 1");
     mysql_query("UPDATE `user_set` SET `garem_uk`=`garem_uk`+1 WHERE `id_user` = '$user[id]' LIMIT 1"); // +1 счётчик людей
     mysql_query("INSERT INTO `cms_garem` (id_garem, id_user, time) values('$user[id]', '$ank[id]', '$time')"); // создаём табличку с кражей
     mysql_query("UPDATE `user_set` SET `cost`=`cost`+1 WHERE `id_user` = '$ank[id]' LIMIT 1"); // записываем +1 кража
      		mysql_query("INSERT INTO `notification` (`avtor`, `id_user`, `id_object`, `type`, `time`) VALUES ('$user[id]', '$ank[id]', '$ank[id]', 'garem_kraj', '$time')");
  $_SESSION['message']='Готово';
  header ("Location: /id$ank[id]");
}
}
  err();

?>
  <div class='mess'>Украсть в гарем <b><?=$ank['nick']?></b> стоит <?=$cost?> баллов<br /> У Вас <?=$user['balls']?> баллов</div>
  <form class="mess" action="" method="POST"><input type="submit" name="ok" value="Украсть"></form>

  <div class='foot'>
   <img src='/style/icons/str2.gif' alt='i'> <a href="/id<?=$ank['id']?>"><?=$ank['nick']?></a> | <b>Кража в гарем</b>
  </div>
<?
break;
################################################################################
case 'top':
  $set['title']='Рейтинг гаремов';
  require H.'sys/inc/thead.php';
  title().aut();
//------------------------------------------------------------------------------
        switch(isset($_GET['sor'])?intval($_GET['sor']):false)
        {
            case 1:  $enk = 'garem_uk';     $d = 'sor=1&amp;';  break;
            default: $enk = 'cost';         $d = false;         break;
        }
//------------------------------------------------------------------------------
?><div class='mess'><center><?
  echo (isset($_GET['sor'])==0?"Дорогие":"<a href='?'>Дорогие</a>")." | ".(isset($_GET['sor']) && $_GET['sor']==1?"Гаремы гиганты</a>":"<a href='?sor=1'>Гаремы гиганты</a>");
?></center></div><?
//------------------------------------------------------------------------------
  $k_post=mysql_result(mysql_query("SELECT COUNT(*) FROM `user_set` WHERE `$enk` > '0'"),0);
  $k_page=k_page($k_post,$set['p_str']);
  $page=page($k_page);
  $start=$set['p_str']*$page-$set['p_str'];

if (!$k_post)
    echo "<div class='mess'>Пусто :(</div>\n";

$q=mysql_query("SELECT `id_user`,`cost`,`garem_uk` FROM `user_set` WHERE `$enk` > '0' ORDER BY `$enk` DESC LIMIT $start, $set[p_str]");
while ($post=mysql_fetch_assoc($q)){
   $ank = get_user($post['id_user']);
    echo "<div class='nav".($e++%2?'1':'2')."'>";
    echo group($ank['id'])." <a href='/info.php?id=$ank[id]'>$ank[nick]</a> ".online($ank['id'])." ".medal($ank['id'])."\n";

        switch(isset($_GET['sor'])?intval($_GET['sor']):false)
        {
            case 1:  echo "В гареме: $post[garem_uk] чел."; break;
            default: echo ($post['cost']*100+100)." бал."; break;
        }
echo '</div>';
}
if ($k_page > 1)
       str("?$d",$k_page,$page);
break;
}
require H.'sys/inc/tfoot.php';
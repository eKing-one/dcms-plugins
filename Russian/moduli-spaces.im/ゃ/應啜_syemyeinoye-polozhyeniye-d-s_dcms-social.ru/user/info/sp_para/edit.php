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
   $set['title']='Семейное положение';
   require H.'sys/inc/thead.php';
   title().aut().only_reg();

?>
  <div class='foot'>
   <img src='/style/icons/str2.gif' alt='*'> <a href='/id<?=$user['id']?>'><?=$user['nick']?></a> | <b>СП</b>
  </div>
<?

if (isset($_POST['edit'])){
    if ($user['sem_poloj'] != $_POST['sem_poloj'])
		      mysql_query("UPDATE `user` SET `sem_poloj` = '".intval($_POST['sem_poloj'])."' WHERE `id` = '$user[id]' LIMIT 1");
    if ($user['id_sp'] != $_POST['id_sp'])
		      mysql_query("UPDATE `user` SET `id_sp` = '".intval($_POST['id_sp'])."' WHERE `id` = '$user[id]' LIMIT 1");
           $_SESSION['message'] = 'Изменения успешно приняты';
           header("Location: /id$user[id]");
}
//==============================================================================
   $man = array('Не скажу','Женат','Не женат','Влюблён','Есть девушка','В активном поиске','В пассивном поиске','Всё сложно','Всё просто я люблю, меня -нет:(','Тащусь','Не могу забыть','Хочу жить','Просто балдею','По уши влюблён','Жить не могу');
   $girls = array('Не скажу','Замужем','Не замужем','Влюблена','Есть парень','В активном поиске','В пассивном поиске','Всё сложно','Всё просто я люблю, меня -нет:(','Тащусь','Не могу забыть','Хочу жить','Просто балдею','По уши влюблена','Жить не могу');
//==============================================================================
?>
  <form class='mess' action="" method="POST">
   <b>Семейное положение:</b><br />
   <select name='sem_poloj'>
   <? for ($i=0;$i<=14;$i++){echo "<option value='$i'".($user['sem_poloj']==$i?" selected='selected'":null).">".($user['pol']==0?$girls[$i]:$man[$i])."</option>";} ?>
   </select><br />
    <b>Твой партнёр:</b><br /><select name='id_sp'>
     <option value='0'<?=($user['id_sp']==0?" selected='selected'":null)?>>---------------</option>
<?
$q = mysql_query("SELECT * FROM `frends` INNER JOIN `user` ON `frends`.`frend`=`user`.`id` WHERE `frends`.`user` = '$user[id]' AND `frends`.`i` = '1' AND `user`.`pol` = '".($user['pol']?0:1)."' ORDER BY `user`.`id`");
while ($friends = mysql_fetch_array($q))
{
$ank2= get_user($friends['frend']);
echo "<option value='$ank2[id]'".($user['id_sp']==$ank2['id']?" selected='selected'":null).">$ank2[nick]</option>\n";
}
  ?>
   </select><br />
   <input type="submit" name="edit" value="Сохранить">
	</form>

  <div class='foot'>
   <img src='/style/icons/str2.gif' alt='*'> <a href='/id<?=$user['id']?>'><?=$user['nick']?></a> | <b>СП</b>
  </div>
<?
require H.'sys/inc/tfoot.php';
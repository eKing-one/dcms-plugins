<?
  $para = get_user($ank['id_sp']); // определяем ID
      $man = array('Не скажу','Женат','Не женат','Влюблён','Есть девушка','В активном поиске','В пассивном поиске','Всё сложно','Всё просто я люблю, меня -нет:(','Тащусь','Не могу забыть','Хочу жить','Просто балдею','По уши влюблён','Жить не могу');
      $girls = array('Не скажу','Замужем','Не замужем','Влюблена','Есть парень','В активном поиске','В пассивном поиске','Всё сложно','Всё просто я люблю, меня -нет:(','Тащусь','Не могу забыть','Хочу жить','Просто балдею','По уши влюблена','Жить не могу');

?>
  <div class='nav2'>
    <img src='/style/icons/sp.png' alt='*'>  <?=($user['id']==$ank['id']?"<a href='/user/info/sp_para/edit.php'>":null)."Семейное положение:".($user['id']==$ank['id']?"</a>":null)." ".($ank['pol']?$man[$ank['sem_poloj']]:$girls[$ank['sem_poloj']])?>
<?
  if ($ank['id_sp']){
   echo ($ank['sem_poloj']==1?' за ':null).($ank['sem_poloj']==3?' в ':null).($ank['sem_poloj']==4?' ':null).($ank['sem_poloj']==7?' с ':null).($ank['sem_poloj']==9?' от ':null).($ank['sem_poloj']==10?' ':null).($ank['sem_poloj']==11?' с ':null).($ank['sem_poloj']==12?' от ':null).($ank['sem_poloj']==13?' в ':null).($ank['sem_poloj']==14?' без ':null);
   echo ($ank['sem_poloj']==1 OR $ank['sem_poloj']==3 OR $ank['sem_poloj']==4 OR $ank['sem_poloj']==7 OR $ank['sem_poloj']==9 OR $ank['sem_poloj']==10 OR $ank['sem_poloj']==11 OR $ank['sem_poloj']==12 OR $ank['sem_poloj']==13 OR $ank['sem_poloj']==14)?"<a href='/id$para[id]'>$para[nick]</a>":null;
}
?>
  </div>
<?php

###################################################
#   Фотоконкурсы под dcms 6.6.4 и 6.7.7           #
#   Автор: Nort, он же Lns                        #
#   icq: 484014288, сайт: http://inwap.org        #
#                                                 #
#   Вы не имеете права продавать, распростронять, #
#   давать друзьям даный скрипт.                  #
#                                                 #
#   Даная версия являет платной, и купить         #
#   можно только у автора.                        #
###################################################

include_once '../sys/inc/start.php';
include_once '../sys/inc/compress.php';
include_once '../sys/inc/sess.php';
include_once '../sys/inc/home.php';
include_once '../sys/inc/settings.php';
include_once '../sys/inc/db_connect.php';
include_once '../sys/inc/ipua.php';
include_once '../sys/inc/fnc.php';
include_once '../sys/inc/user.php';

$set['title']= 'Фотоконкурсы';
include_once '../sys/inc/thead.php';

include_once 'inc.php';

title();
aut();
only_reg();

# ===================================================================

  # Функция перенаправления
  
  function loc($url)
  
  {
  
    header('Location:'.$url);
  
?>

    <html>
    <head>
    <script type="text/javascript">window.location.href="<?=$url?>"</script>
    </head>
    <body>
    <div class="link">
    Извените, но скорее всего Ваш браузер не поддерживает перенаправление. Включите в настройках "Автоматическое перенаправление" или "javascript".<br/>
    Пожалуйста, перейдите самостоятельно по <a href="<?=$url?>">ссылке</a><br/>
    </div>
    </body>
    </html>  
<?  

}
  
  # -----------------------
  
  $ID = abs(intval($_GET['ID']));
  $Photo = mysql_fetch_array(mysql_query("select * from `FotoKonkursUser` WHERE `id` = '".$ID."'"));
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$Photo['konkurs_id']."'"));
  $U = mysql_fetch_array(mysql_query("select * from `user` WHERE `id` = '".$Photo['user_id']."'"));
  $RatingUser = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
  $RatingNum = mysql_num_rows(mysql_query("select * from `FotoKonkursRating` WHERE `photo_id` = '".$ID."'"));
  $Comments = mysql_num_rows(mysql_query("select * from `FotoKonkursComments` WHERE `photo_id` = '".$ID."'"));
  
  if (isset($_GET['M']) && $user['level']>3) {
  
    $M = htmlspecialchars(trim($_GET['M']));
  
    switch ($M) {
  
      default:
	  break;
	
	  case 'Yes':
	  mysql_query("UPDATE `FotoKonkursUser` SET `status` = '0' WHERE `id` = '".$ID."' LIMIT 1");
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	  break;
	  
	  case 'No':
	  mysql_query("UPDATE `FotoKonkursUser` SET `status` = '1' WHERE `id` = '".$ID."' LIMIT 1");
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	  break;
  
    }
  
  }

  if (isset($_GET['rating']) && $user['id']!=$Photo['user_id'] && $Photo['status']=='0') {
  
  /* ------------------------------------------- */
  
  $rating = htmlspecialchars(trim($_GET['rating']));
  
  switch ($rating) {
  
    default:
	break;
	
	case '1':
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '1';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	break;
	
	case '2':
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '2';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	break;
	
	case '3':
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '3';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	break;
	
	case '4':
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '4';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	break;
	
	case '5':
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '5';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	break;
	
	case '25':
	if ($user['balls']<100) {
	
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	
	}else{
	$RAT = mysql_fetch_array(mysql_query("select * from `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."'"));
	$case = '25';
	  if (!isset($RAT['rating'])) {
	  
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
		 
	  }else{
	  
	     mysql_query("DELETE FROM `FotoKonkursRating` WHERE `user_id` = '".$user['id']."' and `photo_id` = '".$ID."' LIMIT 1");
	     mysql_query("INSERT INTO `FotoKonkursRating` (`user_id`, `photo_id`, `rating`, `time`, `date`) VALUES ('".$user['id']."', '".$ID."', '".$case."', '".time()."', '".$date."');");
		 mysql_query("UPDATE `FotoKonkursUser` SET `rating` = `rating` - '".$RAT['rating']."', `rating` = `rating` + '".$case."' WHERE `id` = '".$ID."' LIMIT 1");
	  
	  }
	  mysql_query("UPDATE `user` SET `balls` = `balls` - '100' WHERE `id` = '".$user['id']."' LIMIT 1");
	  loc('/FotoKonkurs/Photo.php?ID='.$ID);
	 }
	break;
  
  }
  
  /* ------------------------------------------- */
  
  }
  
  if (!isset($Konkurs['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'">'.text_out($Photo['name']).'</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Photo['name'])) echo $div_link.'Такой фотографии не существует.<br/></div>';
   else
  
  {
  
          $img = getimagesize('Files/Original/'.$Photo['id'].'_'.$Photo['foto']);
  
          echo $div_link;
          echo '<img src="Files/Wiev/'.$Photo['id'].'_'.$Photo['foto'].'" alt=""/><br/>';
		  echo '<img src="img/zoom.png" alt=""/> <a href="Files/Original/'.$Photo['id'].'_'.$Photo['foto'].'">Посмотреть оригинал</a> ('.$img[0].'x'.$img[1].')<br/>';
		  echo '</div>';
		  echo $div_link;
		  echo '<img src="img/foto.png" alt=""/> <b>'.text_out($Photo['name']).'</b> ('.($U['pol']=='0'?'Добавила':'Добавил').': <b>'.$U['nick'].'</b>)<br/>';
		  echo '<img src="img/comments.png" alt=""/> <a href="/FotoKonkurs/Comments.php?ID='.$Photo['id'].'">Обсудить</a> ('.$Comments.')<br/>';
		  echo '<img src="img/rating.png" alt=""/> <font color="green">Рейтинг:</font> '.$Photo['rating'];
		  
  if ($Photo['user_id']!==$user['id'] && $Photo['status']=='0')
  
  {
  
    echo ' &raquo; ';
    
	if (!isset($RatingUser['rating']))
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='1')
	
	{
	
      echo '<b>+1</b>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='2')
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<b>+2</b>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='3')
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<b>+3</b>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='4')
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<b>+4</b>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='5')
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<b>+5</b>';
	  echo ' | ';
	  echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=25"><font color="red">+25</font></a> (<b>-</b>100 баллов.)';
	
	}
	
	if (isset($RatingUser['rating']) && $RatingUser['rating']=='25')
	
	{
	
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=1">+1</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=2">+2</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=3">+3</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=4">+4</a>';
      echo ' | ';
      echo '<a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;rating=5">+5</a>';
	  echo ' | ';
	  echo '<b>+25</b>';
	
	}
  
  }
          
		  echo '<br/>';
		  echo '<img src="img/user.png" alt=""/> <a href="/FotoKonkurs/RatingList.php?ID='.$ID.'">Кто голосовал?</a> ('.$RatingNum.' чел.)<br/>';
		  
		  if ($user['level']>3 || $Photo['user_id']==$user['id']) echo '<img src="img/Delete.png" alt=""/> <a href="/FotoKonkurs/DeletePhoto.php?ID='.$ID.'">Удалить</a> / <a href="/FotoKonkurs/EditePhoto.php?ID='.$ID.'">Изменить</a><br/>';
		  
		  if ($Photo['status']=='1') echo '<font color="red">Фото на модерации.</font>';
		  if ($Photo['status']=='1' && $user['level']>3) echo ' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;M=Yes">Допустить к голосованию</a>';
		  if ($Photo['status']=='1') echo '<br/>';
		  
		  if ($Photo['status']=='0') echo '<font color="green">Фото допущено до голосования.</font>';
		  if ($Photo['status']=='0' && $user['level']>3) echo ' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'&amp;M=No">Запретить учавствовать</a>';
		  if ($Photo['status']=='0') echo '<br/>';
		
		  echo '</div>';
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
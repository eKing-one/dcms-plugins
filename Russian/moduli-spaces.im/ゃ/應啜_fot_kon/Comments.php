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
  
  $ID = abs(intval($_GET['ID']));
  $Photo = mysql_fetch_array(mysql_query("select * from `FotoKonkursUser` WHERE `id` = '".$ID."'"));
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$Photo['konkurs_id']."'"));
  
  if (!isset($Konkurs['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'">'.text_out($Photo['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/Comments.php?ID='.$ID.'">Обсудить</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Photo['name'])) echo $div_link.'Такой фотографии не существует.<br/></div>';
   else
  
  {
  
     if ($Photo['komm']=='1') echo $div_link.'Извените, но автор фотографии запретил обсуждать фото.<br/></div>';
	  else
	  
	 {
	 
	   if (isset($_POST['submit']) && strlen(trim($_POST['komm']))>2 && strlen(trim($_POST['komm']))<512) {
	   
	     mysql_query("INSERT INTO `FotoKonkursComments` (`user_id`, `photo_id`, `komm`, `time`) VALUES ('".$user['id']."', '".$ID."', '".mysql_real_escape_string($_POST['komm'])."', '".time()."');");
		 
		 loc('/FotoKonkurs/Comments.php?ID='.$ID);
		 
		 include_once '../sys/inc/tfoot.php';
		 exit;
	   
	   }
	   
	   if (isset($_GET['Delete']) && $user['level']>3) {
	   
	      mysql_query("DELETE FROM `FotoKonkursComments` WHERE `id` = '".abs(intval($_GET['Delete']))."' LIMIT 1");
	   
	      loc('/FotoKonkurs/Comments.php?ID='.$ID);
		 
		  include_once '../sys/inc/tfoot.php';
		  exit;
	   
	   }

       echo $div_name;
	   echo '<form method="post">
	         Сообщение (2 - 512 симв.)<br/>
		     <textarea name="komm" cols="25" rows="3"></textarea><br/>
	         <input type="submit" name="submit" value="Добавить комментарий"/></form>';
	   echo '</div>';
	   
      $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `FotoKonkursComments` WHERE `photo_id` = '".$ID."'"),0);
      $k_page = k_page($k_post,$FotoStr);
      $page = page($k_page);
      $start = $FotoStr * $page - $FotoStr;
      $res = mysql_query("select * from `FotoKonkursComments` WHERE `photo_id` = '".$ID."' order by time desc LIMIT $start, $FotoStr");
	  
	  if ($k_post=='0') echo $div_link.'Нет комментариев...<br/></div>';
	   else
	  
	  {
	  
	    while ($post = mysql_fetch_array($res))
	  
	    {
		
		  $U = mysql_fetch_array(mysql_query("select * from `user` WHERE `id` = '".$post['user_id']."'"));
	  
		  $i++;
	
          if ($i%2){ echo $div_zebr1; }
	        else
		  { echo $div_zebr2; }
			
          echo ($U['pol']=='1'?'<img src="img/pol_1.png" alt=""/>':'<img src="img/pol_0.png" alt=""/>').' <a href="/info.php?id='.$U['id'].'">'.$U['nick'].'</a> '.online($U['id']).' ['.vremja($post['time']).']';
		  
		    if ($user['level']>3) echo ' <a href="/FotoKonkurs/Comments.php?ID='.$ID.'&amp;Delete='.$post['id'].'"><img src="img/delete.png" alt=""/></a>'; 
			
		  echo '<br/>';
		  echo output_text($post['komm']).'<br/>';
		  
		  echo '</div>';
	  
	    }
	  
	  if ($k_page>1)str('?ID='.$ID,$k_page,$page);
	  
	  }
	  
	  ################
	 
	 }
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
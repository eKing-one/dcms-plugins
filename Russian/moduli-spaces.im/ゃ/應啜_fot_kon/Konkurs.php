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

  $ID = abs(intval($_GET['ID']));
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$ID."'"));
  $NumUser = mysql_num_rows(mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."'"));
  $KonkursUser = mysql_num_rows(mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."' and `user_id` = '".$user['id']."'"));
  
  if (!isset($Konkurs['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$ID.'">'.text_out($Konkurs['name']).'</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Konkurs['name'])) echo $div_link.'Такого фотоконкурса не существует.<br/></div>';
   else
  
  {
  
    echo $div_name;
	echo '<font color="red">Могут учавствовать:</font> ';
		if ($Konkurs['pol']=='1') echo '<b>Все</b>';
		if ($Konkurs['pol']=='2') echo '<b>Только парни</b>';
		if ($Konkurs['pol']=='3') echo '<b>Только девушки</b>';
	echo '<br/>';
	echo '<font color="red">Участников:</font> <b>'.$NumUser.'</b><br/>';
    echo '<img src="img/time.png" alt=""/> <u>Дата начала:</u> '.text_out($Konkurs['date_on2']).'<br/>';
	echo '<img src="img/time.png" alt=""/> <u>Дата окончания:</u> '.text_out($Konkurs['date_off2']).'<br/>';
	echo '<img src="img/time.png" alt=""/> <u>Разрешено голосовать:</u> '.text_out($Konkurs['date_golos2']).'<br/>';
	    if ($Konkurs['date_off']>$Date2 && $Konkurs['date_golos']<$Date2 && $Konkurs['date_on']<$Date2 && $KonkursUser==0) echo $Link.' <a href="/FotoKonkurs/In.php?ID='.$ID.'">Принять участие</a><br/>';
	echo '</div>';
	
      $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."'"),0);
      $k_page = k_page($k_post,$FotoStr);
      $page = page($k_page);
      $start = $FotoStr * $page - $FotoStr;
      $res = mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."' order by rating desc LIMIT $start, $FotoStr");
	  
	  if ($k_post=='0') echo $div_link.'Нет участников...<br/></div>';
	   else
	  
	  {
	  
	    while ($post = mysql_fetch_array($res))
	  
	    {
		
		  $U = mysql_fetch_array(mysql_query("select * from `user` WHERE `id` = '".$post['user_id']."'"));
		  
		  $Comments = mysql_num_rows(mysql_query("select * from `FotoKonkursComments` WHERE `photo_id` = '".$post['id']."'"));
	  
		  $i++;
	
          if ($i%2){ echo $div_zebr1; }
	        else
		  { echo $div_zebr2; }
			
          echo '<img src="Files/Wiev/'.$post['id'].'_'.$post['foto'].'" alt=""/><br/>';
		  echo '<img src="img/foto.png" alt=""/> <a href="/FotoKonkurs/Photo.php?ID='.$post['id'].'">'.text_out($post['name']).'</a> ('.($U['pol']=='0'?'Добавила':'Добавил').': <b>'.$U['nick'].'</b>)<br/>';
		  echo '<img src="img/comments.png" alt=""/> <a href="/FotoKonkurs/Comments.php?ID='.$post['id'].'">Обсудить</a> ('.$Comments.')<br/>';
		  echo '<img src="img/rating.png" alt=""/> <font color="green">Рейтинг:</font> '.$post['rating'].'<br/>';
		  echo '</div>';
	  
	    }
	  
	  if ($k_page>1)str('?ID='.$ID.'&amp;',$k_page,$page);
	  
	  }
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
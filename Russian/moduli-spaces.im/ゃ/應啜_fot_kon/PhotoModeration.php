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

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' <a href="/FotoKonkurs/PhotoModeration.php">Фотографии на модерации</a><br/>';
  echo '</div>';
  
      $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `FotoKonkursUser` WHERE `status` = '1'"),0);
      $k_page = k_page($k_post,$FotoStr);
      $page = page($k_page);
      $start = $FotoStr * $page - $FotoStr;
      $res = mysql_query("select * from `FotoKonkursUser` WHERE `status` = '1' order by time desc LIMIT $start, $FotoStr");
	  
	  if ($k_post=='0') echo $div_link.'Нет конкурсов...<br/></div>';
	   else
	  
	  {
	  
	    while ($post = mysql_fetch_array($res))
	  
	    {
		
		  $U = mysql_fetch_array(mysql_query("select * from `user` WHERE `id` = '".$post['user_id']."'"));
		  $F = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$post['konkurs_id']."'"));
	  
		  $i++;
	
          if ($i%2){ echo $div_zebr1; }
	        else
		  { echo $div_zebr2; }
			
          echo '<a href="/info.php?id='.$U['id'].'">'.$U['nick'].'</a> ждет пока Вы проверите его <a href="/FotoKonkurs/Photo.php?ID='.$post['id'].'">фотографию</a>.<br/>';
		  echo '<img src="img/foto.png" alt=""/> Фотоконкурс: <a href="/FotoKonkurs/Konkurs.php?ID='.$F['id'].'">'.text_out($F['name']).'</a><br/>';
		  
		  echo '</div>';
	  
	    }
	  
	  if ($k_page>1)str('?',$k_page,$page);
	  
	  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
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
  $Photo = mysql_fetch_array(mysql_query("select * from `FotoKonkursUser` WHERE `id` = '".$ID."'"));
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$Photo['konkurs_id']."'"));
  
  if (!isset($Photo['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'">'.text_out($Photo['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/RatingList.php?ID='.$ID.'">Кто голосовал?</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Photo['name'])) echo $div_link.'Такой фотографии не существует.<br/></div>';
   else
  
  {

    $List = mysql_query("select * from `FotoKonkursRating` WHERE `photo_id` = '".$ID."' order by time desc");
	$ListN = mysql_num_rows(mysql_query("select * from `FotoKonkursRating` WHERE `photo_id` = '".$ID."'"));
	
	while ($List2 = mysql_fetch_array($List)) { $U = mysql_fetch_array(mysql_query("select * from `user` WHERE `id` = '".$List2['user_id']."'")); $ListUser[] = '<a href="/info.php?id='.$List2['user_id'].'">'.$U['nick'].'</a> (<font color="green">+'.$List2['rating'].'</font>, '.vremja($List2['time']).')'; }
	
	echo $div_link;
	
	  if ($ListN == 0) echo 'За фото пока что ни кто не голосовал.<br/>';
	   else
      echo implode(', ', $ListUser).'.';
	  
    echo '</div>';
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
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
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$ID."'"));
  
  if ($user['level']<4) { loc('/FotoKonkurs'); exit; }
  
  if (!isset($Konkurs['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/DeleteKonkurs.php?ID='.$ID.'">Удалить</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Konkurs['name'])) echo $div_link.'Такого фотоконкурса не существует.<br/></div>';
   else
  
  {
  
    if (isset($_POST['submit_yes'])) {
	
	  echo $div_link.'Фотоконкурс успешно удален.<br/></div>';
	  
	  $ListPhoto = mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."'");
	  
	  while ($List = mysql_fetch_array($ListPhoto)) {
	  
	    $ListComments = mysql_query("select * from `FotoKonkursComments` WHERE `photo_id` = '".$List['id']."'");
		$ListRating = mysql_query("select * from `FotoKonkursRating` WHERE `photo_id` = '".$List['id']."'");
		
		  while ($List3 = mysql_fetch_array($ListComments)) { mysql_query("DELETE FROM `FotoKonkursComments` WHERE `id` = '".$List3['id']."'"); }
	      while ($List2 = mysql_fetch_array($ListRating)) { mysql_query("DELETE FROM `FotoKonkursRating` WHERE `id` = '".$List2['id']."'"); }
		
	    unlink('Files/Wiev/'.$List['id'].'_'.$list['foto']);
	    unlink('Files/Original/'.$List['id'].'_'.$List['foto']);
		
		mysql_query("DELETE FROM `FotoKonkursUser` WHERE `id` = '".$List['id']."'");
	  
	  }
	  
	  mysql_query("DELETE FROM `FotoKonkurs` WHERE `id` = '".$ID."'");
	
	}else{

	   echo $div_link;
       echo '<font color="red">Вы действительно хотите удалить фотоконкурс?</font><br/>
	         <form method="post">
	         <input type="submit" name="submit_yes" value="Да, я хочу удалить фотоконкурс"/></form>
			 <form action="/FotoKonkurs" method="post">
	         <input type="submit" value="Нет, я '.($user['pol']=='1'?'передумал':'передумала').'"/></form>';
	   echo '</div>';
	   
	}
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
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
  
  if ($user['level']<4 && $user['id']!=$Photo['user_id']) { loc('/FotoKonkurs'); exit; }
  
  if (!isset($Photo['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/Photo.php?ID='.$ID.'">'.text_out($Photo['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/EditePhoto.php?ID='.$ID.'">Изменить</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Photo['name'])) echo $div_link.'Такой фотографии не существует.<br/></div>';
   else
  
  {
  
     if (isset($_POST['submit'])) {
	 
	   $NAME = mysql_real_escape_string(trim($_POST['name']));
	   $OPIS = mysql_real_escape_string(trim($_POST['opis']));
	 
	   $SN = strlen($NAME); $SO = strlen($OPIS);
	   
	   echo $div_link;
	   
	   	 if ($SN<2 || $SN>128) echo 'Название должно быть не меньше 2 и не больше 128 символов.<br/>';
	      else
	 
	     {
	 
	       if ($SO<2 || $SO>512) echo 'Описание должно быть не меньше 2 и не больше 512 символов.<br/>';
	        else
	   
	       {
		   
		     echo 'Фотография успешно изменена.<br/>';
			 
			 mysql_query("UPDATE `FotoKonkursUser` SET `name` = '".$NAME."', `opis` = '".$OPIS."', `komm` = '".mysql_real_escape_string($_POST['komm'])."' WHERE `id` = '".$ID."' LIMIT 1");
		   
		   }
		   
		}
	   
	   echo '</div>';
	 
	 }else{
     
	   echo $div_link;
       echo '<form method="post">
	         Название фотографии (2 - 128 симв.)<br/>
		     <input type="text" name="name" value="'.$Photo['name'].'"/><br/>
		     Описание фотографии (2 - 512 симв.)<br/>
		     <textarea name="opis" cols="25" rows="3">'.$Photo['opis'].'</textarea><br/>
		     <input type="checkbox" name="komm" value="1" '.($Photo['komm']=='1'?'checked="checked"':'').'/> Запретить обсуждать фотографию<br/>
	         <input type="submit" name="submit" value="Изменить фотографию"/></form>';
	   echo '</div>';
	 
	 }
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
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

if ($user['level']<4) header('Location: /FotoKonkurs/index.php');

# ===================================================================

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' <a href="/FotoKonkurs/NewKonkurs.php">Добавить фотоконкурс</a><br/>';
  echo '</div>';
  
  if (isset($_POST['NewFotoKonkurs'])) {
  
     $NAME = mysql_real_escape_string($_POST['name']);
	 $OPIS = mysql_real_escape_string($_POST['opis']);
	 $POL = abs(intval($_POST['pol']));
	 
	 $SN = strlen(trim($NAME)); $SO = strlen(trim($OPIS));
	 
	 $DateOn = mysql_real_escape_string($_POST['date_on_god'].$_POST['date_on_mes'].$_POST['date_on_day'].$_POST['date_on_h'].$_POST['date_on_min']);
	 $DateOff = mysql_real_escape_string($_POST['date_off_god'].$_POST['date_off_mes'].$_POST['date_off_day'].$_POST['date_off_h'].$_POST['date_off_min']);
	 $DateGolos = mysql_real_escape_string($_POST['date_golos_god'].$_POST['date_golos_mes'].$_POST['date_golos_day'].$_POST['date_golos_h'].$_POST['date_golos_min']);
	 
	 $DateOn2 = mysql_real_escape_string($_POST['date_on_god'].'.'.$_POST['date_on_mes'].'.'.$_POST['date_on_day'].' в '.$_POST['date_on_h'].':'.$_POST['date_on_min']);
	 $DateOff2 = mysql_real_escape_string($_POST['date_off_god'].'.'.$_POST['date_off_mes'].'.'.$_POST['date_off_day'].' в '.$_POST['date_off_h'].':'.$_POST['date_off_min']);
	 $DateGolos2 = mysql_real_escape_string($_POST['date_golos_god'].'.'.$_POST['date_golos_mes'].'.'.$_POST['date_golos_day'].' с '.$_POST['date_golos_h'].':'.$_POST['date_golos_min']);
	 
	 echo $div_link;
	 
	   if ($SN<2 || $SN>128) echo 'Название фотоконкурса должно быть не меньше 2 и не больше 128 символов.<br/>';
	    else
		
	   {
	   
	     if ($SO<2) echo 'Описание фотоконкурса должно быть не меньше 2-х символов.<br/>';
		  else
		 
		 {
		 
		   # Добавление фотоконкурса в базу
		   
		   mysql_query("INSERT INTO `FotoKonkurs` (`name`, `opis`, `date_on`, `date_on2`, `date_off`, `date_off2`, `date_golos`, `date_golos2`, `pol`, `time`) VALUES ('".$NAME."', '".$OPIS."', '".$DateOn."', '".$DateOn2."', '".$DateOff."', '".$DateOff2."', '".$DateGolos."', '".$DateGolos2."', '".$POL."', '".time()."');");
		 
		   $ID = mysql_insert_id();
		 
		   echo 'Фотоконкурс успешно добавлен.<br/>';
		   echo '&raquo; <a href="/FotoKonkurs/Konkurs.php?ID='.$ID.'">Посмотреть конкурс</a><br/>';
		 
		 }
	   
	   }
	 
	 echo '</div>';
	 
     include_once '../sys/inc/tfoot.php';
     exit;
  
  }
  
 echo $div_link;
 echo '<form method="post">
       Название (2 - 128 симв.)<br/>
       <input type="text" name="name" value=""/><br/>
	   Описание (Не менее 2-х симв.)<br/>
	   <textarea name="opis" cols="25" rows="3"></textarea><br/> 
	   Учавствуют<br/>
	   <select name="pol">
	   <option value="1">Все</option>
	   <option value="2">Только парни</option>
	   <option value="3">Только девушки</option>
	   </select><br/>    
       Дата начала конкурса<br/>
       <select name="date_on_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       <select name="date_on_mes">
       <option value="01">Января</option>
	   <option value="02">Февраля</option>
	   <option value="03">Марта</option>
	   <option value="04">Апреля</option>
	   <option value="05">Мая</option>
	   <option value="06">Июня</option>
	   <option value="07">Июля</option>
	   <option value="08">Августа</option>
	   <option value="09">Сентября</option>
	   <option value="10">Октября</option>
	   <option value="11">Ноября</option>
	   <option value="12">Декабря</option>
       </select>';
 echo '<select name="date_on_god">';
       for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       в
       <select name="date_on_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_on_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select><br/>';
  
 echo 'Дата окончания конкурса<br/>
       <select name="date_off_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       <select name="date_off_mes">
       <option value="01">Января</option>
	   <option value="02">Февраля</option>
	   <option value="03">Марта</option>
	   <option value="04">Апреля</option>
	   <option value="05">Мая</option>
	   <option value="06">Июня</option>
	   <option value="07">Июля</option>
	   <option value="08">Августа</option>
	   <option value="09">Сентября</option>
	   <option value="10">Октября</option>
	   <option value="11">Ноября</option>
	   <option value="12">Декабря</option>
       </select>';
 echo '<select name="date_off_god">';
       for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       в
       <select name="date_off_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_off_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select><br/>';
  
 echo 'Можно голосовать за фото<br/>
       <select name="date_golos_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       <select name="date_golos_mes">
       <option value="01">Января</option>
	   <option value="02">Февраля</option>
	   <option value="03">Марта</option>
	   <option value="04">Апреля</option>
	   <option value="05">Мая</option>
	   <option value="06">Июня</option>
	   <option value="07">Июля</option>
	   <option value="08">Августа</option>
	   <option value="09">Сентября</option>
	   <option value="10">Октября</option>
	   <option value="11">Ноября</option>
	   <option value="12">Декабря</option>
       </select>';
 echo '<select name="date_golos_god">';
       for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select>
       с
       <select name="date_golos_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_golos_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'">0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'">'.$i.'</option>'; }
 echo '</select><br/>';
  
  echo '<input type="submit" name="NewFotoKonkurs" value="Добавить фотоконкурс"/></form>';
  echo '</div>';

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
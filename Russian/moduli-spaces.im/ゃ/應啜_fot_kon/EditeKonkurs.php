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
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$Konkurs['id'].'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/EditeKonkurs.php?ID='.$ID.'">Изменить</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (!isset($Konkurs['name'])) echo $div_link.'Такого фотоконкурса не существует.<br/></div>';
   else
  
  {
  
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
		 
		   echo 'Фотоконкурс успешно изменем.<br/>';
		   
		   mysql_query("UPDATE `FotoKonkurs` SET `name` = '".$NAME."', `opis` = '".$OPIS."', `date_on` = '".$DateOn."', `date_off` = '".$DateOff."', `date_golos` = '".$DateGolos."', `date_on2` = '".$DateOn2."', `date_off2` = '".$DateOff2."', `date_golos2` = '".$DateGolos2."', `pol` = '".$POL."' WHERE `id` = '".$ID."' LIMIT 1");
		 
		 }
	   
	   }
	   
	   echo '</div>';
	 
	 }else{
     
       # ------------------------------------------------------------------------------------
	   
	   $date_on = $Konkurs['date_on'];
	   $date_on2 = $Konkurs['date_off'];
	   $date_on3 = $Konkurs['date_golos'];
	   
 echo $div_link;
 echo '<form method="post">
       Название (2 - 128 симв.)<br/>
       <input type="text" name="name" value="'.$Konkurs['name'].'"/><br/>
	   Описание (Не менее 2-х симв.)<br/>
	   <textarea name="opis" cols="25" rows="3">'.$Konkurs['opis'].'</textarea><br/> 
	   Учавствуют<br/>
	   <select name="pol">
	   <option value="1" '.($Konkurs['pol']=='1'?'selected="selected"':'').'>Все</option>
	   <option value="2" '.($Konkurs['pol']=='2'?'selected="selected"':'').'>Только парни</option>
	   <option value="3" '.($Konkurs['pol']=='3'?'selected="selected"':'').'>Только девушки</option>
	   </select><br/>    
       Дата начала конкурса<br/>
       <select name="date_on_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on[6].$date_on[7]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'" '.($date_on[6].$date_on[7]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       <select name="date_on_mes">
       <option value="01" '.($date_on[4].$date_on[5]=='01'?'selected="selected"':'').'>Января</option>
	   <option value="02" '.($date_on[4].$date_on[5]=='02'?'selected="selected"':'').'>Февраля</option>
	   <option value="03" '.($date_on[4].$date_on[5]=='03'?'selected="selected"':'').'>Марта</option>
	   <option value="04" '.($date_on[4].$date_on[5]=='04'?'selected="selected"':'').'>Апреля</option>
	   <option value="05" '.($date_on[4].$date_on[5]=='05'?'selected="selected"':'').'>Мая</option>
	   <option value="06" '.($date_on[4].$date_on[5]=='06'?'selected="selected"':'').'>Июня</option>
	   <option value="07" '.($date_on[4].$date_on[5]=='07'?'selected="selected"':'').'>Июля</option>
	   <option value="08" '.($date_on[4].$date_on[5]=='08'?'selected="selected"':'').'>Августа</option>
	   <option value="09" '.($date_on[4].$date_on[5]=='09'?'selected="selected"':'').'>Сентября</option>
	   <option value="10" '.($date_on[4].$date_on[5]=='10'?'selected="selected"':'').'>Октября</option>
	   <option value="11" '.($date_on[4].$date_on[5]=='11'?'selected="selected"':'').'>Ноября</option>
	   <option value="12" '.($date_on[4].$date_on[5]=='12'?'selected="selected"':'').'>Декабря</option>
       </select>';
 echo '<select name="date_on_god">';
       for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'" '.($date_on[0].$date_on[1].$date_on[2].$date_on[3]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       в
       <select name="date_on_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on[8].$date_on[9]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'" '.($date_on[8].$date_on[9]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_on_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on[10].$date_on[11]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'" '.($date_on[10].$date_on[11]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select><br/>';
  
 echo 'Дата окончания конкурса<br/>
       <select name="date_off_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on2[6].$date_on2[7]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'" '.($date_on2[6].$date_on2[7]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       <select name="date_off_mes">
       <option value="01" '.($date_on2[4].$date_on2[5]=='01'?'selected="selected"':'').'>Января</option>
	   <option value="02" '.($date_on2[4].$date_on2[5]=='02'?'selected="selected"':'').'>Февраля</option>
	   <option value="03" '.($date_on2[4].$date_on2[5]=='03'?'selected="selected"':'').'>Марта</option>
	   <option value="04" '.($date_on2[4].$date_on2[5]=='04'?'selected="selected"':'').'>Апреля</option>
	   <option value="05" '.($date_on2[4].$date_on2[5]=='05'?'selected="selected"':'').'>Мая</option>
	   <option value="06" '.($date_on2[4].$date_on2[5]=='06'?'selected="selected"':'').'>Июня</option>
	   <option value="07" '.($date_on2[4].$date_on2[5]=='07'?'selected="selected"':'').'>Июля</option>
	   <option value="08" '.($date_on2[4].$date_on2[5]=='08'?'selected="selected"':'').'>Августа</option>
	   <option value="09" '.($date_on2[4].$date_on2[5]=='09'?'selected="selected"':'').'>Сентября</option>
	   <option value="10" '.($date_on2[4].$date_on2[5]=='10'?'selected="selected"':'').'>Октября</option>
	   <option value="11" '.($date_on2[4].$date_on2[5]=='11'?'selected="selected"':'').'>Ноября</option>
	   <option value="12" '.($date_on2[4].$date_on2[5]=='12'?'selected="selected"':'').'>Декабря</option>
       </select>';
 echo '<select name="date_off_god">';
    for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'" '.($date_on2[0].$date_on2[1].$date_on2[2].$date_on2[3]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       в
       <select name="date_off_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on2[8].$date_on2[9]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'" '.($date_on2[8].$date_on2[9]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_off_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on2[10].$date_on2[11]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'" '.($date_on2[10].$date_on2[11]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select><br/>';
  
 echo 'Можно голосовать за фото<br/>
       <select name="date_golos_day">';
    for ($i = 1; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on3[6].$date_on3[7]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 32; $i++) { echo '<option value="'.$i.'" '.($date_on3[6].$date_on3[7]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       <select name="date_golos_mes">
       <option value="01" '.($date_on3[4].$date_on3[5]=='01'?'selected="selected"':'').'>Января</option>
	   <option value="02" '.($date_on3[4].$date_on3[5]=='02'?'selected="selected"':'').'>Февраля</option>
	   <option value="03" '.($date_on3[4].$date_on3[5]=='03'?'selected="selected"':'').'>Марта</option>
	   <option value="04" '.($date_on3[4].$date_on3[5]=='04'?'selected="selected"':'').'>Апреля</option>
	   <option value="05" '.($date_on3[4].$date_on3[5]=='05'?'selected="selected"':'').'>Мая</option>
	   <option value="06" '.($date_on3[4].$date_on3[5]=='06'?'selected="selected"':'').'>Июня</option>
	   <option value="07" '.($date_on3[4].$date_on3[5]=='07'?'selected="selected"':'').'>Июля</option>
	   <option value="08" '.($date_on3[4].$date_on3[5]=='08'?'selected="selected"':'').'>Августа</option>
	   <option value="09" '.($date_on3[4].$date_on3[5]=='09'?'selected="selected"':'').'>Сентября</option>
	   <option value="10" '.($date_on3[4].$date_on3[5]=='10'?'selected="selected"':'').'>Октября</option>
	   <option value="11" '.($date_on3[4].$date_on3[5]=='11'?'selected="selected"':'').'>Ноября</option>
	   <option value="12" '.($date_on3[4].$date_on3[5]=='12'?'selected="selected"':'').'>Декабря</option>
       </select>';
 echo '<select name="date_golos_god">';
    for ($i = 2011; $i < 2021; $i++) { echo '<option value="'.$i.'" '.($date_on3[0].$date_on3[1].$date_on3[2].$date_on3[3]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select>
       с
       <select name="date_golos_h">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on3[8].$date_on3[9]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 24; $i++) { echo '<option value="'.$i.'" '.($date_on3[8].$date_on3[9]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select> : 
      <select name="date_golos_min">';
    for ($i = 0; $i < 10; $i++) { echo '<option value="0'.$i.'" '.($date_on3[10].$date_on3[11]=='0'.$i?'selected="selected"':'').'>0'.$i.'</option>'; }
	for ($i = 10; $i < 60; $i++) { echo '<option value="'.$i.'" '.($date_on3[10].$date_on3[11]==$i?'selected="selected"':'').'>'.$i.'</option>'; }
 echo '</select><br/>';
  
  echo '<input type="submit" name="NewFotoKonkurs" value="Изменить фотоконкурс"/></form>';
  echo '</div>';
	   
	   # ------------------------------------------------------------------------------------
	 
	 }
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
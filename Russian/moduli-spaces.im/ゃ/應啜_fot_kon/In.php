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

  function files_get($in)
  
  {
  
    $trans1 = array("Ё","Ж","Ч","Ш","Щ","Э","Ю","Я","ё","ж","ч","ш","щ","э","ю","я","А","Б","В","Г","Д","Е","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ь","Ы","а","б","в","г","д","е","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ь","ы"," ");
	$trans2 = array("JO","ZH","CH","SH","SCH","JE","JY","JA","jo","zh","ch","sh","sch","je","jy","ja","A","B","V","G","D","E","Z","I","J","K","L","M","N","O","P","R","S","T","U","F","H","C","'","Y","a","b","v","g","d","e","z","i","j","k","l","m","n","o","p","r","s","t","u","f","h","c","'","y","+");
	
    return str_replace($trans1,$trans2,$in);
	
  }

  $ID = abs(intval($_GET['ID']));
  $Konkurs = mysql_fetch_array(mysql_query("select * from `FotoKonkurs` WHERE `id` = '".$ID."'"));
  
  $KonkursUser = mysql_num_rows(mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$ID."' and `user_id` = '".$user['id']."'"));
  
  if (!isset($Konkurs['name'])) $Out = 'Ошибка!';
   else
  $Out = '<a href="/FotoKonkurs/Konkurs.php?ID='.$ID.'">'.text_out($Konkurs['name']).'</a> '.$Raquo.' <a href="/FotoKonkurs/In.php?ID='.$ID.'">Принять участие</a>';

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' '.$Out.'<br/>';
  echo '</div>';
  
  if (isset($_POST['submit'])) {
  
     echo $div_link;
  
     $NAME = mysql_real_escape_string(trim($_POST['name']));
	 $OPIS = mysql_real_escape_string(trim($_POST['opis']));
	 
	 $SN = strlen($NAME); $SO = strlen($OPIS);
	 
	 $type = end(explode('.', $_FILES['foto']['name']));
	 
	 if ($SN<2 || $SN>128) echo 'Название должно быть не меньше 2 и не больше 128 символов.<br/>';
	  else
	 
	 {
	 
	   if ($SO<2 || $SO>512) echo 'Описание должно быть не меньше 2 и не больше 512 символов.<br/>';
	    else
	   
	   {
	   
	     if (empty($_FILES['foto']['name'])) echo 'Извените, но Вы не выбрали фотографию.<br/>';
		  else
		 
		 {
		 
		   if ($type!='gif' && $type!='jpg' && $type!='png' && $type!='jpeg') echo 'Извените, но выбраный Вами файл - не фото.<br/>';
		    else
		
		   {
		   
		     if ($KonkursUser > 0) echo 'Извените, но Вы уже приняли участие в этом конкурсе.<br/>';
		      else
		   
		     {
		
		      echo 'Вы успешно приняли участие в конкурсе. Фото отправлено на модерацию.<br/>';
			  
			  mysql_query("INSERT INTO `FotoKonkursUser` (`user_id`, `konkurs_id`, `name`, `opis`, `time`, `foto`, `komm`, `status`) VALUES ('".$user['id']."', '".$ID."', '".$NAME."', '".$OPIS."', '".time()."', '".files_get($_FILES['foto']['name'])."', '".mysql_real_escape_string($_POST['komm'])."', '1');");
			  
			  $id_foto = mysql_insert_id();
			  
			   move_uploaded_file($_FILES['foto']['tmp_name'], 'Files/Original/'.$id_foto.'_'.files_get($_FILES['foto']['name']));
			   copy('Files/Original/'.$id_foto.'_'.files_get($_FILES['foto']['name']), 'Files/Wiev/'.$id_foto.'_'.files_get($_FILES['foto']['name']));
			   
               $imgc = @imagecreatefromstring(file_get_contents('Files/Wiev/'.$id_foto.'_'.files_get($_FILES['foto']['name'])));
               $img_x = imagesx($imgc);
               $img_y = imagesy($imgc);

               if ($img_x==$img_y) {
                   $dstW = 150;
                   $dstH = 150; 
               }
                elseif ($img_x>$img_y) {
                        $prop = $img_x/$img_y;
                        $dstW = 150;
                        $dstH = ceil($dstW/$prop);
                }
                 else {
                       $prop = $img_y/$img_x;
                       $dstH = 150;
                       $dstW = ceil($dstH/$prop);
                }

               $screen = imagecreatetruecolor($dstW, $dstH);
               imagecopyresampled($screen, $imgc, 0, 0, 0, 0, $dstW, $dstH, $img_x, $img_y);
               imagedestroy($imgc);
               imagegif($screen,'Files/Wiev/'.$id_foto.'_'.files_get($_FILES['foto']['name']));
               imagedestroy($screen); 
			   
			 }
		
		   }
		 
		 }
	   
	   }
	 
	 }
	 
	 echo '</div>';
	 
	 include_once '../sys/inc/tfoot.php';
	 exit;
  
  }
  
  if (!isset($Konkurs['name'])) echo $div_link.'Такого фотоконкурса не существует.<br/></div>';
   else
  
  {
  
     echo $div_link;
	 
       if ($Konkurs['date_off']<$Date2) echo 'Извените, но конкурс уже закончился и принять участие уже нельзя.<br/>';
	    else
	   
	   {
	   
	     if ($Konkurs['date_golos']>$Date2) echo 'Извените, но принять участие можно будет только <b>'.$Konkurs['date_golos2'].'</b><br/>';
		  else
		 
		 {
		 
		   if ($KonkursUser > 0) echo 'Извените, но Вы уже приняли участие в этом конкурсе.<br/>';
		    else
		   
		   {
		   
		      if ($Konkurs['date_on']>$Date2) echo 'Извените, но конкурс ещё не начался и принять участие пока что нельзя.<br/>';
	           else
	   
	          {
			  
			    if ($user['pol']=='0' && $Konkurs['pol']=='2') echo 'Извените, но учавствовать в конкурсе могут только парни.<br/>';
				 else
				
				{
				
				  if ($user['pol']=='1' && $Konkurs['pol']=='3') echo 'Извените, но учавствовать в конкурсе могут только девушки.<br/>';
				   else
				  
				  {
		 
		        echo $Link.' Принять участия в конкурсе <b>'.$Konkurs['name'].'</b><br/>';
		        echo $Link.' <font color="red">После добавления фотографии - она отправится на модерацию и если администрация Вас допустит до участия, то за фото можно будет голосовать.</font><br/>';
		        echo '<form method="post" enctype="multipart/form-data">
		              Название фотографии (2 - 128 симв.)<br/>
		              <input type="text" name="name" value=""/><br/>
				      Описание фотографии (2 - 512 симв.)<br/>
				      <textarea name="opis" cols="25" rows="3"></textarea><br/>
				      Выберите фотографию (jpg, gif, png)<br/>
				      <input type="file" name="foto"/><br/>
				      <input type="checkbox" name="komm" value="1"/> Запретить обсуждать фотографию<br/>
		              <input type="submit" name="submit" value="Принять участие"/></form>';
					  
				 }
					  
			   }
				   
			 }
				 
		   }
		 
		 }
	   
	   }
	   
	 echo '</div>';
  
  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
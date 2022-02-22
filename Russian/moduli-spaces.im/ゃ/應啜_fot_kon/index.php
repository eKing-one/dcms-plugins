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

  if (isset($_GET['Sort'])) {
  
    $Sort = htmlspecialchars(trim($_GET['Sort']));
	
    switch ($Sort) {
  
      default:
      break;
	
	  case 'On':
	
	  $_SESSION['foto_sort'] = NULL;
	  loc('/FotoKonkurs');
	
	  break;
	  
	  case 'Off':
	
	  $_SESSION['foto_sort'] = '111111';
	  loc('/FotoKonkurs');
	
	  break;
	  
	  case 'H':
	
	  $_SESSION['foto_sort'] = 'HHH';
	  loc('/FotoKonkurs');
	
	  break;
	
	}
  
  }

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a><br/>';
  echo '</div>';
  
  $PhotoModeration = mysql_num_rows(mysql_query("select * from `FotoKonkursUser` WHERE `status` = '1'"));
  
  echo $div_name;
  if ($user['level']>3) echo $Link.' <a href="NewKonkurs.php">Добавить фотоконкурс</a><br/>';
  if ($user['level']>3) echo $Link.' <a href="PhotoModeration.php">Фотографии на модерации</a> ['.$PhotoModeration.']<br/>';
  if ($user['level']>3) echo $Link.' <a href="Settings.php">Настройки</a><br/>';
    if (strlen($_SESSION['foto_sort'])<1) { echo $Link.' Сорт.: <b>Начатые</b> / <a href="/FotoKonkurs?Sort=Off">Завершенные</a> / <a href="/FotoKonkurs?Sort=H">Ждут начала</a><br/>'; $Sort = "WHERE `date_on` < '".$Date2."'"; }
	if (strlen($_SESSION['foto_sort'])>5) { echo $Link.' Сорт.: <a href="/FotoKonkurs?Sort=On">Начатые</a> / <b>Завершенные</b> / <a href="/FotoKonkurs?Sort=H">Ждут начала</a><br/>';  $Sort = "WHERE `date_off` < '".$Date2."'"; }
    if ($_SESSION['foto_sort']=='HHH') { echo $Link.' Сорт.: <a href="/FotoKonkurs?Sort=On">Начатые</a> / <a href="/FotoKonkurs?Sort=Off">Завершенные</a> / <b>Ждут начала</b><br/>'; $Sort = "WHERE `date_on` > '".$Date2."'"; }
  echo '</div>';
  
      $k_post = mysql_result(mysql_query("SELECT COUNT(*) FROM `FotoKonkurs` ".$Sort.""),0);
      $k_page = k_page($k_post,$FotoStr);
      $page = page($k_page);
      $start = $FotoStr * $page - $FotoStr;
      $res = mysql_query("select * from `FotoKonkurs` ".$Sort." order by time desc LIMIT $start, $FotoStr");
	  
	  if ($k_post=='0') echo $div_link.'Нет конкурсов...<br/></div>';
	   else
	  
	  {
	  
	    while ($post = mysql_fetch_array($res))
	  
	    {
		
		  $NumUser = mysql_num_rows(mysql_query("select * from `FotoKonkursUser` WHERE `konkurs_id` = '".$post['id']."'"));
	  
		  $i++;
	
          if ($i%2){ echo $div_zebr1; }
	        else
		  { echo $div_zebr2; }
			
		  # echo 'ID: '.$post['id'].'<br/>';
          echo '<img src="img/foto.png" alt=""/> <a href="/FotoKonkurs/Konkurs.php?ID='.$post['id'].'">'.text_out($post['name']).'</a><br/>';
		  echo '<font color="red">Могут учавствовать:</font> ';
		    if ($post['pol']=='1') echo '<b>Все</b>';
			if ($post['pol']=='2') echo '<b>Только парни</b>';
			if ($post['pol']=='3') echo '<b>Только девушки</b>';
		  echo '<br/>';
		  echo '<font color="red">Участников:</font> <b>'.$NumUser.'</b><br/>';
		  echo output_text($post['opis']).'<br/>';
		  
		  echo '<img src="img/time.png" alt=""/> <u>Дата начала:</u> '.text_out($post['date_on2']).'<br/>';
		  echo '<img src="img/time.png" alt=""/> <u>Дата окончания:</u> '.text_out($post['date_off2']).'<br/>';
		  echo '<img src="img/time.png" alt=""/> <u>Разрешено голосовать:</u> '.text_out($post['date_golos2']).'<br/>';
		  
		  if ($user['level']>3) echo '<img src="img/Delete.png" alt=""/> <a href="/FotoKonkurs/DeleteKonkurs.php?ID='.$post['id'].'">Удалить</a> / <a href="/FotoKonkurs/EditeKonkurs.php?ID='.$post['id'].'">Изменить</a><br/>';
		  
		  echo '</div>';
	  
	    }
	  
	  if ($k_page>1)str('?',$k_page,$page);
	  
	  }

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
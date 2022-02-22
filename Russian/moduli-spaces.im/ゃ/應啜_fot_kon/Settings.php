<?php

###################################################
#   Мини-игры под dcms 6.6.4 и 6.7.7              #
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

  if ($user['level']<4) { loc('/MiniGame'); exit; }

  echo $div_name;
  echo $Inform.' <a href="/FotoKonkurs/">Фотоконкурсы</a> '.$Raquo.' <a href="/FotoKonkurs/Settings.php">Настройки</a><br/>';
  echo '</div>';
  
  if (isset($_POST['submit']))
  
  {
  
    echo $div_link;
  
    $set1 = mysql_real_escape_string($_POST['div_name']);
	$set2 = mysql_real_escape_string($_POST['div_link']);
	$set3 = mysql_real_escape_string($_POST['div_zebr1']);
	$set4 = mysql_real_escape_string($_POST['div_zebr2']);
	
	if (!$set1 || !$set2 || !$set3 || !$set4) echo 'Вы некорректно ввели какие-то данные. В дизайне разрешены любые символы, а в остальных данных только цифры.<br/>';
	 else
	 
	{
	 
	  echo 'Настройки успешно сохранены.<br/>';
	  
	  mysql_query("UPDATE `FotoKonkurs_settings` SET `div_name` = '".$set1."', `div_link` = '".$set2."', `div_zebr1` = '".$set3."', `div_zebr2` = '".$set4."' WHERE `id` = '1' LIMIT 1");
	 
	} 
	
	echo '</div>';
	
	include_once '../sys/inc/tfoot.php';
	exit;
  
  }

  
  echo $div_link;
  echo '<form method="post">
        Дизайн заголовка<br/>
		<input type="text" name="div_name" value="'.$a['div_name'].'"/><br/>
        Дизайн других блоков<br/>
		<input type="text" name="div_link" value="'.$a['div_link'].'"/><br/>
        Дизайн зебры (блок 1)<br/>
		<input type="text" name="div_zebr1" value="'.$a['div_zebr1'].'"/><br/>
        Дизайн зебры (блок 2)<br/>
		<input type="text" name="div_zebr2" value="'.$a['div_zebr2'].'"/><br/>
        <input type="submit" name="submit" value="Сохранить"/></form>';
  echo '</div>';

# ===================================================================

include_once '../sys/inc/tfoot.php';
?>
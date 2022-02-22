<?php

require '../sid.php';
require '../config.php';
$link = connect_db();

if (!empty($_SESSION['us'])) {
   list($user, $id, $ps) = check_login($link);
   whorm(0, 'love');
}

include '../head.php';
include '../navigator.php';

$do = (isset($_GET['do'])) ? $_GET['do'] : NULL;
switch($do) {
default:
echo $div_title . 'Поиск пользователя' . $div_end . $div_left;

  if (isset($_GET['del'])) {
     mysql_query("UPDATE `users` SET `s_city` = '' WHERE `id` = '$user[id]' LIMIT 1");
	 header('Location: search.php?' . $ref);
  }

  $_citi = mysql_fetch_array(mysql_query("SELECT `city_name` FROM `geo_cities` WHERE `city_id` = '" . (int)$user['s_city'] . "'"));
  if (!empty($user['s_city'])) {
     $s_city = '<a href="search.php?do=city">' . $_citi[0] . '</a>
	            <a href="search.php?del"><img src="../ico/delete.gif" alt=""/></a>';
  } else {
	 $s_city = '<b>Все города</b>';
  }

     echo $div_menu . '
	       <a href="search.php?do=users">Знакомства</a>
		  ' . $div_end . '
	       <fieldset>
	       <FORM method="POST" action="search.php?do=result">
		   Возраст:<br/>
		   От <input type="text" name="ot" size="2" maxlength="2"/>
		   До <input type="text" name="do" size="2" maxlength="2"/>
		   <br/>
		   Пол:<br/>
		   <select name="sex">
		   <option value="0">Пол</option>
		   <option value="2">Мужской</option>
		   <option value="1">Женский</option>
		   </select>
		   <br/>
		   Ориентация:<br/>
		   <select name="orient">
           <option value="0">Ориентация</option>
           <option value="1">Гетеро</option>
           <option value="2">Би</option>
           <option value="3">Гей/Лесби</option>
           </select>
		   <br/>
		   Семейное положение:<br/>
		   <select name="status">
           <option value="0">Семейное положение</option>
           <option value="1">Не женат/Не замужем</option>
           <option value="2">Есть подруга/Есть друг</option>
           <option value="3">Помолвлен/Помолвлена</option>
           <option value="4">Женат/Замужем</option>
           <option value="5">Всё сложно</option>
           <option value="6">В активном поиске</option>
           </select>
		   <br/>
		   Цель знакомства:<br/>
		   <select name="target">
           <option value="0">Цель знакомства</option>
           <option value="1">Дружба и общение</option>
           <option value="2">Переписка</option>
           <option value="3">Любовь, отношения</option>
           <option value="4">Регулярный секс вдвоем</option>
           <option value="5">Секс на один-два раза</option>
           <option value="6">Групповой секс</option>
           <option value="7">Виртуальный секс</option>
           <option value="8">Предлагаю интим за деньги</option>
           <option value="9">Ищу интим за деньги</option>
           <option value="10">Брак, создание семьи</option>
           <option value="11">Рождение, воспитание ребенка</option>
           <option value="12">Брак для вида</option>
           <option value="13">Совместная аренда жилья</option>
           <option value="14">Занятия спортом</option>
           </select>
		   <br/>
		   Город: ' . $s_city . ' (<a href="search.php?do=city">выбрать</a>)
		   <br/>
		   <input type="checkbox" name="mycity" value="1"/> Свой город
		   <br/>
		   <input type="checkbox" name="foto" value="1"/> С фотографией
		   <br/>
		   <input type="checkbox" name="onsite" value="1"/> На сайте
		   <br/>
		   <input type="submit" name="search" value="Найти"/>
		   </FORM>
		   </fieldset>
		   Простой поиск:<br/>
		   <FORM method="POST" action="search.php?do=view">
		   <input type="text" name="nikname"/>
		   <br/>
		   В поиске можно указывать ник, id<br/>
		   <input type="submit" name="gosearch" value="Найти"/>
		   </FORM>';
echo $div_end;
break;

case result:
echo $div_title . 'Результаты поиска' . $div_end . $div_left . '
     <img src="../ico/search.gif" alt=""/> <a href="search.php?'.$ref.'">Новый поиск</a><br/>
	 ' . $div_menu;

     if (isset($_GET['new_all'])) {
	    $sql_sort = ' `id` DESC ';
	    echo '<b>Новые</b> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <a href="search.php?do=result&amp;last_all">Последние</a>' . $div_end;
	 } elseif (isset($_GET['rating_all'])) {
	    $sql_sort = ' `rating` DESC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <b>По рейтингу</b> |
			  <a href="search.php?do=result&amp;last_all">Последние</a>' . $div_end;
	 } elseif (isset($_GET['last_all'])) {
	   	$sql_sort = ' `id` ASC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <b>Последние</b>' . $div_end;
	 } else {
	   	$sql_sort = ' `id` ASC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <b>Последние</b>' . $div_end;
	 }

    $ot = my_int($_POST['ot']);
	$do = my_int($_POST['do']);
	$sex = my_int($_POST['sex']);
	$orient = my_int($_POST['orient']);
	$status = my_int($_POST['status']);
    $target = my_int($_POST['target']);
	$mycity = my_int($_POST['mycity']);
	$foto = my_int($_POST['foto']);
	$onsite = my_int($_POST['onsite']);

    if ($ot == 0) {
       $s_age_ot = "`age` LIKE ('%')";
    } else {
       $s_age_ot = "`age` >= '{$ot}'";
    }

    if ($do == 0) {
       $s_age_do = "`age` LIKE ('%')";
    } else {
       $s_age_do = "`age` <= '{$do}'";
    }

    if ($sex == 0) {
	   $_sex = "`sex` LIKE ('%')";
	} elseif ($sex == 1) {
	   $_sex = "`sex` = '1'";
	} elseif ($sex == 2) {
	   $_sex = "`sex` = '2'";
	}

    if ($sex == 1 && $status == 1) {
	   $_stat = "`status` = 'Не замужем'";
	} elseif ($sex == 1 && $status == 2) {
	   $_stat = "`status` = 'Есть друг'";
	} elseif ($sex == 1 && $status == 3) {
	   $_stat = "`status` = 'Помолвлена'";
	} elseif ($sex == 1 && $status == 4) {
	   $_stat = "`status` = 'Замужем'";
	} elseif ($sex == 2 && $status == 1) {
	   $_stat = "`status` = 'Не женат'";
	} elseif ($sex == 2 && $status == 2) {
	   $_stat = "`status` = 'Есть подруга'";
	} elseif ($sex == 2 && $status == 3) {
	   $_stat = "`status` = 'Помолвлен'";
	} elseif ($sex == 2 && $status == 4) {
	   $_stat = "`status` = 'Женат'";
	} else {
	   $_stat = "`status` LIKE ('%')";
	}

	if ($orient == 0) {
	   $_orient = "`orient` LIKE ('%')";
	} elseif ($orient == 1) {
	   $_orient = "`orient` = 'Гетеро'";
	} elseif ($orient == 2) {
	   $_orient = "`orient` = 'Би'";
	} elseif ($orient == 3) {
	   $_orient = "`orient` = 'Гей/Лесби'";
	}

    if ($target == 0) {
	    $_target = '';
	} elseif ($target == 1) {
	    $_target = "`tar_1` != '' AND ";
	} elseif ($target == 2) {
	    $_target = "`tar_2` != '' AND ";
	} elseif ($target == 3) {
	    $_target = "`tar_3` != '' AND ";
	} elseif ($target == 4) {
	    $_target = "`tar_4` != '' AND ";
	} elseif ($target == 5) {
	    $_target = "`tar_5` != '' AND ";
	} elseif ($target == 6) {
	    $_target = "`tar_6` != '' AND ";
	} elseif ($target == 7) {
	    $_target = "`tar_7` != '' AND ";
	} elseif ($target == 8) {
	    $_target = "`tar_8` != '' AND ";
    } elseif ($target == 9) {
	    $_target = "`tar_9` != '' AND ";
	} elseif ($target == 10) {
	    $_target = "`tar_10` != '' AND ";
	} elseif ($target == 11) {
	    $_target = "`tar_11` != '' AND ";
	} elseif ($target == 12) {
	    $_target = "`tar_12` != '' AND ";
	} elseif ($target == 13) {
	    $_target = "`tar_13` != '' AND ";
	} elseif ($target == 14) {
	    $_target = "`tar_14` != '' AND ";
	}

    if ($mycity == 1 && $user['city'] == 0) {
	    $_citi = "`city` = '{$user[city]}'";
	} elseif ($mycity == 1 && $user['city'] != 0) {
	    $_citi = "`city` = '{$user[s_city]}'";
	} else {
	    $_citi = "`city` LIKE ('%')";
	}

    if ($foto == 1) {
	   $_foto = "`img` != ''";
	} else {
	   $_foto = "`img` LIKE ('%')";
	}

    if ($onsite == 1) {
        $_on = "`onl` + '100' > '" . time() . "'"; 
	} else {
	    $_on = "`onl` LIKE ('%')";
	}

    $look = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` 
	                   WHERE
					    {$s_age_ot} AND
					    {$s_age_do} AND
					    {$_sex} AND
					    {$_stat} AND
					    {$_orient} AND
						{$_target}
						{$_citi} AND
					    {$_foto} AND
					    {$_on}
					   "), 0);

    $n = new navigator($look, 10, '?do=result&amp;');

    $res = mysql_query("SELECT * FROM `users` 
	                   WHERE
					    {$s_age_ot} AND
					    {$s_age_do} AND
					    {$_sex} AND
					    {$_stat} AND
					    {$_orient} AND
						{$_target}
						{$_citi} AND
					    {$_foto} AND
					    {$_on}
					   ORDER BY {$sql_sort} {$n->limit}");

 if ($look != FALSE) {
   $i = 0;
    while($a = mysql_fetch_assoc($res)) {
		 if ($i ++ % 2) echo $div_tworazdel . nikus($a['id']) . '<br/>' . bb_code(smiles($a['xstatus'])) . $div_end;
		 else echo $div_razdel . nikus($a['id']) . '<br/>' . bb_code(smiles($a['xstatus'])) . $div_end;
	}
	 echo $n->navi();
 } else {
   echo 'Поиск не дал результатов!<br/>';
 }	

echo $div_end;
break;

case city:
echo $div_title . 'Выбрать город' . $div_end . $div_left;

  if (isset($_GET['ok'])) {
     $ok = my_int($_GET['ok']);
	 mysql_query("UPDATE `users` SET `s_city` = '$ok' WHERE `id` = '$user[id]' LIMIT 1");
	 header('Location: search.php?' . $ref);
  }

  if (isset($_GET['r'])) {
      $look = mysql_result(mysql_query("SELECT COUNT(*) FROM `geo_cities` WHERE `rid` = '" . (int)$_GET['r'] . "'"), 0);
      $n = new navigator($look, 10, '?do=city&amp;r='.(int)$_GET['r'].'&amp;');

      $gorod = mysql_query("SELECT * FROM `geo_cities` WHERE `rid` = '" . (int)$_GET['r'] . "' ORDER BY `city_name` ASC {$n->limit}");

	 $i = 0;
	 if ($look != FALSE) {
	 while($a = mysql_fetch_assoc($gorod)) {
          if ($i ++ % 2) echo $div_razdel . '<a href="search.php?do=city&amp;ok='.$a['city_id'].'">' . $a['city_name'] . '</a>' . $div_end;
		  else echo $div_tworazdel . '<a href="search.php?do=city&amp;ok='.$a['city_id'].'">' . $a['city_name'] . '</a>' . $div_end;
	 }
	 echo $n->navi();
	 } else {
	   echo 'Городов нет!<br/>';
	 }
  }

  elseif (isset($_GET['c'])) {
      $look = mysql_result(mysql_query("SELECT COUNT(*) FROM `geo_regions` WHERE `cid` = '" . (int)$_GET['c'] . "'"), 0);
      $n = new navigator($look, 10, '?do=city&amp;c='.(int)$_GET['c'].'&amp;');

      $gorod = mysql_query("SELECT * FROM `geo_regions` WHERE `cid` = '" . (int)$_GET['c'] . "' ORDER BY `region_name` ASC {$n->limit}");

	 $i = 0;
	 if ($look != FALSE) {
	 while($a = mysql_fetch_assoc($gorod)) {
          if ($i ++ % 2) echo $div_razdel . '<a href="search.php?do=city&amp;r='.$a['region_id'].'">' . $a['region_name'] . '</a>' . $div_end;
		  else echo $div_tworazdel . '<a href="search.php?do=city&amp;r='.$a['region_id'].'">' . $a['region_name'] . '</a>' . $div_end;
	 }
	 echo $n->navi();

	 } else {
	   echo 'Регионов нет!<br/>';
	 }

  } else {

$look = mysql_result(mysql_query("SELECT COUNT(*) FROM `geo_countries`"), 0);
$n = new navigator($look, 10, '?do=city&amp;');

$gorod = mysql_query("SELECT * FROM `geo_countries` ORDER BY `country_name` ASC {$n->limit}");

	 $i = 0;
	 if ($look != FALSE) {
	 while($a = mysql_fetch_assoc($gorod)) {
          if ($i ++ % 2) echo $div_razdel . '<a href="search.php?do=city&amp;c='.$a['country_id'].'">' . $a['country_name'] . '</a>' . $div_end;
		  else echo $div_tworazdel . '<a href="search.php?do=city&amp;c='.$a['country_id'].'">' . $a['country_name'] . '</a>' . $div_end;
	 }
	 echo $n->navi();

	 } else {
	   echo 'Стран нет!<br/>';
	 }
  }
echo $div_end;
break;

case users:
echo $div_title . 'Знакомства' . $div_end . $div_left . '
     <img src="../ico/search.gif" alt=""/> <a href="search.php?'.$ref.'">Новый поиск</a><br/>
	 ' . $div_menu;

     if (isset($_GET['new_all'])) {
	    $sql_sort = ' `id` DESC ';
	    echo '<b>Новые</b> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <a href="search.php?do=result&amp;last_all">Последние</a>' . $div_end;
	 } elseif (isset($_GET['rating_all'])) {
	    $sql_sort = ' `rating` DESC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <b>По рейтингу</b> |
			  <a href="search.php?do=result&amp;last_all">Последние</a>' . $div_end;
	 } elseif (isset($_GET['last_all'])) {
	   	$sql_sort = ' `id` ASC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <b>Последние</b>' . $div_end;
	 } else {
	   	$sql_sort = ' `id` ASC ';
	    echo '<a href="search.php?do=result&amp;new_all">Новые</a> |
		      <a href="search.php?do=result&amp;rating_all">По рейтингу</a> |
			  <b>Последние</b>' . $div_end;
	 }

    $look = mysql_result(mysql_query("SELECT COUNT(*) FROM `users`"), 0);

    $n = new navigator($look, 10, '?do=users&amp;');

    $res = mysql_query("SELECT * FROM `users` ORDER BY {$sql_sort} {$n->limit}");

 if ($look != FALSE) {
   $i = 0;
    while($a = mysql_fetch_assoc($res)) {
		 if ($i ++ % 2) echo $div_tworazdel . nikus($a['id']) . '<br/>' . bb_code(smiles($a['xstatus'])) . $div_end;
		 else echo $div_razdel . nikus($a['id']) . '<br/>' . bb_code(smiles($a['xstatus'])) . $div_end;
	}
	 echo $n->navi();
 } else {
   echo 'Поиск не дал результатов!<br/>';
 }	

echo $div_end;
break;

case view:
echo $div_title . 'Знакомства' . $div_end . $div_left . '
     <img src="../ico/search.gif" alt=""/> <a href="search.php?'.$ref.'">Новый поиск</a><br/>
	 ' . $div_menu;


   if (!ctype_digit($_POST['nikname'])) {
      $nikname = trim(mysql_real_escape_string(check($_POST['nikname'])));
	  $se = "`user` LIKE '%$nikname%'";
   } else {
      $nikname = my_int($_POST['nikname']);
	  $se = "`id` = '$nikname'";
   }

   $look = mysql_result(mysql_query("SELECT COUNT(*) FROM `users` WHERE $se"), 0);
   $n = new navigator($look, 10, '?do=view');

   $view = mysql_query("SELECT * FROM `users` WHERE $se ORDER BY `id` DESC {$n->limit}");

	if ($look != FALSE) {
	 $i = 0;
	 while($a = mysql_fetch_assoc($view)) {
	     if ($i ++ % 2) echo $div_razdel . nik($a['id']) . $div_end;
		 else echo $div_tworazdel . nik($a['id']) . $div_end;
	 }
	  echo $n->navi();
	} else {
	  echo 'Поиск не дал результатов!<br/>';
	}

echo $div_end;
break;

}
include '../foot.php';
?>
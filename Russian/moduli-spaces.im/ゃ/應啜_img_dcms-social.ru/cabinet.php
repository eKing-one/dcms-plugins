<?php
/*
СОБСТВЕННО КАБИНЕТ УПРАВЛЕНИЯ ПОЛЬЗОВАТЕЛЯ.
ЗДЕСЬ ПРОИЗВОДЯТСЯ ОСНОВНЫЕ ДЕЙСТВИЯ.
*/
$title = "Кабинет партнера"; //ЗАГОЛОВОК
include_once("ini.php");
include_once("header.php");
$user = info();
if($user==0){
header("location: index.php");
}
$id_user = $user["id_zver"];
$a=(isset($_GET['a']))?$_GET['a']:"";
if (empty($a)) {
// ///////////////ГЛАВНАЯ КАБИНЕТА
$datenew = zapros("select date from news order by id_new desc");
if (!$datenew) {
$datenew = "пока нет";
}
echo diz($title, "header");
echo $div["menu"];
include("adv.php");
echo $div["end"];
echo $div["menu"];
echo 'ID: ' . $user["id_zver"] . '<br/>';
echo 'Баланс: ' . zapros("select balans from zveri where id_zver=" . $id_user . "") . ' WMR<br/>';
echo 'Сегодня кликов: ' . zapros("select clickS from zveri where id_zver=" . $id_user . "") . '<br/>';
// //////////
if ($user["type"] == "user") {
$status = '<font color="red">Обычный</font>';
}
if ($user["type"] == "premium") {
$status = '<font color="green">Premium</font>';
}
if ($user["type"] == "gold") {
$status = '<font color="#FF6600">Gold</font>';
}
echo "Тариф: " . url("file", "view=tarif", $status);
// //////////
echo $div["end"];
echo $hr;
echo $div["menu"];
echo url("news", "", "»Новости ($datenew)");
echo url("price", "", "»Расценки");
echo url("cabinet", "a=stat", "»Статистика");
echo url("cabinet", "a=rating", "»Рейтинг");
echo url("cabinet", "a=conf", "»Настройки");
echo $div["end"];
echo $hr;
echo $div["menu"];
echo url("cabinet", "a=url", "»Сайты (" . zapros("select count(id_url) from sites where id_zver=" . $id_user) . ")");
echo url("cabinet", "a=referals", "»Партнеры (" . zapros("select count(id_zver) from zveri where id_parent=" . $id_user) . ")");
echo url("auction", "", "»Аукцион");
echo url("cabinet", "a=money", "»Вывод WM");
echo url("cabinet", "a=mobi", "»Вывод на мобильный");
echo $div["end"];
echo $hr;
echo $div["menu"];
echo url("chat", "", "»Mini-Чат (" . zapros("select count(id_mess) from chat") . ")");
echo url("forum", "", "»Форум (" . zapros("select count(id_post) from forum") . ")");
echo url("mail", "", "»Почта (" . zapros("select count(id_mail) from mail where id_to=".$id_user." and folder='new'") . ")");
echo url("file", "view=faq", "»FAQ");
echo url("file", "view=rules", "»Правила");
echo url("cabinet", "a=tiket", "»Тикеты");
echo url("file", "view=cont", "»Контакты");
echo $div["end"];
echo $hr;
echo $div["menu"];
echo url("cabinet", "a=out", "»Выход");
echo $div["end"];
}
// ////////////////////ДОБАВЛЕНИЕ/УДАЛЕНИЕ САЙТОВ
elseif ($a == "url") {
echo diz("Мои сайты", "header");
    $id_del=(isset($_GET['id_del']))?$_GET['id_del']:"0";
    $link=(isset($_GET['link']))?$_GET['link']:"0";
    $b=(isset($_GET['b']))?$_GET['b']:"0";
    if ($id_del!=0) {
        $id_del = (int)$id_del;
        $query = mysql_query("delete from sites where id_url=" . $id_del . " and id_zver=" . $id_user) or die (mysql_error($sql));
        if ($query) {
            echo "Сайт удален!" . $hr;
        } else {
            echo "Вы не можете удалить этот сайт!" . $hr;
        }
    }
    if ($link!=0) {
        $link = (int)$link;
        echo 'Для заработка разместите на данном сайте ссылку:<br/><input type="text" value="' . $site . '/click.php?link=' . $link . '"/><br/>';
        echo 'Название - любое, не нарушающее правила системы.';
        echo $hr . url("cabinet", "a=url", "К сайтам");
    } elseif (empty($b)) {
        $startan=(isset($_GET['startan']))?$_GET['startan']:"0";
        $startan = intval($startan);
        if ($startan < 0) $startan = 0;
        echo '(S) - ссылка для сайта<br/>(X) - удаление сайта<br/>';
        $arr = mysql_query("select * from sites");
        $query = mysql_query("select * from sites where id_zver=" . $id_user . " limit " . $startan . "," . $colzap . "");
        if (mysql_affected_rows() == 0) {
            echo $hr . "К сожалению, у Вас не добавлено ни одного сайта для заработка.<br/>";
        } else {
            while ($site = mysql_fetch_array($query)) {
                // /////////////выодим ссылку и прочее
                echo $hr;
                if ($site['status'] == "mod") {
                    echo url("cabinet", "a=url&b=mod&id=" . $site['id_url'] . "", "<b>" . $site['status'] . "</b>");
                } else {
                    if ($site['status'] == "activ") {
                        $status = "Активен";
                    } elseif ($site['status'] == "ban") {
                        $status = "Забанен";
                    }
                    echo "<b>" . $status . "</b><br/>";
                }
                echo $site['url'] . url("cabinet", "a=url&link=" . $site['id_url'] . "", "<b>(S)</b>") . url("cabinet", "a=url&id_del=" . $site['id_url'] . "", "<b>(X)</b>");
                // ////////////конец вывода
            }
        }
        $col = mysql_num_rows($arr);
        if ($startan != 0) {
            echo $hr . url("cabinet", "a=url&startan=" . ($startan - $colzap) . "", "Назад");
        }
        if ($col > $startan + $colzap) {
            echo $hr . url("cabinet", "a=url&startan=" . ($startan + $colzap) . "", "Вперед");
        }
        echo $hr . url("cabinet", "a=url&b=add", "Добавить");
        // /////////////////////
    } elseif ($b == "add") {
         $sites=(isset($_POST['sites']))?$_POST['sites']:"0";
        if ($sites) {
            $sites=htmlspecialchars(trim(addslashes($sites)));
            $page = @file_get_contents($sites);
            $err=0;
            if (!$page) {
                echo '<b>Такого сайта нет!</b>' . $hr;
                $err++;
            }
            $page = "";
            if (!ereg($check["URL"][0], $sites)) {
                echo "<b>URL введен не верно!</b><br/>";
                echo $hr;
                $err++;
            }
            if (zapros("select id_zver from sites where url='" . $sites . "'")) {
                echo "<b>Такой сайт уже есть  в базе!</b><br/>";
                echo $hr;
                $err++;
            }
            if (!$err) {
                $insert = mysql_query("insert into sites set url='" . $sites . "', id_zver=" . $id_user);
                if ($insert) {
                    echo "Сайт успешно добавлен!";
                    echo $hr;
                } else {
                    echo "К сожалению, сайт не может быть добавлен в данный момент.";
                    echo $hr;
                }
                $sites = "";
            }
        }
        echo '<form method="post">
URL:<br/><input type="text" name="sites" value="http://"/><br/>
<input type="submit" value="Добавить"/>
</form>
';
        echo $hr . url("cabinet", "a=url", "К сайтам");
    } elseif ($b == "mod") {
        $id = (isset($_GET['id']))?(int)$_GET['id']:"0";
         if(!$id){
           header("location: cabinet.php?sid=".$sid);
           }
        $urlsite = zapros("select url from sites where id_url=" . $id);
        $status = zapros("select status from sites where id_url=" . $id);
        if ($status == "activ") {
            echo 'Сайт уже промодерирован.';
            echo $hr . url("cabinet", "a=url", "К сайтам");
        } else {
            if (!$urlsite) {
                echo "Такого сайта нет в базе!";
            } else {
                $pg = @file_get_contents($urlsite);
                $link = $site . "/click.php?link=" . $id;
                if (!$pg) {
                    echo '<b>Такого сайта нет!</b>';
                } elseif (substr_count($pg,$link) === 0) {
                    echo 'Ваш сайт не прошел модерацию.Что б это сделать установите на нем эту ссылку:<br/><input type="text" value="' . $link . '"/><br/>После этого зайдите сюда еще раз.';
                } else {
                    $q = mysql_query("update sites set status='activ' where id_url=" . $id);
                    if ($q) {
                        echo 'Сайт успешно промодерирован.';
                        echo $hr . url("cabinet", "a=url", "К сайтам");
                    } else {
                        echo 'Сайт не может быть промодерирован сейчас.Зайдите попозже';
                        echo $hr . url("cabinet", "a=url", "К сайтам");
                    }
                }
            }
        }
    } else {
        Header("location: /cabinet.php?sid=" . $ses . "");
    }
}
// /////////////////////СТАТИСТИКА
elseif ($a == "stat") {
    echo diz("Статистика переходов", "header");
    echo $div["menu"];
    $startan=(isset($_GET['startan']))?$_GET['startan']:"0";
    $startan = intval($startan);
    if ($startan < 0) $startan = 0;
    $arr = mysql_query("select * from trafick where id_zver=" . $id_user . "");
    $count = mysql_num_rows($arr);
   if($startan > $count + $colzap){
	echo 'Вы ошиблись адресом!';
}
else
{
    $query = mysql_query("select * from trafick where id_zver=" . $id_user . " limit " . $startan . "," . $colzap);
    if (mysql_affected_rows() == 0) {
        echo "Сегодня еще не было переходов!<br/>";
    } else {
        $x = mysql_num_rows($query);
        if ($x == 1) {
            $b = "";
        } elseif (($x > 1) && ($x < 5)) {
            $b = "а";
        } else {
            $b = "ов";
        }
        echo 'За сегодня - ' . $x . ' переход' . $b . '' . $hr;
        while ($go = mysql_fetch_array($query)) {
            $oper=$go['oper'];
            include("opname.php");
            echo $go["time"];
            echo '<br/>Сайт: '.zapros("select url from sites where id_url=".$go['id_url']).'<br/>';
            echo 'Браузер: ' . $go["usa"] . '<br/>Оператор: ' . $oper_name. '<br/>';
            echo 'Вознаграждение: '.($money["".$go["oper"].""]/1000).' wmr';
            echo $hr;
        }
    }
    if ($startan != 0) {
        echo url("cabinet", "a=stat&startan=" . ($startan - $colzap) . "", "Назад");
    }
    if ($count > $startan + $colzap) {
        echo $hr . url("cabinet", "a=stat&startan=" . ($startan + $colzap) . "", "Вперед");
    }
}
   echo $div["end"];
    // ////////////////
}
// //////////////ЗАКАЗ ВЫВОДА СРЕДСТВ
elseif ($a == "money") {
    echo diz("Вывод WM", "header");
 echo $div["menu"];
    $balance = zapros("select balans from zveri where id_zver=" . $id_user . "");
    $b=(isset($_GET['b']))?$_GET['b']:"0";
    if (!$b) {
        echo 'На счету - ' . $balance . 'WMR<br/>';
        echo 'Минимальная сумма к выводу - ' . $min . 'WMR';
        echo $hr;
        echo '<center>';
        if ($balance < $min) {
            echo '<font color="red">Вывод невозможен!</font>';
        } else {
            echo url("cabinet", "a=money&b=go", "Вывести!");
        }
        echo '</center>';
    } elseif ($b == "go") {
        if ($balance < $min) {
            echo '<font color="red">Вывод невозможен!</font>';
        } else {
	    $t=($user["type"]!="user")?$tarif[$user["type"]]:"0";
            mysql_query("update zveri set balans=0 where id_zver=" . $user['id_zver']."");
            $summa = $balance * (1 + ($t / 100)); ///РАСЧИТЫВАЕМ СУММУ К ВЫПЛОТЕ СОГЛАСНО ТАРИФУ
            mysql_query("insert into viplati set id_zver=" . $user['id_zver'] . ", summa=" . $summa . ", purse='" . $user["wmr"] . "'");
            echo 'Заявка на вывод средств отослана администрации.Вы получите свои деньги в скором времени.';
        }
    } else {
        Header("location: /cabinet.php?sid=" . $sid . "");
    }
    echo $div["end"];
}
// //////////РЕЙТИНГ ПАРНЕРОВ
elseif ($a == "rating") {
    echo diz("Рейтинг партнеров", "header");
   echo $div["menu"];
    $q = zapros("select count(id_zver) from zveri where clickS!=0 and status!='ban'");
    if (!$q) {
        echo 'Нет участников  в рейтинге сегодня!';
    } else {
        if ($q > 5) {
            $q = 5;
        } else {
            $q = $q;
        }
        $query = mysql_query("select id_zver, clickS from zveri where clickS!=0 and status!='ban'  order by clickS desc limit " . $q);
        while ($uzver = mysql_fetch_array($query)) {
            echo "ID:" . $uzver["id_zver"] . " - " . $uzver["clickS"] . " кликов/сегодня<br/>";
        }
    }
     echo $div["end"];
}
// ////////////////РЕФ. ССЫЛКА
elseif ($a == "referals") {
    echo diz("Рефералы", "header");
    echo $div["menu"];
    echo 'Для приведения партнера размести на своем сайте следующую ссылку:<br/>';
    echo '<input type="text" value="' . $site . '/reg.php?id=' . $id_user . '"/><br/>';
    echo 'Вы будете получать ' . $ref . ' процентов от заработка ваших партнеров.' . $hr;
    $col = zapros("select count(id_zver) from zveri where id_parent=" . $id_user);
    if (!$col) {
        echo 'У Вас еще нет парнеров';
    } else {
        echo 'У Вас партнеров - ' . $col . '<br/>';
        echo 'Сегодня они принесли Вам - ' . (zapros("select sum(monS) from zveri where id_parent=" . $id_user) * $ref / 100) . ' WMR';
    }
     echo $div["end"];
}
// ///////////////ТИКЕТ СИСТЕМА
elseif ($a == "tiket") {
    echo diz("Тикеты", "header");
    // ////////ВСТАВКА ТИКЕТА В БАЗУ
   $text=(isset($_POST['text']))?$_POST['text']:"0";
    if ($text) {
        $text = htmlspecialchars(addslashes($text));
        if (!zapros("select * from tiketi where text='" . $text . "'")) {
            $query = mysql_query("insert into tiketi set id_zver=" . $id_user . ", text='" . $text . "', answer=0,date=NOW()");
            if ($query) {
                echo "Вопрос упешно добавлен.Ответ Вы получите в ближайшее время." . $hr;
            } else {
                echo "К сожадению, в данный момент задать не возможно.Попробуйте позже." . $hr;
            }
        }
    }
    // ////////ФОРМА ДОБАВЛЕНИЯ ТИКЕТА
    echo '<center><form method="post">
Вопрос:<br/><input type="text" name="text" value=""/><br/>
<input type="submit" value="Отправить"/>
</form></center>';
    echo $hr;
    // ////////ВЫВОД ТИКЕТОВ
    $startan=(isset($_GET['startan']))?$_GET['startan']:"0";
    $startan = intval($startan);
    if ($startan < 0) $startan = 0; //ВЫРАЖАЕМ СТРАНИЦУ
    $q = mysql_query("select * from tiketi"); //ОБЩИЙ ЗАПРОС
    $query = mysql_query("select * from tiketi where id_zver=" . $id_user . " order by id_tiket desc limit " . $startan . "," . $colzap . ""); //ЗАПРОС НЕОБХОДИМЫХ
    if (mysql_affected_rows() == 0) {
        echo "Нет тикетов..."; //ОТСУТСТВИЕ
    } else {
        while ($tiket = mysql_fetch_array($query)) { // /ЛЮБИМЫЙ ЦЫКЛ ВЫВОДИТ НУЖНУЮ ИНФУ
            echo $tiket["date"] . "<br/>"; //ДАТА
            echo $tiket["text"] . "<br/>"; //ВОПРОС
            if (!$tiket["answer"]) {
                $answer = '<font color="red">На модерации</font>' . $hr; //ЕСЛИ НЕТ ОТВЕТА ТО НА МОДЕРАЦИИ...
            } else {
                $answer = '<font color="green">Ответ: ' . $tiket["answer"] . '</font>' . $hr; //ИЛИ ВЫВОДИМ ОТВЕТ
            }
            echo $answer; //ОТВЕТ
        }
    }
    $col = mysql_num_rows($q); //КОЛИЧЕСТВО
    // //////////НАВИГАЦИЯ ПО СТРАНИЦАМ
    if ($startan != 0) {
        echo $hr . url("cabinet", "a=tiket&startan=" . ($startan - $colzap) . "", "Назад");
    }
    if ($col > $startan + $colzap) {
        echo $hr . url("cabinet", "a=tiket&startan=" . ($startan + $colzap) . "", "Вперед");
    }
    }
    //////////////////////////////////
    /////////НАСТРОЙКИ ПОЛЬЗОВАТЕЛЯ///
    //////////////////////////////////
    elseif($a=="conf"){
     echo diz("Настройки", "header");
      echo $div["menu"];
$icq=(isset($_POST['icq']))?$_POST['icq']:"";
$wmr=(isset($_POST['wmr']))?$_POST['wmr']:"";
     if(($icq)&&($wmr)){
     $err=0;
     if(!ereg($check["ICQ"][0],$icq)){echo '<b>Не верно введен ICQ!</b><br/>';$err++;}
     if(!ereg($check["WMR"][0],$wmr)){echo '<b>Не верно введен кошелек!</b><br/>';$err++;}
     if(!$err){
     $query=mysql_query("update zveri set icq='".$icq."', wmr='".$wmr."' where id_zver=".$id_user);
     if($query){echo "Информация успешно сохранена!".$hr;}else {echo 'Не возможно сохранить информацию в данный момент...'.$hr;}
     }
     }
     $query=mysql_query("select pass,icq,wmr from zveri where id_zver=".$id_user);
     $data=mysql_fetch_array($query);
     echo "@MAIL: ".$user["mail"]."<br/>";
     echo '<form method="post">
     Пароль: '.$data["pass"].'<br/>
     ICQ:<br/><input type="text" name="icq" value="'.$data["icq"].'"/><br/>
     WMR:<br/><input type="text" name="wmr" value="'.$data["wmr"].'"/><br/>
     <input type="submit" value="Сохранить"/>
     </form>';
    echo $div["end"];

    }
    ////////////////////////////
    /////вывод на мобилу///////
    elseif($a=="mobi"){
    echo diz("Выплаты на мобильный", "header");
     echo $div["menu"];
   if(isset($_POST['summa'])){
	$summa=(isset($_POST['summa']))?(float)$_POST['summa']:"0";
	$opsos=(isset($_POST['opsos']))?htmlspecialchars(addslashes($_POST['opsos'])):"0";
	$numfon=(isset($_POST['numfon']))?$_POST['numfon']:"0";
	$err=0;
	$topay=$summa+($summa*$mobproc/100);
	if(empty($summa)||empty($opsos)||empty($numfon)){echo 'Введите все данные!<br/>'; $err++;}
	elseif($topay>$user['balans']){echo 'Не хватает денег на счету!<br/>';$err++;}
	elseif(strlen($numfon)>12){echo 'Не верно введен номер!<br/>';$err++;}
	elseif($summa<$minmob){echo 'Сумма меньше минимума к выводу!<br/>';$err++;}
	elseif(($opsos=="life")&&($summa<25)){echo 'Минимум к выводу на life - 25 рублей!<br/>';$err++;}
	if(!$err){
		mysql_query("update zveri set balans=balans-".$topay." where id_zver=".$user['id_zver']);
	        mysql_query("insert into mobipay set id_zver=".$user['id_zver'].", summa=".$summa.", opsos='".$opsos."', number=".$numfon."");
		echo "Заявка на вывод средств на ".$numfon." подана администрации и будет выполнена в ближайшее время.";
	}
	
} else{
    echo 'Минимум к выводу на мобильный: '.$minmob.' руб.(исключениe - life:) - 25 руб)<br/>';
    echo 'Дополнительная комисия - '.$mobproc.'%<br/>';
    echo $hr;
    ///
    echo '<form method="post">
    Выберите оператора:<br/>
   <select name="opsos">
<option value="megafon(centr)">Мегафон (Центральный)</option>
<option value="megafon(severo-zapad)">Мегафон (Северо-Запад)</option>
<option value="megafon(ural)">Мегафон (Уральский)</option>
<option value="megafon(sibir)">Мегафон (Сибирский)</option>
<option value="megafon(dalnevostok)">Мегафон (Дальневосточный)</option>
<option value="megafon(moskva)">Мегафон (Москва)</option>
<option value="megafon(povolzhue)">Мегафон (Поволжье)</option>
<option value="megafon(severnue_kavkaz)">Мегафон (Северный Кавказ)</option>
<option value="beeline">Билайн</option>
<option value="mts">МТС</option>
<option value="umc">МТС Украина</option>
<option value="kyivstar">Киевстар</option>
<option value="wellcom">Билайн Украина</option>
<option value="life">life:)</option>
  </select>
Введите номер (например 380664522200)<br/>
<input type="text" name="numfon" value=""/><br/>
Введите сумму:<br/>
<input type="text" name="summa" value=""/><br/>
<input type="submit" value="Вывести"/>
</form>';
}
  echo $div["end"];
}
    // //выход
    elseif($a=="out"){
    mysql_query("update zveri set ses='' where id_zver=".$id_user);
    Header("location: index.php");
////
}
// ///////////////////////////////////////////////////////
// ////////////////ЕСЛИ ОШИБОЧНЫЙ ЗАПРОС//////////////////
else {
    Header("location: /cabinet.php?sid=" . $sid . "");
}
// ////////////////
if ($a) {
    echo $hr;
    echo url("cabinet", "", "В кабинет");
}
include_once("footer.php");

?>
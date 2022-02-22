<?php
/*
АДМИНОЧКА
*/
$title = "ADMIN-PANEL";
include_once("ini.php");
include_once("header.php");
echo diz($title, "header");
$pwd=(isset($_GET['pwd']))?$_GET['pwd']:"0";
if (($pwd)&&(($pwd == $apanel))) {
$url = 'admin.php?pwd=' . $pwd . '';
// /////////////////////////////////
// //////ССЫЛКИ НА ЧАСТИ АДМИНКИ///
// /////////////////////////////////
$a=(isset($_GET['a']))?$_GET['a']:"";
switch($a){
/////ГЛАВНАЯ
default:
echo $div['menu'];
echo '<a href="' . $url . '&a=new">»Новости</a><br/>';
echo '<a href="' . $url . '&a=user">»Пользователи (' . zapros("select count(id_zver) from zveri") . ')</a><br/>';
echo '<a href="' . $url . '&a=sites">»Сайты (' . zapros("select count(id_url) from sites") . ')</a><br/>';
echo '<a href="' . $url . '&a=tiket">»Тикеты(' . zapros("select count(id_tiket) from tiketi where answer='0'") . ')</a><br/>';
echo '<a href="' . $url . '&a=tikets">»История тикетов</a><br/>';
echo '<a href="' . $url . '&a=wm">»Вывод WM (' . zapros("select count(id_vip) from viplati") . ')</a><br/>';
echo '<a href="' . $url . '&a=mobi">»Вывод на мобильный (' . zapros("select count(id_pay) from mobipay") . ')</a><br/>';
echo '<a href="' . $url . '&a=traf">»Трафик</a><br/>';
echo '<a href="' . $url . '&a=ceni">»Цены</a><br/>';
echo '<a href="' . $url . '&a=conf">»Настройки</a><br/>';
echo '<a href="' . $url . '&a=logs">»Логи</a><br/>';
echo '<a href="' . $url . '&a=stat">»Статистика</a><br/>';
echo '<a href="' . $url . '&a=auc">»Победители аукциона</a><br/>';
echo '<a href="' . $url . '&a=adv">»Реклама</a><br/>';
echo '<a href="forum.php?pwd=' . $pwd . '">»Форум</a><br/>';
echo '<a href="chat.php?pwd=' . $pwd . '">»Чат</a><br/>';
echo $div['end'];
break;
// ///////////////////////////////
// /////НОВОСТИ///////////////////
// ///////////////////////////////
case 'new':
// /////////////////////////////
// /РЕДАКТИРОВАНИЕ НОВОСТИ//////
// /////////////////////////////
if (isset($_GET['id_news'])) {
$id_news = (int)$_GET['id_news'];
if (!$id_news)header("location: " . $url . "");
$text=(isset($_POST['text']))?addslashes(htmlspecialchars($_POST['text'])):"0";
if ($text) {
mysql_query("update news set text='" . $text . "' where id_new=" . $id_news);
echo "Новость изменена!" . $hr;
}
$text = zapros("select text from news where id_new=" . $id_news);
echo '<form method="post">
Текст<br/><input type="text" name="text" value="' . $text . '"/><br/>
<input type="submit" value="Изменит"/>
</form>';
}
// ////////////////////////////
// //УДАЛЕНИЕ НОВОСТИ//////////
// ////////////////////////////
elseif (isset($_GET['id_delete'])) {
$id_delete = (int)$_GET['id_delete'];
if (!$id_delete)header("location: " . $url . "");
mysql_query("delete from news where id_new=" . $id_delete);
echo "Новость удалена!";
// /////////////////////////////
// ////ДОБАВЛЕНИЕ И ПРОСМОТР////
// /////////////////////////////
} else {
$new=(isset($_POST['new']))?$_POST['new']:"0";
if ($new) {
$new = htmlspecialchars(addslashes($new));
if (!zapros("select id_new from news where text='" . $new . "'")) {
}
                    $query = mysql_query("insert into news set text='" . $new . "', date=NOW(), time=NOW()");
                    if ($query) {
                        echo "Новость успешно добавлена!" . $hr;
                    } else {
                        echo "Не возможно добавить новость в данный момент." . $hr;
                    }
                }
                // /////////ФОРМА ДОБАВЛЕНИЯ НОВОСТИ
                echo '<form method="post">
        Новость:<br/><input type="text" name="new" value=""/><br/>
        <input type="submit" value="Добавить"/>
        </form>';
                 $start=(isset($_GET['start']))?$_GET['start']:"0";
                $start = (int)$start;
                if (!$start < 0)$start = 0;
                $q = mysql_query("select * from news");
                $query = mysql_query("select * from news order by id_new desc limit " . $start . "," . $colzap);
                if (mysql_affected_rows() == 0) {
                    echo "Новостей пока нет";
                } else {
                    while ($new = mysql_fetch_array($query)) {
                        echo $hr . $new["date"] . "<br/>";
                        echo $new["text"] . "<br/>";
                        echo '<a href="' . $url . '&a=new&id_news=' . $new["id_new"] . '">[EDIT]</a>|<a href="' . $url . '&a=new&id_delete=' . $new["id_new"] . '">[DEL]</a><br/>';
                        echo '<a href="news.php?pwd='.$apanel.'&id='.$new["id_new"].'">Комментарии ('.zapros("select count(id_comm) from comm where id_new=".$new["id_new"]).')</a>';
                     }
                }
                $count = mysql_num_rows($q);
                if ($start) {
                    echo '<a href="' . $url . '&a=new&start=' . ($start - $colzap) . '">Назад</a>';
                }
                if ($count > $start + $colzap) {
                    echo $hr . '<a href="' . $url . '&a=new&start=' . ($start + $colzap) . '">Вперед</a>';
                }
            }
        break;
        // //////////////////////////////
        // //////////ПОЛЬЗОВАТЕЛИ////////
        // //////////////////////////////
        case 'user':
            // //////////ФОРМА ПОИСКА ПОЛЬЗОВАТЕЛЯ
            $id_zver=(isset($_POST['id_zver']))?$_POST['id_zver']:"0";
            if(isset($_GET['id'])){
	$id=$_GET['id'];
}
elseif(isset($_POST['id'])){
	$id=$_POST['id'];
}
else
{
	$id=0;
}
            if ($id_zver)$id = $id_zver;
            echo '<form method="post">
        ID пользователя:<br/><input type="text" name="id" value="' . $id . '"/><br/>
        <input type="submit" value="Смотреть"/><br/>
        </form>' . $hr;
            // ///////////ВЫПОЛНЯЕМ ДЕЙСТВИЯ НАД ПОЛЬЗОВАТЕЛЕМ
            if ($id_zver!=0) {
                $balans = (isset($_POST['balans']))?$_POST['balans']:"0";
                 $status=(isset($_POST['status']))?$_POST['status']:"activ";
                 $type=(isset($_POST['type']))?$_POST['type']:"user";
                $q = mysql_query("update zveri set balans=" . $balans . ", status='" . $status . "', type='" . $type . "' where id_zver=" . $id_zver);
                if ($status == "ban") {
                    mysql_query("update sites set status='ban' where id_zver=" . $id_zver);
                } else {
                    mysql_query("update sites set status='activ' where id_zver=" . $id_zver);
                }
                if ($q) {
                    echo "Данные успешно сохранены!" . $hr;
                } else {
                    echo "Данные не могут быть сохранены в настоящее время.Попробуйте позже." . $hr;
                }
            }
            // ///////////ВЫВОД И РЕДАКТИРОВАНИЕ ИНФОРМАЦИИ
            elseif ($id) {
                $id = (int)$id;
                $query = mysql_query("select * from zveri where id_zver=" . $id);
                $id = mysql_fetch_array($query);
                if (!$id) {
                    echo 'Пользователь с данным ID не найден.';
                } else {
                    echo '<a href="' . $url . '&a=logs&id_log=' . $id["id_zver"] . '">[Смотреть логи]</a>' . $hr;
                    echo "Пароль - " . $id["pass"] . "<br/>";
                    echo "Мыло - " . $id["mail"] . "<br/>";
                    echo "ICQ - " . $id["icq"] . "<br/>";
                    echo "Последний браузер - " . $id["ua"] . "<br/>";
echo "Последний ip - " . $id["ip"] . "<br/>";
                    echo "Сегодня кликов - " . $id["clickS"] . "<br/>";
                    echo "Всего кликов - " . $id["clickV"] . "<br/>";
                    echo "Заработал сегодня - " . $id["monS"] . "<br/>";
                    echo "Заработал всего - " . $id["monV"] . "<br/>";
                    echo "Кошелек - " . $id["wmr"] . "<br/>";
                    echo "Дата регистрации  - " . $id["datreg"] . "<br/>";
                    $az=zapros("select count(id_zver) from zveri where id_parent=" . $id["id_zver"]);
                    echo "Рефералов - " . $az . "<br/>";
                    ////если есть рефы то выволим их
                    if($az){
	        echo "Иды его рефералов:";
	            $query = mysql_query("select id_zver from zveri where id_parent=" . $id["id_zver"]);
                        while ($uid = mysql_fetch_array($query)) {
                            echo '<a href="' . $url . '&a=user&id=' . $uid["id_zver"] . '">' . $uid["id_zver"] . '</a>;';
                        }
                    echo "<br/>";
}
                    echo "Его привёл - " . (($id["id_parent"])?'<a href="' . $url . '&a=user&id='.$id["id_parent"].'">'.$id["id_parent"].'</a>':"нет") . "<br/>";
                    $ax = zapros("select count(id_url) from sites where id_zver=" . $id["id_zver"]);
                    echo "Сайтов - " . $ax . "<br/>";
                    // /////////ЕСЛИ ЕСТЬ САЙТЫ ТО ВЫВОДИМ ИХ ИДЫ
                    if ($ax) {
                        echo "ID'ы его сайтов - ";
                        $query = mysql_query("select id_url from sites where id_zver=" . $id["id_zver"]);
                        while ($sayt = mysql_fetch_array($query)) {
                            echo '<a href="' . $url . '&a=sites&id=' . $sayt["id_url"] . '">' . $sayt["id_url"] . '</a>;';
                        }
                    }
                    // ////////////ИЗМЕНЕНИЕ ИНФОРМАЦИИ
                    echo '<form method="post">
        Балланс:<br/><input type="text" name="balans" value="' . $id["balans"] . '"/><br/>
        Тип:<br/><select name="type">
        <option value="user" ' . (($id["type"] == "user")?"selected='1'":"") . '>Обычный</option>
        <option value="premium" ' . (($id["type"] == "premium")?"selected='1'":"") . '>Premium</option>
        <option value="gold" ' . (($id["type"] == "gold")?"selected='1'":"") . '>Gold</option>
        </select><br/>
        Статус:<br/><select name="status">
        <option value="activ" ' . (($id["status"] == "activ")?"selected='1'":"") . '>Активен</option>
        <option value="ban" ' . (($id["status"] == "ban")?"selected='1'":"") . '>Забанен</option>
        </select><br/>
        <input type="hidden" name="id_zver" value="' . $id["id_zver"] . '"/>
        <input type="submit" value="Сохранить"/>
        </form>';
                }
            } else {
	 $sort=(isset($_GET['sort']))?$_GET['sort']:"id_zver";
	        if(($sort!="balans")&&($sort!="id_zver")&&($sort!="clickS")){$sort="id_zver";}
	        echo 'Сортировка:<br/>';
	echo '1)';
	if($sort != "id_zver"){
		echo '<a href="' . $url . '&a=user&sort=id_zver">по ID</a><br/> ';
	}else{
		echo '<b>по ID</b><br/>';
	}
	///
	echo '2)';
	if($sort != "balans"){
		echo '<a href="' . $url . '&a=user&sort=balans">по балансу</a><br/>';
	}else{
		echo '<b>по балансу</b><br/>';
	}
	///
	echo '3)';
	if($sort != "clickS"){
		echo '<a href="' . $url . '&a=user&sort=clickS">по кликам/сегодня</a><br/>';
	}else{
		echo '<b>по кликам/сегодня</b><br/>';
	}
	///	
	     
                $start=(isset($_GET['start']))?$_GET['start']:"0";
                $start = (int)$start;
                if (!$start < 0)$start = 0;
                $q = mysql_query("select * from zveri");
                $query = mysql_query("select id_zver, balans, clickS from zveri order by ".$sort." desc limit " . $start . "," . $colzap);
                if (mysql_affected_rows() == 0) {
                    echo "Нет пользователей";
                } else {
                    while ($zvers = mysql_fetch_array($query)) {
                        echo $hr ."ID: ".$zvers["id_zver"] . "<br/>";
                        echo "Баланс: ".$zvers["balans"] . " WMR<br/>";
                        echo 'Сегодня кликов: '.$zvers["clickS"]."<br/>";
                        echo '<a href="' . $url . '&a=user&id=' . $zvers["id_zver"] . '">[EDIT]</a>|<a href="' . $url . '&a=logs&id_log=' . $zvers["id_zver"] . '">[LOGS]</a><br/>';
                    }
                }
                $count = mysql_num_rows($q);
                if ($start) {
                    echo '<a href="' . $url . '&a=user&sort='.$sort.'&start=' . ($start - $colzap) . '">Назад</a>';
                }
                if ($count > $start + $colzap) {
                    echo $hr . '<a href="' . $url . '&a=user&sort='.$sort.'&start=' . ($start + $colzap) . '">Вперед</a>';
                }
            }
        break;
        // ///////////////////////////////
        // ////////САЙТЫ//////////////////
        // ///////////////////////////////
        case 'sites';
            // //////ФОРМА
            $id_url=(isset($_POST['id_url']))?$_POST['id_url']:"0";
            $id=(isset($_POST['id']))?$_POST['id']:"0";
            if(isset($_POST['id'])){
            $id=$_POST['id'];
            }
            elseif(isset($_GET['id'])){
            $id=$_GET['id'];
            }
            else
            {
            $id=0;
            }
            if ($id_url)$id = $id_url;
            echo '<form method="post">
        ID сайта:<br/><input type="text" name="id" value="' . $id . '"/><br/>
        <input type="submit" value="Смотреть"/><br/>
        </form>' . $hr;
            // //////ИЗМЕНЕНИЕ СТАТУСА
            if ($id_url) {
                $status=(isset($_POST['status']))?$_POST['status']:"";
                $query = mysql_query("update sites set status='" . $status . "' where id_url=" . $id_url);
                if ($query) {
                    echo "Данные успешно изменены." . $hr;
                } else {
                    echo "Данные не могут быть изменены в данный момент.Попробуйте позже.";
                }
            }
            // ///////ПРОСМОТР ИНФОРМАЦИИ
            if ($id) {
                $id = (int)$id;
                $query = mysql_query("select * from sites where id_url=" . $id);
                $sayt = mysql_fetch_array($query);
                if (!$sayt) {
                    echo "Сайт с таким ID не найден.";
                } else {
                    echo 'ID пользователя: <a href="' . $url . '&a=user&id=' . $sayt["id_zver"] . '">'.$sayt["id_zver"].'</a><br/>';
                    echo 'URL <a href="out.php?url=' . $sayt["url"] . '">' . $sayt["url"] . '</a><br/>';
                    $pg = @file_get_contents($sayt["url"]);
                    $link = $site . "/click.php?link=" . $id;
                    $qq = substr_count($pg, $link);
                    if ($qq) {
                        $w = "Да";
                    } else {
                        $w = "Нет";
                    }
                    echo 'Ссылка стоит - ' . $w . '<br/>';
                    echo '<form method="post">
        Статус:<br/><select name="status">
        <option value="activ" ' . (($sayt["status"] == "activ")?"selected='1'":"") . '>Активен</option>
        <option value="ban" ' . (($sayt["status"] == "ban")?"selected='1'":"") . '>Забанен</option>
        <option value="mod" ' . (($sayt["status"] == "mod")?"selected='1'":"") . '>На модерации</option>
        </select><br/>
        <input type="hidden" name="id_url" value="' . $sayt["id_url"] . '"/>
        <input type="submit" value="Сохранить"/>
        </form>
        ';
                }
            }
else 
{
	$start=(isset($_GET['start']))?$_GET['start']:"0";
                $start = (int)$start;
                if (!$start < 0)$start = 0;
                $q = mysql_query("select * from sites");
                $query = mysql_query("select id_zver, id_url, url from sites order by id_url desc limit " . $start . "," . $colzap);
                if (mysql_affected_rows() == 0) {
                    echo "Сайтов";
                } else {
                    while ($sites = mysql_fetch_array($query)) {
                        echo "ID сайта: ".$sites["id_url"] . "<br/>";
                        echo 'ID пользователя: <a href="' . $url . '&a=user&id='.$sites["id_zver"] . '">'.$sites["id_zver"] . '</a><br/>';
                        echo 'URL: <a href="out.php?url='.$sites['url'].'">'.$sites['url'].'<br/>';
                        echo '<a href="' . $url . '&a=sites&id=' . $sites["id_url"] . '">[EDIT]</a><br/>';
                        echo $hr;
                    }
                }
                $count = mysql_num_rows($q);
                if ($start) {
                    echo '<a href="' . $url . '&a=sites&start=' . ($start - $colzap) . '">Назад</a>';
                }
                if ($count > $start + $colzap) {
                    echo $hr . '<a href="' . $url . '&a=sites&start=' . ($start + $colzap) . '">Вперед</a>';
                }
}
        break;
        // //////////////////////////////////
        // /////////ТИКЕТЫ///////////////////
        // //////////////////////////////////
        case 'tiket':
            $answer=(isset($_GET['answer']))?$_GET['answer']:"0";
           $answer = (int)$answer;
            if ($answer) {
                $otvet=(isset($_POST['otvet']))?$_POST['otvet']:"0";
                if (($otvet) && (!zapros("select id_tiket from tiketi where text='" . $otvet . "'"))) {
                    $query = mysql_query("update tiketi set answer='" . $otvet . "' where id_tiket=" . $answer);
                    if ($query) {
                        header("location: " . $url . "&a=tiket");
                    } else {
                        echo "Не возможно ответить в данный момент." . $hr;
                    }
                }
                echo '<form method="post">
        Ответ:<br/><input type="text" name="otvet" value=""/><br/>
        <input type="submit" value="Ответить"/>
        </form>';
            } else {
                echo "Вывод - 10 тикетов на страницу." . $hr;
                $query = mysql_query("select * from tiketi where answer='0' order by id_tiket desc limit 10");
                $arr = mysql_num_rows($query);
                if (!$arr) {
                    echo "Нет не отвеченных тикетов.";
                } while ($tiket = mysql_fetch_array($query)) {
                    echo $tiket["date"] . "<br/>";
                    echo "ID: ".$tiket["id_zver"] . "<br/>";
                    echo '<font color="red">Вопрос</font>: ' . $tiket["text"];
                    echo '<a href="' . $url . '&a=tiket&answer=' . $tiket["id_tiket"] . '">[отв.]</a>';
                    echo $hr;
                }
            }
        break;
        // //////////////////////////////////
        // /////////история тикетов///////////////////
        // //////////////////////////////////
        case 'tikets':
	$start=(isset($_GET['start']))?$_GET['start']:"0";
                $start = (int)$start;
                if (!$start < 0)$start = 0;
	        if(isset($_POST['id_zver'])){
	$id_zver=(int)$_POST['id_zver'];
	}
	elseif(isset($_GET['id_zver'])){
	$id_zver=(int)$_GET['id_zver'];
	}
	else
	{
		$id_zver="";
	}
         echo '<form method="post">
        ID:<br/><input type="text" name="id_zver" value="'.$id_zver.'"/><br/>
        <input type="submit" value="Ответить"/>
        </form>'; 
                if($id_zver){$zz="and id_zver=".$id_zver;} else {$zz="";}
                $q=mysql_query("select * from tiketi where 1=1 ".$zz."");       
                $query = mysql_query("select * from tiketi where answer!='' ".$zz." order by id_tiket desc limit ".$start.",".$colzap."");
                if (mysql_affected_rows()==0) {
                    echo "Тикеты не найдены<br/>";
                } while ($tiket = mysql_fetch_array($query)) {
                    echo $tiket["date"] . "<br/>";
                    echo "ID: ".$tiket["id_zver"] . "<br/>";
                    echo '<font color="red">Вопрос</font>: ' . $tiket["text"].'<br/>';
                    echo '<font color="green">Ответ</font>: ' . $tiket["answer"].'<br/>';
                    echo $hr;
                }
                $count = mysql_num_rows($q);
                if ($start) {
                    echo '<a href="' . $url . '&a=tikets&'.(($id_zver!=0)?"id_zver=".$id_zver."":"&").'start=' . ($start - $colzap) . '">Назад</a>';
                }
                if ($count > $start + $colzap) {
                    echo $hr . '<a href="' . $url . '&a=tikets&'.(($id_zver!=0)?"id_zver=".$id_zver."&":"").'start=' . ($start + $colzap) . '">Вперед</a>';
                }
        break;
        // /////////////////////////////////////
        // ////////ЗАПРОСЫ ВЫВОДА///////////////
        // /////////////////////////////////////
        case 'wm':
            echo "Вывод - 10 запросов на страницу." . $hr;
            $id_v=(isset($_GET['id_v']))?$_GET['id_v']:"0";
            if ($id_v) {
                $id_v = (int)$id_v;
                $qa = mysql_query("delete from viplati where id_vip=" . $id_v);
                if ($qa) {
                    echo "Запрос успешно удален." . $hr;
                } else {
                    echo "Запрос не может быть удален  в данный момент." . $hr;
                }
            }
            $query = mysql_query("select * from viplati order by id_vip desc limit 10");
            $arr = mysql_num_rows($query);
            if (!$arr) {
                echo "Нет не выплаченных запросов.";
            } while ($vip = mysql_fetch_array($query)) {
                echo 'ID: <a href="admin.php?pwd='.$pwd.'&a=user&id='. $vip["id_zver"] . '">'. $vip["id_zver"] . '</a><br/>';
                echo 'Необходимо вывести ' . $vip["summa"] . 'WMR на ' . $vip["purse"] . '<br/>';
                $ax = zapros("select count(id_url) from sites where id_zver=" . $vip["id_zver"]);
                // /////////ЕСЛИ ЕСТЬ САЙТЫ ТО ВЫВОДИМ ИХ ИДЫ
                if ($ax) {
                    echo "ID'ы его сайтов - ";
                    $q = mysql_query("select id_url from sites where id_zver=" . $vip["id_zver"]);
                    while ($sayt = mysql_fetch_array($q)) {
                        echo $sayt["id_url"].";";
                    }
                }
                echo '<br/><a href="wmk:payto?Purse='.$vip["purse"].'&Amount='.$vip["summa"].'&Desc=выплата на wm-traf.ru&BringToFront=Y">Заплатить</a><br/>';
                echo '<a href="' . $url . '&a=wm&id_v=' . $vip["id_vip"] . '">Удалить</a><br/>';
                echo $hr;
            }
        break;
        // /////////////////////////////////////
        // ////////ЗАПРОСЫ ВЫВОДА НА МОБИЛУ/////
        // /////////////////////////////////////
        case 'mobi':
            echo "Вывод - 10 запросов на страницу." . $hr;
            $id_v=(isset($_GET['id_v']))?$_GET['id_v']:"0";
            if ($id_v) {
                $id_v = (int)$id_v;
                $qa = mysql_query("delete from mobipay where id_pay=" . $id_v);
                if ($qa) {
                    echo "Запрос успешно удален." . $hr;
                } else {
                    echo "Запрос не может быть удален  в данный момент." . $hr;
                }
            }
            $query = mysql_query("select * from mobipay order by id_pay desc limit 10");
            $arr = mysql_num_rows($query);
            if (!$arr) {
                echo "Нет не выплаченных запросов.";
            } while ($vip = mysql_fetch_array($query)) {
                echo 'ID: <a href="admin.php?pwd='.$pwd.'&a=user&id='. $vip["id_zver"] . '">'. $vip["id_zver"] . '</a><br/>';
                echo 'Необходимо вывести <b>' . $vip["summa"] . 'WMR</b> на <b>' . $vip["number"] . '</b>, оператор - <b>' . $vip["opsos"] . '</b><br/>';
                $ax = zapros("select count(id_url) from sites where id_zver=" . $vip["id_zver"]);
                // /////////ЕСЛИ ЕСТЬ САЙТЫ ТО ВЫВОДИМ ИХ ИДЫ
                if ($ax) {
                    echo "ID'ы его сайтов - ";
                    $q = mysql_query("select id_url from sites where id_zver=" . $vip["id_zver"]);
                    while ($sayt = mysql_fetch_array($q)) {
                        echo $sayt["id_url"].";";
                    }
                }
                echo '<br/><a href="' . $url . '&a=mobi&id_v=' . $vip["id_pay"] . '">Удалить</a><br/>';
                echo $hr;
            }
        break;
        // ///////////////////////////////////
        // ///////////////ЛОГИ////////////////
        // ///////////////////////////////////
        case 'logs':
             $logdel=(isset($_GET['logdel']))?$_GET['logdel']:"0";
           if(isset($_POST['id_log'])){
            $id_log=$_POST['id_log'];
            }
            elseif(isset($_GET['id_log'])){
            $id_log=$_GET['id_log'];
            }
            else
            {
            $id_log=0;
            }
              echo '<form method="post">
        ID:<br/><input type="text" name="id_log" value="' . $id_log . '"/><br/>
        <input type="submit" value="Смотреть"/>
        </form>' . $hr;
            if ($logdel) {
                $logdel = (int)$logdel;
                @unlink("logi/" . $logdel . ".log");
                echo "Логи данного пользователя удалены." . $hr;
            }
            if ($id_log) {
                $file = @file_get_contents("logi/" . $id_log . ".log");
                if (!$file) {
                    echo "Для данного пользователя логи отсутствуют.";
                } else {
                    echo '<a href="' . $url . '&a=logs&logdel=' . $id_log . '">[DELETE]</a>' . $hr;
                    $f = split("\n", $file);
                    $ox = count($f);
                    error_reporting(0);
                    $page=(isset($_GET['page']))?$_GET['page']:"0";
                    $page = (int)$page;
                    if ($page < 0)$page = 0;
                    $q = $page + $colzap;
                    for($i = $page;$i < $q;$i++) {
                        if (!$f[$i])echo "";
                        echo $f[$i];
                        if (!$f[$i]) {
                            echo "";
                        } else {
                            echo $hr;
                        }
                    }
                    $nazad = $page - $colzap;
                    $vpered = $page + $colzap;
                    if (($ox > $vpered) && ($ox != $vpered))echo '<a href="' . $url . '&a=logs&id_log=' . $id_log . '&page=' . $vpered . '">Вперед</a><br/>';
                    if ($page)echo '<a href="' . $url . '&a=logs&id_log=' . $id_log . '&page=' . $nazad . '">Назад</a>';
                }
            }
        break;
        // ///////////////////////////////////
        // ////УПРАВЛЕНИЕ ТРАФИКОМ////////////
        // ///////////////////////////////////
        case 'traf':
            $cell=(isset($_POST['cell']))?$_POST['cell']:"0";
$opera=(isset($_POST['opera']))?$_POST['opera']:"0";
$comp=(isset($_POST['comp']))?$_POST['comp']:"0";
////
            if (($cell) && ($opera) && ($opera)) {
                 $cell=addslashes(htmlspecialchars($cell));
$opera=addslashes(htmlspecialchars($opera));
$comp=addslashes(htmlspecialchars($comp));
                $query = mysql_query("update url set cell='" . $cell . "', opera='" . $opera . "', comp='" . $comp . "'");
                if ($query) {
                    echo "Настройки успешно сохранены." . $hr;
                } else {
                    echo "Не возможно сохранить настройки в данный момент." . $hr;
                }
            }
            $query = mysql_query("select * from url");
            $trafick = mysql_fetch_array($query);
            echo '<form method="post">
        Мобильные:<br/><input type="text" name="cell" value="' . $trafick["cell"] . '"/><br/>
        Opera Mini:<br/><input type="text" name="opera" value="' . $trafick["opera"] . '"/><br/>
        Компы:<br/><input type="text" name="comp" value="' . $trafick["comp"] . '"/><br/>
        <input type="submit" value="Сохранить"/>
        </form>
        ';
        break;
        // /////////////////////////////////
        // ///ЦЕНЫ И ТАРИФЫ/////////////////
        // /////////////////////////////////
        case 'ceni':
            if (count($_POST)>0){
               for($i=1;$i<119;$i++){
               $m=$_POST["c".$i.""];
               $m=(int)$m;
               if($m==0)$m==200;
                $query.=mysql_query("update money set `".$i."`=".$m);
                }

            }
            echo 'Цены указанны в рублях.<br/>Для выключения оплаты оператора поставьте 0' . $hr;
            echo '<form method="post">';
           for($i=1;$i<119;$i++){
           $oper=$i;
           include("opname.php");
           $cena=zapros("select `".$i."` from money");
           echo $oper_name.'<br/><input type="text" name="c'.$i.'" value="'.$cena.'"/><br/>';
           }
        echo '<input type="submit" value="Сохранить"/>
        </form>
        ';
        break;
       /////////////////////////////////////
       ///////настройки
      case 'conf':
         if(isset($_POST['action'])){
$ref1=(isset($_POST['ref1']))?addslashes(htmlspecialchars($_POST['ref1'])):"0";
 $min1=(isset($_POST['min1']))?addslashes(htmlspecialchars($_POST['min1'])):"0";
 $sheif1=(isset($_POST['sheif1']))?addslashes(htmlspecialchars($_POST['sheif1'])):"0";
 $premium1=(isset($_POST['premium1']))?addslashes(htmlspecialchars($_POST['premium1'])):"0";
 $gold1=(isset($_POST['gold1']))?addslashes(htmlspecialchars($_POST['gold1'])):"0";
         mysql_query("update money set ref=".$ref1.",min=".$min1.",sheif=".$sheif1.",premium=".$premium1.",gold=".$gold1);
         echo 'Запрос успешно выполнен!<br/>';
         }
         $query=mysql_fetch_array(mysql_query("select min,ref,sheif,premium,gold from money"));
         echo '<form method="post">
         Минималка:<br/><input type="text" name="min1" value="'.$query["min"].'"/><br/>
         Реферальские (%):<br/><input type="text" name="ref1" value="'.$query["ref"].'"/><br/>
         Шейф:<br/><input type="text" name="sheif1" value="'.$query["sheif"].'"/><br/>
         Сколько добавлять за Premium (%)<br/><input type="text" name="premium1" value="'.$query["premium"].'"/><br/>
         Сколько добавлять за Gold (%)<br/><input type="text" name="gold1" value="'.$query["gold"].'"/><br/>
         <input type="hidden" name="action" value="1"/>
         <input type="submit" value="Изменить"/>
        </form>';
        break;
        // /////////////////////////////////
        // /АДМИНСКАЯ СТАТИСТИКА////////////
        // /////////////////////////////////
       case 'stat':
            /*
        ЭТО НЕ ПРОСТО СТАТИСТИКА.
        В НЕЕ ПОПАДАЮТ ДАЖЕ  НЕ ЗАСЧИТАННЫЕ ПЕРЕХОДЫ, ПЕРЕХОДЫ С ШЕЙФОМ И ПРОЧЕЕ.
        */
            $query = mysql_query("select clickSM, clickVM, clickSO, clickVO, clickSC, clickVC, clickS, clickV from money");
            $stat = mysql_fetch_array($query);
            echo "<b>Переходов сегодня:</b><br/>";
            echo "Мобильных - " . $stat["clickSM"] . "<br/>";
            echo "Opera Mini - " . $stat["clickSO"] . "<br/>";
            echo "Компьютеры - " . $stat["clickSC"] . "<br/>";
            echo "В сумме - " . $stat["clickS"] . "<br/>";
            echo "<b>Переходов всего:</b><br/>";
            echo "Мобильных - " . $stat["clickVM"] . "<br/>";
            echo "Opera Mini - " . $stat["clickVO"] . "<br/>";
            echo "Компьютеры - " . $stat["clickVC"] . "<br/>";
            echo "В сумме - " . $stat["clickV"] . "<br/>";
       break;
        /////////////////////////
        /////внутренняя рекла////
        case 'adv';
	$del=(isset($_GET['del']))?(int)($_GET['del']):"0";
	if($del){
		mysql_query("delete from adver where id_adv=".$del);
		echo 'Реклама удалена!'.$hr;
	}
	$name=(isset($_POST['name']))?htmlspecialchars(addslashes($_POST['name'])):"0";
	$urls=(isset($_POST['urls']))?htmlspecialchars(addslashes($_POST['urls'])):"0";
	$colday=(isset($_POST['colday']))?(int)($_POST['colday']):"7";
	if(($name)&&($urls)&&($colday)){
		if($colday<=0)$colday=7;
	         mysql_query("insert into adver set name='".$name."', url='".$urls."', colday=".$colday.", timeadd=NOW(), ts=".time());
	echo 'Реклама добавлена!';
	}
	echo '<form method="post">
	Название:<br/><input type="text" name="name" value=""/><br/>
	URL:<br/><input type="text" name="urls" value="http://"/><br/>
	Количество дней:<br/><input type="text" name="colday" value="7"/><br/>
	<input type="submit" value="Добавить"/>
	</form>'; 
	echo $hr;
	/////
	$query=mysql_query("select * from adver");
if(mysql_affected_rows()==0){
	echo 'Нет внутренней рекламы!';
}else{
	while($adv=mysql_fetch_array($query)){
		echo $adv['timeadd'].'<br/>';
		echo ''.$adv['name'].'|<a href="out.php?url='.$adv['url'].'">'.$adv['url'].'</a> <a href="'.$url.'&a=adv&del='.$adv['id_adv'].'">[DEL]</a><br/>';
		echo $hr;
	}
}
	break;
	case 'auc':
$query=mysql_query("select * from winner");
$winner=mysql_fetch_array($query);
if(!$winner[0]){
echo 'Победители отсутствуют...';
}
else
{
echo 'ID: '.$winner['id_zver'].'<br/>';
echo 'Сумма: '.$winner['summa'].'<br/>';	
}
	break;
}
        if ($a) {
            echo $hr . '<a href="' . $url . '">В админку</a>';
        } else {
            echo $hr . '<a href="index.php">Выход</a>';
        }
}
else
{
	echo 'Доступ запрещен!<br/>';
}
include_once("footer.php");
?>
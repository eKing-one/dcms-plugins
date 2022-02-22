<?php
/*
РЕГИСТРАЦИЯ
*/
$title = "Регистрация";
include_once("ini.php");
include_once("header.php");
echo diz("Регистрация", "header");
$a=(isset($_GET['a']))?$_GET['a']:"";
if (empty($a)) {
$id = (isset($_GET['id']))?(int)$_GET['id']:"0";///РЕФ. ИД
echo 'Регистрируясь, Вы соглашаетесь с <a href="file.php?view=rules">правилами</a>.'.$hr;///ПРЕДУПРЕЖДЕНИЕ
/////////////////////////////////////
//////ФОРМА РЕГИСТРАЦИИ//////////////
/////////////////////////////////////
echo $div["menu"];
echo '<form method="post" action="reg.php?a=reg">
Пароль<br/><input type="password" name="pass" value=""/><br/>
@-MAIL<br/><input type="text" name="mail" value="@"/><br/>
Кошелек WMR<br/><input type="text" name="wmr" value="R"/><br/>
ICQ<br/><input type="text" name="icq" value=""/><br/>
Введите цифры: <img src="captcha.php" alt="check"/><br/><input type="text" name="rand" value=""/><br/>
<input type="hidden" name="id_parent" value="' . $id . '"/>
<input type="submit" value="Зарегистрировать"/>
</form>';
echo $div["end"];
echo $hr;
echo '<a href="index.php">На главную</a>';
} elseif ($a == "reg") {
$pass=(isset($_POST['pass']))?$_POST['pass']:"";
$mail=(isset($_POST['mail']))?$_POST['mail']:"";
$wmr=(isset($_POST['wmr']))?$_POST['wmr']:"";
$icq=(isset($_POST['icq']))?$_POST['icq']:"";
$rand=(isset($_POST['rand']))?$_POST['rand']:"";
$id_parent=(isset($_POST['id_parent']))?$_POST['id_parent']:"";
$err=0;
// /////////проверяем пасс
if (empty($pass)) {
echo "Не введен пароль!<br/>";
$err++;
} else {
if (!ereg($check["PASSWORD"][0], $pass)) {
echo "Не верно введен пароль!<br/>";
$err++;
}
}
// /////////проверяем мыло
if (empty($mail)) {
echo "Не введен эл. ящик!<br/>";
$err++;
} else {
$res = @mysql_query("select * from zveri where (`mail`='" . $mail . "');");
$ros = @mysql_fetch_array($res);
$value = $ros['mail'];
if (!ereg($check["MAIL"][0], $mail)) {
echo "Не верно введен эл. ящик!<br/>";
$err++;
} elseif ($mail == $value) {
echo "Пользователь с таким @-mail уже существует!<br/>";
$err++;
}
}
// //////////проверяем кошель
if (empty($wmr)) {
echo "Не введен кошелек WMR!<br/>";
$err++;
} else {
$res = @mysql_query("select * from zveri where (`wmr`='" . $mail . "');");
$ros = @mysql_fetch_array($res);
$value = $ros['wmr'];
if (!ereg($check["WMR"][0], $wmr)) {
echo "Не верно введен номер кошелька!<br/>";
$err++;
} elseif ($wmr == $value) {
echo "Пользователь с таким кошельком WMR уже существует!<br/>";
            $err++;
        }
    }
    // ///////////ПРОВЕРЯЕМ АСЮ
    if (empty($icq)) {
        echo "Не введен ICQ!<br/>";
        $err++;
    } else {
        if (!ereg($check["ICQ"][0], $icq)) {
            echo "Не верно введен номер ICQ!<br/>";
            $err++;
        }
    }
    // /////////////ПРОВЕРЯЕМ ЗАЩИТУ КАРТИНКОЙ
    if ($rand != "".zapros("select rand from randoms where usa='".addslashes($usa)."' and ip='".$ip."'")."") {
        echo 'Не верно введены цифры с картинки!';
        $err++;
    }
    // //////////низ
    if ($err!=0) {
        echo '' . $hr . '<a href="reg.php'.(($id_parent!=0)?"?id=".$id_parent."":"").'">Регистрация</a><br/>';
    }
    // //////////регистрируем зверя
    if(empty($err)) {
        mysql_query("insert into zveri set pass='" . AddSlashes($pass) . "', mail='" . AddSlashes($mail) . "', ua='" . AddSlashes($usa) . "', wmr='" . AddSlashes($wmr) . "', datreg=NOW(), icq=" . $icq . ", id_parent=" . $id_parent) or die ("Извините, регистрация не доступна в данный момент");
        $uid=mysql_insert_id();
       echo $div['menu'];
        echo "Вы успешно зарегистрированны!<br/>";
        echo '' . $hr . '<a href="login.php?id=' . $uid . '&pass=' . $pass . '">Войти</a><br/>';
       echo $div['end'];
    }
}
include_once("footer.php");

?>
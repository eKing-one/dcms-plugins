<?php
/*
АВТОРИЗАЦИЯ.ВОССТАНОВЛЕНИЕ ДАННЫХ НА МЫЛО.
*/
$title = "Авторизация";
include_once("ini.php");
include_once("header.php");
echo diz($title, "header");
$a=(isset($_GET['a']))?$_GET['a']:"";
if (empty($a)) {
if ((isset($_GET['id'])) && (isset($_GET['pass']))) {
$id=trim(mysql_escape_string($_GET['id']));
$pass=trim(mysql_escape_string($_GET['pass']));
$query = mysql_query("select * from zveri where id_zver='" . $id . "' and pass='" . $pass . "'");
$user = mysql_fetch_array($query, MYSQL_ASSOC);
if (empty($user)) {
echo $div["menu"];
echo "Авторизация провалена!<br/>Проверьте правильность введенных Вами данных или воспользуйтесь ".url("login","a=mail","восстановлением")." пароля";
echo $div["end"];
echo $hr;
echo url("login","","Назад");
}
elseif($user["status"]!="activ"){///ЕСЛИ ЗАБАНЕН
header("location: usban.php");
}
else {
$ses = rand(100000000,999999999);//типа ид сесии
$last_ua = zapros("select ua from zveri where id_zver=" . $user["id_zver"]);////ПРОШЛЫЙ ВХОД
mysql_query("update zveri set ua='" . $usa . "', ip='".$ip."',ses='".$ses."',timeses=".time()." where id_zver=" . $user["id_zver"]);
echo $div["menu"];
echo "Вы успешно авторизовались!<br/>";
echo "Последний вход был с " . $last_ua . "!<br/>";
echo $div["end"];
echo $hr . '<center><a href="cabinet.php?sid='.$ses.'">Вход</a></center>';
}
} else {
echo $div["menu"];
echo '<form method="get" action="'.$_SERVER['REQUEST_URI'].'">
<div>
ID:<br/>
<input type="text" name="id" value=""/><br/>
Пароль:<br/><input type="password" name="pass" value=""/><br/>
<input type="submit" value="Войти"/>
</div>
</form>';
echo $div["end"];
echo $hr.url("login", "a=mail", "Забыли пароль?").$hr;
echo url();
}
}
//////////ВОССТАНОВЛЕНИЕ ДАННЫХ НА МЫЛО
elseif ($a == "mail") {
$mail=(isset($_POST['mail']))?$_POST['mail']:"";
if (empty($_POST['mail'])) {
echo '<form method="post">
Ваш @-mail:<br/><input type="text" name="mail" value="@"/><br/>
<input type="submit" value="Выслать"/>
</form>';
echo url("login");
} elseif (($mail) && (!ereg($check["MAIL"][0], $mail))) {
echo "Не правильно введен эл. ящик!<br/>";
echo url("login", "a=mail");
} else {
$query = mysql_query("select * from zveri where mail='" . $mail . "'");
$user = mysql_fetch_array($query, MYSQL_ASSOC);
if (!$user) {
echo "<b>Пользователь с таким @-mail отсутствует!</b><br/>";
echo url("login", "a=mail");
} elseif ($user["status"] == "ban") {
echo "<b>Пользователь забанен!</b><br/>";
echo url("login", "a=mail");
} else {
$subject = "Восстановление данных";
$body = "Вы заказали восстановление данных на htpp://" . $_SERVER["HTTP_HOST"] . "
ID: " . $user["id_zver"] . "
       Пароль: " . $user["pass"] . "";
            $adds = "From: $mail \n";
            $adds .= "X-sender: < $mail >\n";
            $adds .= "Content-Type: text/plain; charset=utf-8\n";
            mail($mail, $subject, $body, $adds);
            echo 'Данные успешно восстановлены!';
            echo url("login", "", "Вход");
        }
    }
}
 else {
    Header("location: /index.php");
}
include_once("footer.php");

?>
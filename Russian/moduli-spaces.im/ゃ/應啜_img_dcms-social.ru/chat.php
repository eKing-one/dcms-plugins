<?php
//////НЕ ЗНАЮ ЧТО ЭТО.ПОКЛАЦАЛ ПО КЛАВЕ И ПОЛУЧИЛОСЬ ЭТО.
$title = "Mini-Чат";
include_once("ini.php");
include_once("header.php");
echo diz($title, "header");
if((isset($_GET['pwd']))&&($_GET['pwd']==$apanel)){$admin=1;} else {$admin=0;};
$user = info();
if((!$user)&&(!$admin)){
header("location: index.php");
}
$del=(isset($_GET['del']))?$_GET['del']:"0";
$text=(isset($_POST['text']))?$_POST['text']:"0";
// /////////////////////УДАЛЕНИЕ СООБЩЕНИЯ
if (($del!=0) && ($admin)) {
$del = (int)$del;
$query = mysql_query("update chat set text='..:[DELETED]:..' where id_mess=" . $del);
if ($query) {
echo "Сообщение успешно удалено." . $hr;
header("location: chat.php?pwd=".$apanel."");
} else {
echo "Сообщение не может быть удалено в данный момент." . $hr;
}
}
// /////////////////////ВСТАВКА СООБЩЕНИЯ
if ($text) {
$text = htmlspecialchars(addslashes(mysql_escape_string($text)));
if (!zapros("select id_mess from chat where text='" . $text . "'")) {
if($admin){$zx="admin=1";} else {$zx="id_zver=" . $user["id_zver"] . "";}
$text=substr($text,0,500);
$query = mysql_query("insert into chat set ".$zx.", text='" . $text . "', date=NOW()");
if ($query) {
echo "Сообщение успешно добавлено." . $hr;
} else {
echo "Сообщение не может быть добавлено в данный момент." . $hr;
}
}
}
// /////ФОРМА
echo $div['menu'];
echo '<div style="text-align:center;"><form method="post">
Сообщение:<br/><input type="text" name="text" value=""/><br/>
<input type="submit" value="Написать"/>
</form></div>';
echo $div['end'];
echo $hr;
$startan=(isset($_GET['startan']))?$_GET['startan']:"0";
$startan = intval($startan);
if ($startan < 0) $startan = 0; //ВЫРАЖАЕМ СТРАНИЦУ
$q = mysql_query("select * from chat"); //ОБЩИЙ ЗАПРОС
$query = mysql_query("select * from chat order by id_mess desc limit " . $startan . "," . $colzap . ""); //ЗАПРОС НЕОБХОДИМЫХ
if (mysql_affected_rows() == 0) {
echo "Нет сообщений..."; //ОТСУТСТВИЕ
} else {
while ($chat = mysql_fetch_array($query)) { // /ЛЮБИМЫЙ ЦЫКЛ ВЫВОДИТ НУЖНУЮ ИНФУ
echo $div['menu'];
echo $chat["date"] . "<br/>"; //ДАТА
if($chat["id_zver"]==0){echo '<font color="red">ADMIN</font><br/>';}else {echo 'ID: '.$chat["id_zver"].'<br/>'; }
echo $chat["text"];
if ($admin==1) {
echo url("chat", "pwd=".$apanel."&del=" . $chat["id_mess"] . "", "[del]");
}
echo $div['end'];
}
}
$col = mysql_num_rows($q); //КОЛИЧЕСТВО
if($admin){$e="pwd=".$apanel;} else {$e="";}
if ($startan != 0) {
echo $hr . url("chat", $e."&startan=" . ($startan - $colzap) . "", "Назад");
}
if ($col > $startan + $colzap) {
echo $hr . url("chat", $e."&startan=" . ($startan + $colzap) . "", "Вперед");
}

echo $hr;
if(!$admin){ echo url('cabinet');} else {echo '<a  href="admin.php?pwd='.$apanel.'">В админку</a>';}
// //////////////
include_once("footer.php");

?>
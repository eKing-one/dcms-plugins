<?php
$title = "Форум";
include_once("ini.php");
include_once("header.php");
if((isset($_GET['pwd']))&&($_GET['pwd']==$apanel)){$admin=1;} else {$admin=0;};
$user = info();
if((!$user)&&(!$admin)){
header("location: index.php");
}
$del=(isset($_GET['del']))?$_GET['del']:"0";
$text=(isset($_POST['text']))?$_POST['text']:"0";
$theme=(isset($_GET['theme']))?$_GET['theme']:"0";
$arr=array('Вопросы по системе','Ошибки/жалобы','Куплю/Продам','Программирование','Общение/Флуд');
//////
if(!$theme){
echo diz($title, "header");
echo $div['menu'];
for($i=0;$i<5;$i++)
{
if($user){
echo url('forum','theme='.($i+1).'','»'.$arr[$i].'('.zapros("select count(id_post) from forum where id_theme=".($i+1)."").')');
}
else
{
echo url('forum','pwd='.$apanel.'&theme='.($i+1).'','»'.$arr[$i].'('.zapros("select count(id_post) from forum where id_theme=".($i+1)."").')');
}
}
echo $div['end'];
}
//////
else
{
$theme=(int)$theme;
if((!$theme)||($theme<0)||($theme>5))$theme=1;
echo diz($arr[$theme-1], "header");
// /////////////////////УДАЛЕНИЕ СООБЩЕНИЯ
if (($del!=0) && ($admin)) {
$del = (int)$del;
$query = mysql_query("update forum set text='..:[DELETED]:..' where id_post=" . $del);
if ($query) {
header("location: forum.php?pwd=".$apanel."&theme=".$theme."");
} else {
echo "Сообщение не может быть удалено в данный момент." . $hr;
}
}
// /////////////////////ВСТАВКА СООБЩЕНИЯ
if ($text) {
$text = htmlspecialchars(addslashes(mysql_escape_string($text)));
if (!zapros("select id_post from forum where text='" . $text . "'")) {
if($admin){$zx="admin=1";} else {$zx="id_zver=" . $user["id_zver"] . "";}
$text=substr($text,0,500);
$query = mysql_query("insert into forum set ".$zx.", text='" . $text . "', date=NOW(), id_theme=".$theme);
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
$q = mysql_query("select * from forum where id_theme=".$theme); //ОБЩИЙ ЗАПРОС
$query = mysql_query("select * from forum where id_theme=".$theme." order by id_post desc limit " . $startan . "," . $colzap . ""); //ЗАПРОС НЕОБХОДИМЫХ
if (mysql_affected_rows() == 0) {
echo "Нет сообщений..."; //ОТСУТСТВИЕ
} else {
    while ($forum = mysql_fetch_array($query)) { // /ЛЮБИМЫЙ ЦЫКЛ ВЫВОДИТ НУЖНУЮ ИНФУ
        echo $div['menu'];
        echo $forum["date"] . "<br/>"; //ДАТА
        if($forum["id_zver"]==0){echo '<font color="red">ADMIN</font><br/>';}else {echo 'ID: '.$forum["id_zver"].'<br/>'; }
        echo $forum["text"];
        if ($admin==1) {
            echo url("forum", "pwd=".$apanel."&theme=".$theme."&del=" . $forum["id_post"] . "", "[del]");
        }
        echo $div['end'];
    }
}
$col = mysql_num_rows($q); //КОЛИЧЕСТВО
if($admin){$e="pwd=".$apanel;} else {$e="";}
if ($startan != 0) {
    echo $hr . url("forum", $e."&theme=".$theme."&startan=" . ($startan - $colzap) . "", "Назад");
}
if ($col > $startan + $colzap) {
    echo $hr . url("forum", $e."&theme=".$theme."&startan=" . ($startan + $colzap) . "", "Вперед");
}
}
echo $hr;
if(!$admin){ echo url('cabinet');} else {echo '<a  href="admin.php?pwd='.$apanel.'">В админку</a>';}
// //////////////
include_once("footer.php");

?>
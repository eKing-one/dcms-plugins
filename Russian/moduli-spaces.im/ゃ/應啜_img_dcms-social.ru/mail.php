<?php
$title = "Почта";
include_once("ini.php");
include_once("header.php");
$user = info();
if((!$user)&&(!$admin)){
header("location: index.php");
}
$id=(isset($_GET['id']))?$_GET['id']:"0";
$answer=(isset($_GET['answer']))?$_GET['answer']:"0";
$text=(isset($_POST['text']))?$_POST['text']:"";
$a=(isset($_GET['a']))?$_GET['a']:"0";
$startan=(isset($_GET['startan']))?$_GET['startan']:"0";
$startan = intval($startan);
if ($startan < 0) $startan = 0;
///
switch($a){
///
default:
echo diz($title, "header");
//
echo $div['menu'];
echo url('mail','a=in', 'Входящие ('.zapros("select count(id_mail)from mail where id_to=".$user['id_zver']).')');
echo url('mail','a=out', 'Исходящие ('.zapros("select count(id_mail)from mail where id_from=".$user['id_zver']).')');
echo url('mail','a=new', 'Написать');
echo $div['end'];
break;
///
case 'in':
echo diz("Входящие","header");
if(!$id){
$q = mysql_query("select * from mail");
$query = mysql_query("select * from mail where id_to=".$user['id_zver']." order by id_mail desc limit " . $startan . "," . $colzap . "");
if (mysql_affected_rows() == 0) {
echo "Нет сообщений..."; //ОТСУТСТВИЕ
} else {
while ($mails = mysql_fetch_array($query)) { // /ЛЮБИМЫЙ ЦЫКЛ ВЫВОДИТ НУЖНУЮ ИНФУ
echo $div['menu'];
echo $mails["date"] . "<br/>"; //ДАТА
if($mails["folder"]=="new")echo "NEW - ";
echo url('mail','a=in&id='.$mails['id_mail'].'',$mails["subj"].' (ID: '.$mails['id_from'].')');
echo $div['end'];
}
}
$col=mysql_query($q);
if ($startan != 0) {
echo $hr . url("mail", "a=in&startan=" . ($startan - $colzap) . "", "Назад");
}
if ($col > $startan + $colzap) {
echo $hr . url("mail", "a=in&startan=" . ($startan + $colzap) . "", "Вперед");
}
}
else
{
$id=(int)$id;
if(!$id)header("location: mail.php?sid=".$sid."");
$query=mysql_query("select * from mail where id_to=".$user['id_zver']." and id_mail=".$id);
$mails=mysql_fetch_array($query);
if(!$mails){
echo 'Вы ошиблись адресом!<br/>';
}
else
{
if($mails['folder']=="new") mysql_query("update mail set folder='view' where id_mail=".$id);
echo $div['menu'];
echo 'Отправлено: '.$mails['date'].'<br/>';
echo 'Отправитель: '.$mails['id_from'].'<br/>';
echo 'Тема: '.$mails['subj'].'<br/>';
echo 'Текст: '.$mails['text'].'<br/>';
echo url('mail','a=new&answer='.$id.'','Ответить');
echo $div['end'];
}
}
break;
///
case 'out':
echo diz("Исходящие","header");
if(!$id){
	$q = mysql_query("select * from mail");
$query = mysql_query("select * from mail where id_from=".$user['id_zver']." order by id_mail desc limit " . $startan . "," . $colzap . ""); 
if (mysql_affected_rows() == 0) {
    echo "Нет сообщений..."; //ОТСУТСТВИЕ
} else {
    while ($mails = mysql_fetch_array($query)) { // /ЛЮБИМЫЙ ЦЫКЛ ВЫВОДИТ НУЖНУЮ ИНФУ
echo $div['menu'];
        echo $mails["date"] . "<br/>"; //ДАТА
        echo url('mail','a=out&id='.$mails['id_mail'].'',$mails["subj"].' (ID: '.$mails['id_to'].')');     
echo $div['end'];    
}
}
$col=mysql_query($q);
if ($startan != 0) {
    echo $hr . url("mail", "a=out&startan=" . ($startan - $colzap) . "", "Назад");
}
if ($col > $startan + $colzap) {
    echo $hr . url("mail", "a=out&startan=" . ($startan + $colzap) . "", "Вперед");
}
}
else
{
$id=(int)$id;
if(!$id)header("location: mail.php?sid=".$sid."");
$query=mysql_query("select * from mail where id_from=".$user['id_zver']." and id_mail=".$id);
$mails=mysql_fetch_array($query);
if(!$mails){
echo 'Вы ошиблись адресом!<br/>';
}
else
{
echo $div['menu'];	
echo 'Отправлено: '.$mails['date'].'<br/>';
echo 'Получатель: '.$mails['id_to'].'<br/>';
echo 'Тема: '.$mails['subj'].'<br/>';
echo 'Текст: '.$mails['text'].'<br/>';
echo $div['end'];
}	
}
break;
////////
case 'new':
echo diz("Написать сообщение","header");
if((isset($_POST['to']))&&(isset($_POST['subj']))&&(isset($_POST['text']))){
	$to=(int)$_POST['to'];
	$subj=htmlspecialchars(addslashes(trim($_POST['subj'])));
	$text=htmlspecialchars(addslashes(trim($_POST['text'])));
	$error=array();
	if(!$to){
		$error[]='Введите ID получателя!<br/>';
	}
	if((!$subj)||(strlen($subj)>100)){
		$error[]='Тема не введена либо слишком длинная!Тема не должна превышать 100 символов!<br/>';
	}
	if((strlen($text)<=0)||(strlen($text)>500)){
		$error[]='Сообщение не введено либо слишком длинно!Сообщение не должно превышать 500 символов!<br/>';
	}
	$zx=count($error);
	if($zx>0){
		for($i=0;$i<$zx;$i++){
			echo $error[$i];
			echo $hr;
		}
	}
	else
	{
		mysql_query("insert into mail set id_to=".$to.", id_from=".$user['id_zver'].", date=NOW(), subj='".$subj."', text='".$text."', folder='new'");
		header("location: mail.php?sid=".$sid."&a=out");
		
	}
}
/////
$answer=(int)$answer;
$to="";
$subj="";
if($answer){
	$query=mysql_query("select * from mail where id_mail=".$answer." and id_to=".$user['id_zver']."");
	$answer=mysql_fetch_array($query);
	if($answer){
		$to=$answer['id_from'];
		$subj='Re: '.$answer['subj'];
	}
}
echo $div['menu'];
echo '<form method="post">
ID:<br/><input type="text" name="to" value="'.$to.'"/><br/>
Тема:<br/><input type="text" name="subj" value="'.$subj.'"/><br/>
Сообщение<br/><input type="text" name="text" value=""/><br/>
<input type="submit" value="Отправить"/><br/>
</form>';
echo $div['end'];
break;
}
/////////
if($a){echo $hr; echo url('mail','','В почту');}
echo $hr;
echo url('cabinet');
// //////////////
include_once("footer.php");

?>
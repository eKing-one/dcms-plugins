<?php
$title="Новости системы";
include_once("ini.php");
include_once("header.php");
echo diz("Новости", "header");
$user=info();
if($user){$b="cabinet";} else {$b="index";}
////
if((isset($_GET['pwd']))&&($_GET['pwd']==$apanel)){$admin=1;} else {$admin=0;};
$id=(isset($_GET['id']))?$_GET['id']:"";
$start=(isset($_GET['start']))?$_GET['start']:"0";
$start=(int)$start;
if(!$start<0)$start=0;
////
if(!$id){   ////новости
$q=mysql_query("select * from news");
$query=mysql_query("select * from news order by id_new desc limit ".$start.",".$colzap);
if(mysql_affected_rows()==0){
echo "Новостей пока нет...";
} else {
while($new=mysql_fetch_array($query)){
echo $div['menu'];
echo $new["date"]." - ".$new["time"]."<br/>";
echo $new["text"]."<br/>";
if($user) echo url('news','id='.$new["id_new"].'','Комментарии ('.zapros("select count(id_comm) from comm where id_new=".$new["id_new"]).')');
echo $div['end'];
}
}
$count=mysql_num_rows($q);
if($start){echo url("news","start=".($start-$colzap)."","Назад");}
if($count>$start+$colzap){echo $hr.url("news","start=".($start+$colzap)."","Вперед");}
}
else   /////комменты
{
$id=(int)$id;
if((!$id)||((!$admin)&&(!$user))||(!zapros("select * from news where id_new=".$id))){header("location: news.php?sid=".$sid);}
/////
if(isset($_POST['text'])){
$text=htmlspecialchars(addslashes(trim($_POST['text'])));
if(!zapros("select * from comm where id_zver=".$user['id_zver']." and id_new=".$id." and text='".$text."'"))
///
{
$text=substr($text,0,500);
$query=mysql_query("insert into comm set id_new=".$id.", id_zver=".$user['id_zver'].", text='".$text."', date=NOW()");
if($query){echo "Комментарий успешно добавлен!".$hr;} else {echo "Комментарий не может быть добавлен в данный момент!".$hr;}
}
///
}
///////
if((isset($_GET['del']))&&($admin)){
$del=(int)$_GET['del'];
$query=mysql_query("update comm set text='.:[DELETED]:.' where id_comm=".$del);
if($query){echo "Комментарий успешно удален!".$hr;} else {echo "Комментарий не может быть удален в данный момент!".$hr;}
}
///////
echo '<div style="text-align: center;"><form method="post" action="'.$_SERVER['REQUEST_URI'].'">
Комментарий:<br/><input type="text" name="text" value=""/><br/>
<input type="submit" value="Написать"/><br/>
</form></div>';
echo $hr;
$q=mysql_query("select * from comm where id_new=".$id."");
$query=mysql_query("select * from comm where id_new=".$id." order by id_comm desc limit ".$start.",".$colzap);
if(mysql_affected_rows()==0){
echo "Комментариев пока нет...";
} else {
while($comm=mysql_fetch_array($query)){
echo $comm["date"]."<br/>";
echo "ID: ".$comm["id_zver"]."<br/>";
echo $comm["text"]."<br/>";
if($admin)echo url('news','pwd='.$apanel.'&id='.$id.'&del='.$comm['id_comm'].'','[del]');
echo $hr;
}
}
$count=mysql_num_rows($q);
if($admin){$zz="pwd=".$apanel."&";} else {$zz="";}
if($start){echo url("news","".$zz."id=".$id."&start=".($start-$colzap)."","Назад");}
if($count>$start+$colzap){echo $hr.url("news","".$zz."id=".$id."&start=".($start+$colzap)."","Вперед");}
if(!$admin)echo $hr.url('news','','К новостям');
}
if(!$admin){echo $hr.url($b);} else {echo url('admin','pwd='.$apanel.'','В админку');}
////////////////
include_once("footer.php");
?>
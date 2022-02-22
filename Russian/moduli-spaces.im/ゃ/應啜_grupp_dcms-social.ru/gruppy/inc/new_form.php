<?
if($_GET['new']=='ok'){}
else
{
echo'<form method="post" action="?r='.$r.'&new=ok">';
echo'Название<br/>';
echo'<input type="text" name="name"><br/>';
echo'Описание<br/>';
echo'<textarea name="desc"></textarea><br/>';
echo'<input type="submit" value="Создать"></form><br/>';
}
?>
<?
echo'<form method="post" action="?r='.$r.'&new">';
echo'Название<br/>';
echo'<input type="text" name="name" maxlength="64"><br/>';
echo'Сообщение<br/>';
echo'<textarea name="msg"></textarea><br/>';
echo'Теги(через запятую)<br/>';
echo'<input type="text" name="tags" maxlength="128"><br/>';
echo'Приватность<br/>';
echo'<select name="readers">';
echo'<option value="0">Читают и комментируют все</option>';
echo'<option value="1">Читают все, комментируют друзья</option>';
echo'<option value="2">Читают и комментируют друзья</option>';
echo'</select><br/>';
echo'<input type="submit" name="add" value="Создать"></form><br/>';
?>
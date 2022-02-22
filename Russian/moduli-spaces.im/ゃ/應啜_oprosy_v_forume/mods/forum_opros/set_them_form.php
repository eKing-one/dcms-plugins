<?php
//valerik_mod Опрос в форуме--------------------------------
if (isset($_GET['act']) && $_GET['act']=='add_opros' && ((user_access('forum_them_edit') && $ank2['level']<$user['level']) || $ank2['id']==$user['id']))
{
$q_kolvo=10;	//Кол-во вариантов

		//Обработка формы------------------------
		if(isset($_POST['del_opros']))
		{
		mysql_query("UPDATE `forum_t` SET `opros` = '',`otvet`='' WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id` = '$them[id]'");
		header("location:/forum/$forum[id]/$razdel[id]/$them[id]/?$passgen");
		exit;
		}
		
		if(isset($_POST['opros_q']))
		{
		$quetion=htmlspecialchars(my_esc($_POST['opros_q']));
		$answers=Array();
			for($i=1;$i<=$q_kolvo;$i++)
			{
			if(empty($_POST['ans_'.$i]))continue;
			echo $_POST['ans_'.$i];
			$ans=htmlspecialchars(my_esc($_POST['ans_'.$i]));		
			if(!$ans)continue;			
			$answers[$i]=$ans;			
			}
			
		$opros['q']=$quetion;
		$opros['a']=$answers;
		$opros_str=serialize($opros);
		mysql_query("UPDATE `forum_t` SET `opros` = '$opros_str' WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id` = '$them[id]'");
		header("location:/forum/$forum[id]/$razdel[id]/$them[id]/?$passgen");
		exit;
		}
		//---------------------------------------

?>
<a href="?">Отмена</a>
<form method="post" action="">
Вопрос:<br />
<textarea name="opros_q"><?=$vopros?></textarea><br /><br />
Ответы:<br />
<?for($i=1;$i<=$q_kolvo;$i++):?>
<?=$i?>: <input type="text" name="ans_<?=$i?>" value="<?=$answers[$i]?>"/><br />
<?endfor?>
<input type="submit" value="Сохранить" /> 
<input type="submit" name="del_opros" value="Удалить" />
</form>
<?}?>
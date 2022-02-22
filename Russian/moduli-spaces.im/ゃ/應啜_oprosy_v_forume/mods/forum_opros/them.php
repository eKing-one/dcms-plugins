<?php
$vopros='';
$answers=Array();

if($them['opros'])
{
$arr=unserialize($them['opros']);
$vopros=$arr['q'];
$answers=$arr['a'];

$arr=unserialize($them['otvet']);
$otvet=$arr['a'];
$opros_users=$arr['u'];
$is_golos=substr_count($opros_users,','.$user['id'].',');

	//Обработка результата-----------
	if($user['id'] AND isset($_GET['opros_ans']) AND !$is_golos)
	{
	$ans_id=(int)$_GET['opros_ans'];
	if($opros_users)$opros_users.=$user['id'].',';
	else $opros_users.=','.$user['id'].',';
	
	$otvet[$ans_id]=$otvet[$ans_id]+1;
	
	$arr=Array();
	$arr['a']=$otvet;
	$arr['u']=$opros_users;
	$otvet_str=serialize($arr);
	mysql_query("UPDATE `forum_t` SET `otvet` = '$otvet_str' WHERE `id_forum` = '$forum[id]' AND `id_razdel` = '$razdel[id]' AND `id` = '$them[id]'");
	}
	//-------------------------------

$all_otvets=array_sum($otvet);	//100%	
foreach($answers AS $key=>$val)
{
$procents[$key]=round($otvet[$key]*100/$all_otvets);
}

//Вывод опроса--------
?>
<div style="border:solid 1px #98B7F7;padding:15px;background-color:#F2F5FF;color:#000000;">
<div style="color:#465A90;"><b><?=$vopros?></b></div>
<small>
<table style="width:100%;">
<?foreach($answers AS $key=>$val):?>
<tr>
<td>
<?=$val?>
<div style="border:1px solid #7EC1FF;height:15px;">
<div style="background-color:#62B3FF;height:15px;width:<?=$procents[$key]?>%;">&nbsp;</div>
<span style="position:relative;top:-15px;left:5px;"><?=$otvet[$key]?> (<?=$procents[$key]?> %)</span>
</div>
</td>
<?if(!$is_golos):?><td><span style="position:relative;top:12px;">[<a style="color:#000000;" href="?opros_ans=<?=$key?>">+</a>]</span></td><?endif?>
</tr>
<?endforeach?>
</table>
</small>
</div>

<?
}
?>
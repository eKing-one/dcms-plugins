<?php
echo "<div class='rowup'><img src='/farm/img/garden.png' alt='' /> <a href='/farm/garden/'>Ваш огород</a> / <b>Информация о растении</b></div>";
$int=intval($_GET['id']);
$notis = dbresult(dbquery("SELECT COUNT(*) FROM `farm_plant` WHERE  `id` = '$int'"),0);
if ($notis==0)
{
echo "<div class='err'>Нет такого растения</div>";
echo "<img src='/img/back.png' alt='' /> <a href='shop.php'>Назад</a>";
include_once '../sys/inc/tfoot.php';
exit;
}
$post = dbarray(dbquery("select * from `farm_plant` WHERE  `id` = '$int'  LIMIT 1")); 

if (isset($_POST['opis']) && $_POST['opis']!=NULL)
{
$opis=$_POST['opis'];//过滤代码
dbquery("UPDATE `farm_plant` SET `opis` = '$opis' WHERE `id` = '$post[id]' LIMIT 1");
}


$timediff=$post['time'];
$oneMinute=60; 
$oneHour=60*60; 
$oneDay=60*60*24; 
$dayfield=floor($timediff/$oneDay); 
$hourfield=floor(($timediff-$dayfield*$oneDay)/$oneHour); 
$minutefield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour)/$oneMinute); 
$secondfield=floor(($timediff-$dayfield*$oneDay-$hourfield*$oneHour-$minutefield*$oneMinute)); 
if($dayfield>0)$day=$dayfield.'д. ';
if($minutefield>0)$minutefield=$minutefield."м.";else$minutefield='';
$time_1=$day.$hourfield."ч. ".$minutefield;

echo "<div class='rowdown'>";
echo "<center><img src='/farm/shopimg/$post[id].jpeg' alt='$post[name]' /></center><br />";

echo "&raquo; <b>$post[name]</b><br />";
if ($post['opis']!=NULL)
{
echo "&raquo; <b>".esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($post['opis']))))))))."</b><br />";
}
echo "&raquo; Требуется уровень: <b>$post[level]</b><br />";
echo "&raquo; Cтоимость: <b>".$post['cena']."</b> золота<br/>";
echo "&raquo; Cозреваниe: <b>".$time_1."</b> <br/>";
echo "&raquo; Урожай: от <b>".$post['rand1']."</b> шт. до <b>".$post['rand2']."</b> шт.<br/>";
$allxp = $post['xp']*$post['rand1'];
echo "&raquo; Добавляет здоровья: <b>".$post['xp']."</b> за 1 шт.(<b>$allxp</b>)<br/>";
$costall = $post['dohod']*$post['rand1'];
echo "&raquo; Цена за единицу: <b>$post[dohod]</b> за 1 шт.(<b>".$costall."</b>)<br/>";
$allopyt = $post['oput']*$post['rand1'];
echo "&raquo; Опыт за единицу: <b> ".$post['oput']."</b> за 1 шт.(<b>$allopyt</b>)<br/>";
if ($post['let']==1)
{
echo "&raquo; Однолетнее растение, плодоносит <b>один</b> раз";
}

if ($post['let']==2)
{
echo "&raquo; Двулетнее растение, плодоносит <b>два</b> раза";
}

if ($post['let']>2)
{
echo "&raquo; Многолетнее растение, плодоносит <b>$post[let]</b> раз";
}


if ($post['opis']==NULL && $user['level']==4)
{
echo "<form action='/farm/shop.php?id=".$int."&amp;$passgen' method='post'>";
echo "<input type='text' maxlenght='1024' size='20' name='opis' /><br />";
echo "<input type='submit' value='OK' />";
echo "</form>";
}

echo "</div>";
$iplus=$post['id']+1;
$iminus=$post['id']-1;
echo "<div class='rowup'>";
echo "<a href='/farm/shop/plant/$iminus'>&laquo; Пред.</a> | <a href='/farm/shop/plant/$iplus'>След. &raquo;</a>";
echo "</div>";

if($level>=$post['level']){
echo "<div class='rowdown'><form method='post' action='/farm/shop.php?id=".$int."&amp;$passgen'>\n";
echo "Купить (кол-во):<br />\n";

echo "<input type='text' name='kupit' size='4'/> <input type='submit' name='save' value='Купить' />";
echo "</form></div>\n";
}else{echo '<div class="err">Данное растение доступно с '.$post['level'].' уровня.</div>';}
$kup=$post['cena']*$_POST['kupit'];
if(isset($_POST['kupit']) && $fuser['gold']>=$kup && $_POST['kupit']>0)
{
dbquery("INSERT INTO `farm_semen` (`kol` , `semen`, `id_user`) VALUES  ('".intval($_POST['kupit'])."', '".$int."', '".$user['id']."') ");
dbquery("UPDATE `farm_user` SET `gold` = `gold`- $kup WHERE `uid` = '".$user['id']."' LIMIT 1");
$_SESSION['plidb']=$post['id'];
header('Location: /farm/shop/?buy_ok');
}
if(isset($_POST['kupit']) && strlen2($_POST['kupit'])==0 || isset($_POST['kupit']) && $_POST['kupit']<1)echo "<div class='err'>Поле не заполнено</div>";

if (isset($_POST['kupit']) && $fuser['gold']<$kup)
{
$_SESSION['plidb']=$post['id'];
header('Location: /farm/shop/?buy_no');
}
?>
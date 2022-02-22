<?
if ($user['id']<1) header('Location: /index.php');
$cena1 = '3'; // цена линейки
$diz ='p_t';
$dizz ='p_m';

function text_out($text) { $text = stripslashes(htmlspecialchars(trim($text))); return $text; } # Обработка текста

$arrs=mysql_fetch_array(mysql_query("SELECT * FROM`photo_lineup` WHERE `id_user`='".$user['id']."'"));
?>
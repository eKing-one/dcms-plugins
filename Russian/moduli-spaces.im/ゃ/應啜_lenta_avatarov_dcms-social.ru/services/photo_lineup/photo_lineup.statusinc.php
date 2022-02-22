<style>
.liders{
margin: 1px;
font-weight: normal;
border: 1px solid #D4E0EB;
background-color: #FFFFFF;
-moz-border-radius: 4px;
-webkit-border-radius: 4px;
border-radius: 4px; 
}

.photo_lineup {
width: 100%;
border-collapse: collapse;
border-spacing: 0;
}

.photo_lineup img{
margin: 2px;
border: 1px solid #C9C9C9;
border-radius: 4px;
}

.photo_lineup td a {
display: block;
text-align: center;
}

.photo_lineup td+td {
border-left: 1px solid #D4E0EB;
}
</style>
<div class='liders' style='margin-bottom:3px;overflow-y:hidden;'>
<table class='photo_lineup'>
<tr><td>
<?
$q=mysql_query("SELECT * FROM `photo_lineup` LIMIT 5");
while ($photo_lineup = mysql_fetch_assoc($q))
{
$ank=get_user($photo_lineup['id_user']);
echo "<td><a href='/info.php?id=".$ank['id']."'>\n";
avatarvip($ank['id']);
echo "</a></td>\n";
}
?>

<?

if (isset($user))echo "</a></td><td><a href='services/photo_lineup/photo_lineup.php'><b>+</b></a></td></tr></table></div>";
else echo "</a></td><td><a href='aut.php'><b>+</b></a></td></tr></table></div>";

?>
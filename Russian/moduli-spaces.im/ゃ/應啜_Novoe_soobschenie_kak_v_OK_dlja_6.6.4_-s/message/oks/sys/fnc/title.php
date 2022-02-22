<?


function title($title=NULL)
{
global $set, $k_new, $k_new_fav;
if ($set['web']==false)
{
if ($title==NULL)$title=$set['title'];
if ($k_new!=0 || $k_new_fav!=0)$title = 'Вам новое сообщение';


echo "<div class='title'>$title</div>\n";

echo "<div class='rekl'>\n";
rekl(1);
echo "</div>\n";
}
}

?>
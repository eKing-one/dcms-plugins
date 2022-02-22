<td>
<div id="right_outer">
<div id="right_top"></div>
<div id="right_inner_float">
<div id="right_inner">
<div class="moduletable">
<h3><?
if (isset($user))
echo "Вы:&nbsp;$user[nick]";
else
echo "Вы:&nbsp;Гость";
include_once H.'style/themes/'.$set['set_them'].'/box.php';

?></h3>
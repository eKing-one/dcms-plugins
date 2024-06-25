<?php
function farm_event($usid = NULL)
{
global $user;
if ($usid==NULL)
{
$uid=$user['id'];
}
else
{
$uid=intval($usid);
}

$checkevent = dbresult(dbquery("SELECT COUNT(*) FROM `farm_event` WHERE `uid` = '$uid'"),0);
if ($checkevent!=0)
{
$event = dbarray(dbquery("SELECT * FROM `farm_event` WHERE `uid` = '$uid' ORDER BY id LIMIT 1"));
echo "<div class='event'>";
echo "<table class='post'><td rowspan='2' style='width:33px'>";
echo "<img src='/img/event.png' alt='' /></td><td>消息!</td></tr><tr><td>";
echo "".esc(trim(br(bbcode(smiles(links(stripcslashes(htmlspecialchars($event['msg']))))))))."";
echo "</td></tr></table></div>";
dbquery("DELETE FROM `farm_event` WHERE `id` = '$event[id]' LIMIT 1");
dbquery("OPTIMIZE TABLE `farm_event`");
}
}
?>
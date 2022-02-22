<?
function vip($user=NULL){
$arr=mysql_fetch_array(mysql_query("SELECT * FROM `vip_premimum` WHERE `id_user`='$user' LIMIT 1"));
if($arr['time']>=time())	{
echo "    <a href='/user/money/vip.php'><img src='/style/vip/$arr[nomer].png'> </a>";}}
?>
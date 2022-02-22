<?php
global $sql;
$query=mysql_query("select * from adver");
$num=mysql_num_rows($query);
if($num!=0){
while($adv=mysql_fetch_array($query)){
echo '<a href="'.$adv['url'].'">'.$adv['name'].'</a><br/>';
}
}
$xx=$coladv-$num;
if($xx){
for($i=0;$i<$xx;$i++){
echo url('file','view=adv','Место для вашей рекламы');
}
}
?>



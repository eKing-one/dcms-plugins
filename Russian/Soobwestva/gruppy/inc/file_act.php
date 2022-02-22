<?php
if(isset($_GET['act']) && $_GET['act']=='delete' && isset($_GET['ok']) && $l!='/'){
mysql_query("DELETE FROM `gruppy_obmen_files` WHERE `id` = '$file_id[id]' AND `id_gruppy` = '$gruppy[id]'");
unlink(H.'sys/gruppy/obmen/files/'.$file_id['id'].'.dat');
header ("Location: ?s=$gruppy[id]&d=$dir_id[dir]");
exit;
}
?>
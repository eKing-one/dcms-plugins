<?php
function add_farm_event($msg)
{
global $user;
dbquery("INSERT INTO `farm_event` (`time`, `uid`, `msg`) VALUES 
('".time()."', '".$user['id']."', '".my_esc($msg)."')");
}
?>
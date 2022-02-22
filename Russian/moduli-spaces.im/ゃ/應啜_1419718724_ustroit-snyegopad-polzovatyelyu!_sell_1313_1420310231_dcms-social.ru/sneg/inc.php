<?
if (time() < $user['time_sneg']) {
$sss = $user['sneg_s'];
include_once H.'sneg/inc'.$sss.'.tpl';
}
?>
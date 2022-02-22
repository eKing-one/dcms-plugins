<?php
function koecurl($url, $post='', $mode=array()) {
$defaultmode = array('charset' => 'utf-8', 'ssl' => 1, 'cookie' => 1, 'headers' => 1, 'useragent' => 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.10.229 Version/11.61', 'referer' => 'google.com');  
foreach ($defaultmode as $k => $v) {
if (!isset($mode[$k]) ) {
$mode[$k] = $v;
}
}
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, $mode['headers']);
curl_setopt($ch, CURLOPT_REFERER, $mode['referer']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, $mode['useragent']);
curl_setopt($ch, CURLOPT_ENCODING, $mode['charset']);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 200);
if ($post) {
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
}
if ($mode['cookie']) {
curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
}
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
if ($mode['ssl']) {
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
}
$data = curl_exec($ch);
curl_close($ch);
return $data;
}
function check_page($str){
preg_match('#стр. \(([0-9]+)-([0-9]+)\)#si', $str, $c);
return intval($c[2]);
}
function pagenav($base_url, $start, $max_value, $num_per_page)
{
$neighbors = 4;
if ($start >= $max_value)
$start = max(0, (int)$max_value - (((int)$max_value % (int)$num_per_page) == 0 ? $num_per_page : ((int)$max_value % (int)$num_per_page)));
else
$start = max(0, (int)$start - ((int)$start % (int)$num_per_page));
$base_link = '<span class="page"><a href="' . strtr($base_url, array('%' => '%%')) . '%d' . '">%s</a></span>';
$out[] = $start == 0 ? '' : sprintf($base_link, $start / $num_per_page, '&lt;&lt;');
if ($start > $num_per_page * $neighbors)
$out[] = sprintf($base_link, 1, '1');
if ($start > $num_per_page * ($neighbors + 1))
$out[] = '<span style="font-weight: bold;">...</span>';
for ($nCont = $neighbors; $nCont >= 1; $nCont--)
if ($start >= $num_per_page * $nCont) {
$tmpStart = $start - $num_per_page * $nCont;
$out[] = sprintf($base_link, $tmpStart / $num_per_page + 1, $tmpStart / $num_per_page + 1);
}
$out[] = '<span class="str">' . ($start / $num_per_page + 1) . '</span>';
$tmpMaxPages = (int)(($max_value - 1) / $num_per_page) * $num_per_page;
for ($nCont = 1; $nCont <= $neighbors; $nCont++)
if ($start + $num_per_page * $nCont <= $tmpMaxPages) {
$tmpStart = $start + $num_per_page * $nCont;
$out[] = sprintf($base_link, $tmpStart / $num_per_page + 1, $tmpStart / $num_per_page + 1);
}
if ($start + $num_per_page * ($neighbors + 1) < $tmpMaxPages)
$out[] = '<span style="font-weight: bold;">...</span>';
if ($start + $num_per_page * $neighbors < $tmpMaxPages)
$out[] = sprintf($base_link, $tmpMaxPages / $num_per_page + 1, $tmpMaxPages / $num_per_page + 1);
if ($start + $num_per_page < $max_value) {
$display_page = ($start + $num_per_page) > $max_value ? $max_value : ($start / $num_per_page + 2);
$out[] = sprintf($base_link, $display_page, '&gt;&gt;');
}
return implode(' ', $out);
}
?>
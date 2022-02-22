<?
$q=mysql_query("SELECT * FROM `vip_premimum` WHERE `time` > '$time' ORDER BY rand() LIMIT 4");
if (mysql_num_rows($q)) {
	?>
	<div class="main"><?


echo "<center><a href='/user/money/vip.php'><img src='/style/vip/vg.png'></a></center>";


		?><table style="text-align: center;">
			<tr>
				<?
				while ($vip = mysql_fetch_assoc($q)) {
					?>
					<td>
						<?
						$ank=get_user($vip['id_user']);
echo "<a href='/info.php?id=$ank[id]'>\n";
						?><div style='position:relative;display:inline-block;'><?
  echo avatar($ank['id']);

$arr=mysql_fetch_array(mysql_query("SELECT * FROM `vip_premimum` WHERE `id_user` = '$ank[id]' AND `time` > '$time' LIMIT 1"));
  if ($arr['time'] >= $time)echo "<img style='position:absolute;bottom: -0px; right: -20%;'src='/style/vip/$arr[nomer].png'>";

						?>
						<br />
						<?
						
						?>
					</td>
					<?
				}
				?>
			</tr>
		</table>
	</div>
	<?
}
?>
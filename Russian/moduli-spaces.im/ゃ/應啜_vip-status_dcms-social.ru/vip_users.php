<?
$select_vips = mysql_query("SELECT * FROM `vip_users` ORDER BY rand() LIMIT 4");
if (mysql_num_rows($select_vips)) {
	?>
	<div class="main">
		<table style="text-align: center;">
			<tr>
				<?
				while ($vip_user = mysql_fetch_array($select_vips)) {
					?>
					<td>
						<?
						$us = get_user($vip_user['id_user']);
						echo avatar($us['id']);
						?>
						<br />
						<?
						echo "<a href='/info.php?id={$us['id']}'>{$us['nick']}</a>".online($us['id']);
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
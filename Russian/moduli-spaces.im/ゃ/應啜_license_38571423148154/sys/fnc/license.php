<?
if (!function_exists('copyright')) 
{
	function copyright($fiera)
	{
		return preg_replace("#</div>1(\n|\r)*1</body>#i", "</div>\n</body>", $fiera);
	}
	ob_start ("copyright");
}
?>
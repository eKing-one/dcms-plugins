<?
	function sklon_text($int, $expressions, $show=1) { // $expression = array('день', 'дня', 'дней');
		if (count($expressions) < 3)$expressions[2] = $expressions[1];
		$count = $int % 100;
		if ($count >= 5 && $count <= 20)$result = 2;
		else $count = $count % 10;
		if ($count == 1)$result = 0;
		elseif ($count >= 2 && $count <= 4)$result = 1;
		else $result = 2; 
		return ($show==1?$int.' ':NULL).$expressions[$result];
	}
?>
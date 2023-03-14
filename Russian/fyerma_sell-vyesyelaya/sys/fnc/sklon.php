<?PHP
function sklon_after_number($Number,$Slovo1,$Slovo2,$Slovo3,$Mode)
	{
		$Str=strval($Number);
		$chars = mb_strlen($Str, "utf8");
		$LastChar = mb_substr($Str, $chars - 1, 1, "utf8");
		if($LastChar=="1")
			{
				$Result=$Slovo1;
			};
		if(($LastChar=="2")or($LastChar=="3")or($LastChar=="4"))
			{
				$Result=$Slovo2;
			};
		if(($LastChar=="5")or($LastChar=="6")or($LastChar=="7")or($LastChar=="8")or($LastChar=="9")or($LastChar=="0"))
			{
				$Result=$Slovo3;
			};
		if($Mode==1)
			{
				$Ret=$Str." ".$Result;
			};
		if($Mode==2)
			{
				$Ret=$Result;
			};
		return $Ret;
	};
?>
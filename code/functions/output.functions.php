<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: output.functions.php
*/

	function pOut($content, $header = false)
	{
		global $pol;
		if(!isset($_REQUEST["ajaxOUT"]))
		{
			if(!$header)
				return $pol['page']['content'][] = $content;
			else
				return $pol['page']['header'][] = $content;
		}	
		else{
			echo $content;
			return true;
		}
	}
	
	function pButton($link, $icon, $text, $extra = '')
	{
		return "<a class='actionbutton $extra' href='".pUrl($link)."'><i class='fa fa-$icon' style='font-size: 12px!important;'></i> $text</i></a>";
	}

	// This function wraps tags around a keyword to highlight it.
	function pHighlight($needle, $haystack, $before, $after){

		return preg_replace('/' . preg_quote($needle) . '/i', $before . '$0' . $after, $haystack);

	}


 ?>
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: markdown.functions.php

	function pMarkDown($text, $block = true, $examples = true, $num = false){
	
	// We need to require the parsedown files, but only once.
	require_once pFromRoot('library/assets/php/vendors/parsedown.require.php');
	require_once pFromRoot('library/assets/php/vendors/parsedown_extra.require.php');


	// Parsing (@) to numbred examples:
	if($examples){
		$exampleCount = 0;
		$text = preg_replace_callback('/[\(](@)[\)]/', function($matches){
			global $exampleCount;
			$exampleCount++;
  			return "(" . $exampleCount . ")";
		}, $text);
	}

	$parse = new ParsedownExtra;

	return "<div class='markdown-body ".($num ? 'num' : '')." ".($block ? 'block' : 'inline-block')."'>".$parse->text($text)."</div>";
}
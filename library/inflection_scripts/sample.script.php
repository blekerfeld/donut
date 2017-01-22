<?php

// Donut sample inflection script

// A script has a function




function pExecuteScript_sample($inflect_this, $word, $mode, $submode, $number){

	// If there is an -e, it is not needed
	if(pEndsWith($inflect_this, 'e'))
		$inflect_this = pStr($inflect_this)->replaceSuffix('e', '');

	return $inflect_this;

}





?>
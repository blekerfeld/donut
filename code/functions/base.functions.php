<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: base.functions.php

	// Donut base output functionality
	function pOut($content, $header = false, $outer = false)
	{
		global $donut;
		if($header)
			return $donut['page']['header'][] = $content;
		elseif($outer)
			return $donut['page']['outofinner'] .= $content;
		else
			return $donut['page']['content'][] = $content;
	}

	function pConsole($x){
		return pOut("<span class='line'>$x</span>");
	}

	function pVarDump($var){
		ob_start();
		var_dump($var);
		return ob_get_clean();
	}
	
	// This function wraps tags around a keyword to highlight it.
	function pHighlight($needle, $haystack, $before, $after){

		return preg_replace('/' . preg_quote($needle) . '/i', $before . '$0' . $after, $haystack);

	}

	// This functions parses the data according to norms
		function pDate($time_stamp, $show_time = true, $show_seconds = false, $timezone_when_possible = false) {
		
		// Everthing is added to this one
		$e = "";

		// Is it today? 
		if(date('d/m/Y') == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_TODAY;
		}

		// ... yesterday maybe?
		elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")-1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_YESTERDAY;
		}

		// Tomorrow then?
		elseif(date("d/m/Y",mktime(0,0,0,date("m") ,date("d")+1,date("Y"))) == date('d/m/Y', strtotime($time_stamp)))
		{
			$e .= DATE_TOMORROW;
		}

		// This week? We just show the name of the day then
		elseif(date("W") == date("W", strtotime($time_stamp)) and date("Y") == date("Y", strtotime($time_stamp)))
		{
			$e .= constant("DATE_DAY_".date("w", strtotime($time_stamp)));
		}

		// If it is this year, we only have to show the month and the day
		elseif(date("Y") == date("Y", strtotime($time_stamp)))
		{
			if(DATE_DAYNUMBERAFTER == false)
				$e .= date("j", strtotime($time_stamp))." ";
			if(DATE_USESHORTMONTHS == true)
				$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
			if(DATE_USESHORTMONTHS == false)
				$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
			if(DATE_DAYNUMBERAFTER == true)
				$e .= " ".date("j", strtotime($time_stamp));
			if(DATE_ADDSUFFIX == true)
				$e .= date("S", strtotime($time_stamp));
		}

		// This goes way back to another year, that's when we are now.
		else
		{
			
			if(DATE_DAYNUMBERAFTER == false)
				$e .= date("j", strtotime($time_stamp))." ";
			if(DATE_USESHORTMONTHS == true)
				$e .= constant("DATE_MONTH_SHORT_".date("n", strtotime($time_stamp)));
			if(DATE_USESHORTMONTHS == false)
				$e .= constant("DATE_MONTH_".date("n", strtotime($time_stamp)));
			if(DATE_DAYNUMBERAFTER == true)
				$e .= " ".date("j", strtotime($time_stamp));
			if(DATE_ADDSUFFIX == true)
				$e .= date("S", strtotime($time_stamp));
			if(DATE_SEPERATOR != "")
				$e .= DATE_SEPERATOR;
			$e .= date("Y", strtotime($time_stamp));
		}

		// Need to show time too?
		if($show_time == true)
		{
			$e .= DATE_PREPOSITION_TIME;
			if(DATE_USE12HOUR == true)
				$e .= date("h", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "")." ".date("A", strtotime($time_stamp));
			else
				$e .= date("H", strtotime($time_stamp)).":".date("i", strtotime($time_stamp)).(($show_seconds == true) ? (":".date("s", strtotime($time_stamp))) : "");
			if(DATE_ADDTIMEZONEINDICATOR == true and $timezone_when_possible== true)
				$e .= " ".date("T", strtotime($time_stamp));
		}

		return $e;
	}

	// for easy plurals
	function pPlural($amount, $singular = '', $plural = 's' ) {
	    if ( $amount === 1 ) {
	        return $amount." ".$singular;
	    }
	    return $amount." ".$plural;
	}

	function pNoticeBox($icon, $message, $type='notice', $id=''){
		return '<div class="'.$type.'" id="'.$id.'"><i class="fa '.$icon.' fa-10"></i> '.$message.'</div>';
	}

	function pMempty()
	{
	    foreach(func_get_args() as $arg)
	        if(empty($arg))
	            continue;
	        else
	            return false;
	    return true;
	}


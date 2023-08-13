<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: inflection.class.php

// an inflection

class pInflection{

	protected $_addBefore, $_addAfter, $_stem = null, $_checksB = array(), $_checksA = array(), $_deniedLeft = 0, $_deniedRight = 0, $_maxCountLeft, $_maxCountRight;


	public function __construct($pattern){

		// Splitting up the the prefix, stem and suffix statements
		$pattern = explode('[', $pattern);
		$pattern2 = @explode(']', $pattern[1]);
		$this->_addBefore = @explode(';', $pattern[0]);
		$this->_formStem = @explode(';', $pattern2[0]);
		$this->_addAfter = @explode(';', $pattern2[1]);
		// Calculating the maximal count for the &ELSE operator
		$this->_maxCountRight = count($this->_addAfter) - 1;
		$this->_maxCountLeft = count($this->_addBefore) - 1;
	}

	// The thing that does all the work
	public function inflect($word){
		// Inital stem 
		$stem = $word; 
		// If we have no specified stem, we need to form one
		if(($this->_stem == null))
			foreach($this->_formStem as $action){
				if(p::StartsWith($action, '+^'))
					$stem = substr($action, 2) . $stem;
				elseif(p::StartsWith($action, '+'))
					$stem = $stem . '+' . substr($action, 1);
				elseif(p::StartsWith($action, '-^') AND p::StartsWith($stem, substr($action, 2)))
					$stem = substr($stem, strlen(substr($action, 2)));
				elseif(p::StartsWith($action, '-')){
					$stem = p::Str($stem)->replaceSuffix(substr($action, 1), '');
					$stem = $stem->__toString();
				}
				// Base modification
				elseif(p::StartsWith($action, '&')){
					$value = explode('=>', $action)[1];
					$name = explode('=>', $action)[0];
					$stem = str_replace($name, $value, $stem);
				}
			}
		else
			// We have a specified stem
			$stem = $this->_stem;

		foreach($this->_addBefore as $action)
			if($action != '')
				$stem = $this->addBefore($action, $stem);

		foreach($this->_addAfter as $action)
			if($action != '')
				$stem = $this->addAfter($action, $stem);

		$this->_deniedLeft = 0;
		$this->_deniedRight = 0;

		return str_replace(':-:', '', $stem);
	}

	protected function addBefore($what, $string){

		$whatExploded = explode('?', $what);
		$check = false;
		$prefix = $whatExploded[0];
		unset($whatExploded[0]);
		$checks = array();

		// Let's check if there are negative overrides
		if(isset($whatExploded[1])){
			foreach($whatExploded as $negOver){
				$check = $this->look($string, $negOver, $this->_deniedLeft, $this->_maxCountLeft);
				$checks[] = $check;
			}
		}

		if(in_array(false, $checks)){
			$this->_deniedLeft = $this->_deniedLeft + 1; 
			return $string;
		}
			
		// Temporary morhpeme boundary included
		return $prefix . '+' . $string.':-:';
	}

	protected function GroupControl($negOver, $start, $string, $neg = false){
		$exploded = explode('.', $negOver);
		if($start)
			$regex_outer = '/^';
		else
			$regex_outer = '/';

		$regex_inner = '';

		$contexts = (new pTwolc)->parseContext($negOver, (!$start));
		$regex_inner .= implode('', $contexts);

		if($regex_inner == '')
			return false;

		if(!$start)
			$regex = $regex_outer . $regex_inner . '$/u';
		else
			$regex = $regex_outer . $regex_inner . '/u';

		if($neg)
			return (preg_match($regex, $string) == 1);
		else
			return (preg_match($regex, $string) == 0);

	}

	protected function look($string, $negOver, $count, $maxCount){


		if($negOver == '&ELSE'){

			if($maxCount == $count + 1)
				return false;
			else
				return true;
		}
			

		elseif(p::StartsWith($negOver, '!^'))
			return $this->GroupControl(substr($negOver, 2), true, $string, false);
		elseif(p::StartsWith($negOver, '!$'))
			return $this->GroupControl(substr($negOver, 2), false, $string, false);
		elseif(p::StartsWith($negOver, '^'))
			return $this->GroupControl(substr($negOver, 1), true, $string, true);
		elseif(p::StartsWith($negOver, '$'))
			return $this->GroupControl(substr($negOver, 1), false, $string, true);
		else
			return false;
	}

	protected function addAfter($what, $string){

		$whatExploded = explode('?', $what);
		$check = false;
		$suffix = $whatExploded[0];
		unset($whatExploded[0]);
		$checks = array();

		// Let's check if there are negative overrides
		if(isset($whatExploded[1])){
			foreach($whatExploded as $negOver){
				$check = $this->look($string, $negOver, $this->_deniedRight, $this->_maxCountRight);
				$checks[] = $check;
			}
		}

		if(in_array(false, $checks)){
			$this->_deniedRight = $this->_deniedRight + 1; 
			return $string;
		}
					
		return $string.'+'.$suffix.':-:';

	}

	public function setStem($stem){
		$this->_stem = $stem;
	}

	public function describeCondition($condition){
		$output = '';
		if(p::StartsWith($condition, '!^'))
			$output .= "- ".IND_STEM_NOT_START." '".substr($condition, 2)."' <br/ >";
		elseif(p::StartsWith($condition, '!$'))
			$output .= "- ".IND_STEM_NOT_END." '".substr($condition, 2)."' <br/ >";
		elseif(p::StartsWith($condition, '^'))
			$output .= "- ".IND_STEM_START." '".substr($condition, 1)."' <br/ >";
		elseif(p::StartsWith($condition, '$'))
			$output .= "- ".IND_STEM_END." '".substr($condition, 1)."' <br/ >";
		elseif($condition == '&ELSE')
			$output .= "- ".IND_ELSE." <br/ >";
		return $output;
	}

	public function describeRule(){
		$output =  "<table class='describe'><th><tr class='title'><td>
		<strong>".IND_SF."</strong></td></tr></th>";

		$overAllVM = array();

		foreach($this->_formStem as $key => $action){
			if(p::StartsWith($action, '+^'))
				$output .= "<tr><td>'".substr($action, 2)."' ".IND_SFRONTPLUS."</td></tr>";
			elseif(p::StartsWith($action, '+'))
				$output .= "<tr><td>'".substr($action, 1)."' ".IND_SENDPLUS."</td></tr>";
			elseif(p::StartsWith($action, '-^') AND p::StartsWith($stem, substr($action, 2)))
				$output .= "<tr><td>'".substr($action, 2)."' ".IND_SFRONTMIN."</td></tr>";
			elseif(p::StartsWith($action, '-'))
				$output .= "<tr><td>'".substr($action, 1)."' ".IND_SENDMIN."</td></tr>";
			// Base modification
			elseif(p::StartsWith($action, '&')){
				@$value = explode('=>', $action)[1];
				@$name = explode('=>', $action)[0];
				if(p::StartsWith($value, '&'))
					$overAllVM[] = substr($value, 1);
				$output .= "<tr><td>".sprintf(IND_VARIABLE, p::Markdown("`".$name."`", false))." ".p::Markdown("`".$value."`", false)."</td></tr>";
			}
			elseif($key == 0)
				$output .= "<tr><td>".IND_SNOCHANGE."</td></tr>";
		}
	
		if($this->_addBefore[0] != ''){
			$output .= "<tr class='title'><td><strong>".IND_PREFIXES."</strong></td></tr>";
			foreach($this->_addBefore as $before){
				$explode = explode('?', $before);
				if($explode[0] == '')
					continue;
				$output .= "<tr><td>".p::Markdown(sprintf(IND_PX_ADDS, "`".$explode[0]."`"), false)."<br />	";

				preg_match("/(?<=&)(.*)/", $explode[0], $vM);
				$variableMatches = array_unique($vM);
				unset($explode[0]);
				$cnt = 0;
				foreach ($explode as $condition){
					$cnt ++;
					$output .= $this->describeCondition($condition);
				}
				if($cnt == 0)
					$output .= IND_ALWAYS;
				$output .= "</td></tr>";
				foreach($variableMatches as $m)
					$overAllVM[] = $m;
			}
		}


		if($this->_addAfter[0] != ''){
			$output .= "<tr class='title'><td><strong>".IND_SUFFIXES."</strong></td></tr>";
			foreach($this->_addAfter as $before){
				$explode = explode('?', $before);
				if($explode[0] == '')
					continue;
				$output .= "<tr><td>".p::Markdown(sprintf(IND_SX_ADDS, "`".$explode[0]."`"), false)."<br />	";
				preg_match("/(?<=&)(.*)/", $explode[0], $vM);
				$variableMatches = array_unique($vM);
				unset($explode[0]);
				$cnt = 0;
				foreach ($explode as $condition){
					$cnt ++;
					$output .= $this->describeCondition($condition);
				}
				if($cnt == 0)
					$output .= "- ".IND_ALWAYS."<br />";
				$output .= "</td></tr>";
				foreach($variableMatches as $m)
					$overAllVM[] = $m;
			}
		}

		$overAllVM = array_unique($overAllVM);
		if(count($overAllVM) != '0'){
			$output .= "<tr class='title'><td><strong>".IND_VARIABLE_GENERATED."</strong></td></tr>";
			$output .= "<tr><td>";
			$implodeThis = array();
			foreach($overAllVM as $m){
				$implodeThis[] = '`&'.$m.'`';
			}
			$output .= p::Markdown(implode(', ', $implodeThis), false);
			$output .= "</td></tr>";
		}

		return $output."</table>";
	}

}

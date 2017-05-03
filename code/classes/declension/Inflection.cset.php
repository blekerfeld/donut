<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: inflection.cset.php

// an inflection

class pInflection{

	protected $_addBefore, $_addAfter, $_stem = null, $_checksB = array(), $_checksA = array(), $_deniedLeft = 0, $_deniedRight = 0, $_maxCountLeft, $_maxCountRight;


	public function __construct($pattern){

		//<ge;>[!en;]<t;>
		// First split
		$pattern = explode('[', $pattern);
		$pattern2 = explode(']', $pattern[1]);

		$this->_addBefore = explode(';', $pattern[0]);
		$this->_formStem = explode(';', $pattern2[0]);
		$this->_addAfter = explode(';', $pattern2[1]);
		$this->_maxCountRight = count($this->_addAfter) - 1;
		$this->_maxCountLeft = count($this->_addBefore) - 1;

	}

	public function inflect($word){

		$stem = $word; 

		if(($this->_stem == null))
			foreach($this->_formStem as $action){
				if(pStartsWith($action, '+^'))
					$stem = substr($action, 2) . $stem;
				elseif(pStartsWith($action, '+'))
					$stem = $stem . '++' . substr($action, 1);
				elseif(pStartsWith($action, '-^') AND pStartsWith($stem, substr($action, 2)))
					$stem = substr($stem, strlen(substr($action, 2)));
				elseif(pStartsWith($action, '-')){
					$stem = pStr($stem)->replaceSuffix(substr($action, 1), '');
					$stem = $stem->__toString();
				}
				// Base modification
				elseif(pStartsWith($action, '&')){
					$value = explode('=>', $action)[1];
					$name = explode('=>', $action)[0];
					$stem = str_replace($name, $value, $stem);
				}
			}
		else
			$stem = $this->_stem;


		$word = $stem;

		foreach($this->_addBefore as $action)
			if($action != '')
				$word = $this->addBefore($action, $word);

		foreach($this->_addAfter as $action)
			if($action != '')
				$word = $this->addAfter($action, $word);

		$this->_deniedLeft = 0;
		$this->_deniedRight = 0;

		return str_replace(':-:', '', $word);
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
				
		return $prefix . '+' . $string.':-:';
	}

	protected function GroupControl($negOver, $start, $string, $neg = false){
		$exploded = explode('.', $negOver);
		if($start)
			$regex_outer = '/^';
		else
			$regex_outer = '/';

		$regex_inner = '';

		$contexts = pTwolc::parseContext($negOver, (!$start));
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
			

		elseif(pStartsWith($negOver, '!^'))
			return $this->GroupControl(substr($negOver, 2), true, $string, false);
		elseif(pStartsWith($negOver, '!$'))
			return $this->GroupControl(substr($negOver, 2), false, $string, false);
		elseif(pStartsWith($negOver, '^'))
			return $this->GroupControl(substr($negOver, 1), true, $string, true);
		elseif(pStartsWith($negOver, '$'))
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

}

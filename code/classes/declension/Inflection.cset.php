<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: inflection.cset.php

// an inflection

class pInflection{

	protected $_addBefore, $_addAfter, $_stem = null;


	public function __construct($pattern){

		//<ge;>[!en;]<t;>
		// First split
		$pattern = explode('[', $pattern);
		$pattern2 = explode(']', $pattern[1]);

		$this->_addBefore = explode(';', $pattern[0]);
		$this->_formStem = explode(';', $pattern2[0]);
		$this->_addAfter = explode(';', $pattern2[1]);

	}

	public function inflect($word){

		$stem = $word; 

		if(($this->_stem == null))
			foreach($this->_formStem as $action){

				// $exploded = explode('-', $action);
				// $action = $exploded[0];
				// unset($exploded[0]);	
				// $check = false;		

				// foreach ($exploded as $negOver) {
				// 	$check = $this->look($negOver, $stem);
				// 	if($check == true)
				// 		break;
				// }

				// if($check){
				// 	$stem = $stem;
				// 	break;
				// }

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

		return $word;
	}

	protected function addBefore($what, $string){

		$whatExploded = explode('-', $what);
		$prefix = $whatExploded[0];
		unset($whatExploded[0]);
		$check = false;

		// Let's check if there are negative overrides
		if(count($whatExploded) > 1){
			foreach($whatExploded as $negOver){
				$check = $this->look($string, $negOver);
				if($check == true)
					break;
			}
		}

		if($check)
			return $string;

		return $prefix.'{'.$string;

	}

	protected function look($string, $negOver){

		$check = 0;

		// Negative mode
		if(pStartsWith($negOver, '!'))
			if(pStartsWith(substr($negOver, 1), '^'))
				if(pStartsWith($string, substr($negOver, 2)))
					$check = true;
			elseif(pStartsWith(substr($negOver, 1), '$'))
				if(pEndsWith($string, substr($negOver, 2)))
					$check = true;

		else
			if(pStartsWith($negOver, '^'))
				if(pStartsWith($string, substr($negOver, 2)))
					$check = false;
			elseif(pStartsWith($negOver, '$'))
				if(pEndsWith($string, substr($negOver, 2)))
					$check = false;

		return $check;

	}

	protected function addAfter($what, $string){

		$whatExploded = explode('-', $what);
		$suffix = $whatExploded[0];
		unset($whatExploded[0]);
		$check = false;

		// Let's check if there are negative overrides
		if(count($whatExploded) > 1){
			foreach($whatExploded as $negOver){
				$check = $this->look($string, $negOver);
				if($check == true)
					break;
			}
		}

		if($check)
			return $string;

		return $string.'+'.$suffix;

	}

	public function setStem($stem){
		$this->_stem = $stem;
	}

}

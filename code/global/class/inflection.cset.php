<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: stringmachine.cset.php

// an inflection

class pInflection{

	protected $_addBefore, $_addAfter;


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

		// Forming the stem
		foreach($this->_formStem as $action){


			if(pStartsWith($action, '+^'))
				$stem = substr($action, 2) . $stem;
			elseif(pStartsWith($action, '+'))
				$stem = $stem . '++' . substr($action, 1);
			elseif(pStartsWith($action, '-^') AND pStartsWith($stem, substr($action, 2)))
				$stem = substr($stem, strlen(substr($action, 2)));
			elseif(pStartsWith($action, '-'))
				$stem = pStr($stem)->replaceSuffix(substr($action, 1), '');

		}

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

		$whatExploded = explode('-!', $what);
		$prefix = $whatExploded[0];

		unset($whatExploded[0]);

		$check = false;

		// Let's check if there are negative overrides
		if(count($whatExploded) > 1){
			foreach($whatExploded as $key => $negOver){
				if(pStartsWith($string, $negOver))
					$check = true;
			}
		}

		if($check)
			return $string;

		return $prefix.'{'.$string;

	}

	protected function addAfter($what, $string){

		// Let's check if there are negative overrides
		

		return $string.'+'.$what;

	}

}
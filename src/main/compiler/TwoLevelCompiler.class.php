<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: TwoLevelCompiler.class.php

// the Two Level Compiler

class pTwolc{

	protected $_rules, $_parsedRules, $_leftContexts, $_rightContexts, $_pending;

	public function __construct($rules = []){
		$this->_rules = $rules;
	}

	public function feed($string){

		// Let's create a string object for the Twolc to use
		$string = new pTwolcString($string);

		if($this->_pending != null)
			foreach($this->_pending as $change)
				$string->change($change['pattern'], $change['replace'], $change['middle']);

		return $string;
	}

	public function compile(){

		foreach($this->_rules as $rule)
			$this->parseRule($rule);

		if(sizeof($this->_parsedRules) > 0)
			foreach($this->_parsedRules as $rule)
				$this->pendChange($rule);

		return $this;
	}

	protected function parseRule($rule){
		// First we need to split the two contexts from each other
		$splitRule1 = explode('=', $rule);
		$replace = $splitRule1[1];
		$splitRule2 = explode('<', $splitRule1[0]);
		$splitRule3 = explode('>', $splitRule2[1]);
		$leftPart = $splitRule2[0];		// Left context
		$middlePart = $splitRule3[0];	// Search context
		$rightPart = $splitRule3[1];		// Right context
		$parsedRule = array(
			'left' => $this->parseContext($leftPart),
			'middle' => "(".$this->parseContext($middlePart, false, true).")",
			'right' => $this->parseContext($rightPart, true),
			'replace' => $replace,
			'original_middle' => $middlePart,
		);
		//var_dump($splitRule2);
		return $this->_parsedRules[] = $parsedRule;
	}

	protected function parseReplace($replacePattern){
		// Might be changed a little
		$replaced = $replacePattern;
		$replaced = str_replace(array(' ', '//-'), array('', ' '), $replaced);
		return $replaced;
	} 	

	protected function pendChange($rule){

		// Starting our regular expresion
		$regex = '/';

		if(!empty($rule['left']))
			$regex .= "(?<=".implode('',$rule['left']).")";

		$regex .= $rule['middle'];

		if(!empty($rule['right']))
			$regex .= "(?=".implode('',$rule['right']).")";

		// UTF-8 support enabled
		$regex .= '/u';

		$this->_pending[] = array('pattern' => $regex, 'replace' => $this->parseReplace($rule['replace']), 'middle' => $rule['original_middle']);
		
		//var_dump($regex);

	}


	public function parseContext($context, $right = false, $middle = false, $switchRecursive = false){
		$parsedContext = array();
		$splitContext = explode(' ', $context);
		// CON.CON.CON (three consonants)
		// -3.-2.-1

		if(!$switchRecursive AND $middle){
			$separateContexts = explode('OR', $context);
			if(count($separateContexts) == 1 OR count($separateContexts) == 0)
				return $this->parseContext($context, false, true, true);
			foreach ($separateContexts as $contextPlus)
				$parsedContext[] = $this->parseContext($contextPlus, false, true, true);
			return implode('|', $parsedContext);
		}

		if($middle)
			$count = 0;
		elseif($right)
			$count = 1;
		else
			$count = count($splitContext);

		$captureCount = 1;

		foreach($splitContext as $char){
			// Escaping
			if($char == '//-')
				$parsedContext[$count] = '[\040]{1}';
			elseif($char == '///')
				$parsedContext[$count] = '[/]{1}';
			elseif($char == '//>')
				$parsedContext[$count] = '[>]{1}';
			elseif($char == '//<')
				$parsedContext[$count] = '[<]{1}';
			elseif($char == '//:')
				$parsedContext[$count] = '[:]{1}';
			elseif($char == '//:')
				$parsedContext[$count] = '[:]{1}';

			// String / word boundaries
			elseif($char == '/:'){
				$parsedContext[$count] = '\A'; 
			}elseif($char == ':/'){
				$parsedContext[$count] = '\z'; 
			}elseif($char == '::'){
				$parsedContext[$count] = '\b'; 
			}

			elseif(p::StartsWith($char, '|') AND p::EndsWith($char, '|') AND is_numeric(substr($char, 1, -1))){
				$parsedContext[$count] = '\\'.substr($char, 1, -1);
				$count++;
				continue;
			}
			
			// Only works in (cap)_%_
			elseif($char == '%'){
				$parsedContext[$count] = '\\'.$captureCount;
				continue;
			}
			elseif($char == ':'){
				$parsedContext[$count] = '[:]{1}';
				continue;
			}
			// Variables
			elseif (p::StartsWith($char, '&')) {
				$char = substr($char, 1);
				$explode = explode(',', $char);
				$output = '(?:';
				$matches = array();
				foreach (explode(',', $char) as $var)
					$matches[] = $var;
				$parsedContext[$count] = "[&]{1}".$output.implode('|', $matches).")";
			}
			// literal occurances 'at'
			elseif(p::StartsWith($char, '\'') AND p::EndsWith($char, '\'')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[".$char."]{1}";
			}
			// one of following [a,b,c]
			elseif(p::StartsWith($char, '[') AND p::EndsWith($char, ']')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[".implode('', explode(',', $char))."]{1}";
			}
			// not one of the following
			elseif(p::StartsWith($char, '[^') AND p::EndsWith($char, ']')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[^".implode('', explode(',', $char))."]{1}";
			}
			// Not con, vow 
			elseif(strlen($char) == 4 AND p::StartsWith($char, '^') AND mb_strtoupper(substr($char, 1), 'utf-8') == substr($char, 1) ){
				$parsedContext[$count] = "[^".pAlphabet::getGroupsAsString(substr($char, 1))."]{1}";
			}
			// Con or vow
			elseif(strlen($char) == 3 AND mb_strtoupper($char, 'utf-8') == $char)
				if(pAlphabet::getGroupsAsString($char) != '')
					$parsedContext[$count] = "[".pAlphabet::getGroupsAsString($char)."]{1}";
				else
					$parsedContext[$count] = preg_quote($char);
			elseif($char == '+' OR $char == '#')
				$parsedContext[$count] = '['.$char.']{1}';	
			elseif(strlen($char) == 1){
				$parsedContext[$count] = "[".$char."]{1}";
			}
			elseif(p::StartsWith($char, '(') AND p::EndsWith($char, ')')){
				$parsedContext[$count] = '('.$this->parseContext(substr($char, 1, -1))[1].')';
				$captureCount++;
			}
			else{
				$parsedContext[$count] = $char;
			}

			if($right OR $middle)
				$count++;
			else
				$count--;
		}


		if($middle)
			return implode('', $parsedContext);

		//var_dump($parsedContext);
		return $parsedContext;
	}


}
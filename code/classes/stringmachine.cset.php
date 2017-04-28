<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: stringmachine.cset.php

// the Two Level Compiler

class pTwolc{

	protected $_rules, $_parsedRules, $_leftContexts, $_rightContexts, $_pending;

	public function __construct($rules){
		$this->_rules = $rules;
	}

	public function feed($string){

		// Let's create a string object
		$string = new pTwolcString($string);

		if($this->_pending != null)
			foreach($this->_pending as $change)
				$string->change($change['pattern'], $change['replace']);

		return $string;

	}

	public function compile(){
		foreach($this->_rules as $rule)
			$this->parseRule($rule);
		foreach($this->_parsedRules as $rule)
			$this->pendChange($rule);
	}


	// Rule looks like this: a_CON_b=>^^ (^ = hitter)
	protected function parseRule($rule){
		// First we need to split the two contexts from eachother
		$splitRule1 = explode('=>', $rule);
		$replace = $splitRule1[1];
		$splitRule = explode('_', $splitRule1[0]);
		$leftPart = $splitRule[0];		// Left context
		$middlePart = $splitRule[1];	// Search context
		$rightPart = $splitRule[2];		// Right context
		$parsedRule = array(
			'left' => $this->parseContext($leftPart),
			'middle' => "(".$this->parseContext($middlePart, false, true).")",
			'right' => $this->parseContext($rightPart, true),
			'replace' => $replace,
		);
		return $this->_parsedRules[] = $parsedRule;
	}

	protected function parseReplace($replacePattern){
		// Might be changed a little
		$replaced = $replacePattern;
		$replaced = str_replace('%', '$1', $replaced);
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

		var_dump($regex);

		$this->_pending[] = array('pattern' => $regex, 'replace' => $this->parseReplace($rule['replace']));
		
	}


	protected function parseContext($context, $right = false, $middle = false){
		$parsedContext = array();
		$splitContext = explode('.', $context);
		// CON.CON.CON (three consonants)
		// -3.-2.-1.X

		if($middle)
			$count = 0;
		elseif($right)
			$count = 1;
		else
			$count = count($splitContext);


		foreach($splitContext as $char){


			if($char == '<:'){
				$parsedContext[$count] = '\A'; 
			}elseif($char == ':>'){
				$parsedContext[] = '\z'; 
			}
			elseif($char == '%'){
				$parseContext[] = '$1';
			}

			// Variables
			elseif (pStartsWith($char, '&')) {
				$char = substr($char, 1);
				$explode = explode(',', $char);
				$output = '(?:';
				$matches = array();
				foreach (explode(',', $char) as $var)
					$matches[] = $var;
				$parsedContext[$count] = "[&]{1}".$output.implode('|', $matches).")";
			}
			// literal occurances 'at'
			elseif(pStartsWith($char, '\'') AND pEndsWith($char, '\'')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[".$char."]{1}";
			}
			// one of follolwing [a,b,c]
			elseif(pStartsWith($char, '[') AND pEndsWith($char, ']')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[".implode('', explode(',', $char))."]{1}";
			}
			elseif(pStartsWith($char, '[^') AND pEndsWith($char, ']')){
				$char = substr($char, 1, -1);
				$parsedContext[$count] = "[^".implode('', explode(',', $char))."]{1}";
			}
			elseif(strlen($char) == 4 AND pStartsWith($char, '^') ){
				$parsedContext[$count] = "[^".pAlphabet::getGroupsAsString($char)."]{1}";
			}
			elseif(strlen($char) == 3)
				$parsedContext[$count] = "[".pAlphabet::getGroupsAsString($char)."]{1}";
			elseif($char == '+' OR $char == '#')
				$parsedContext[$count] = '[+]{1}';	
			elseif(strlen($char) == 1){
				$parsedContext[$count] = "[".$char."]{1}";
			}

			if($right OR $middle)
				$count++;
			else
				$count--;
		}

		if($middle)
			return implode('', $parsedContext);
		return $parsedContext;
	}


}

class pTwolcString{

	protected $_string;

	public function __construct($string){
		$this->_string = $string;
	}

	public function __toString(){
		return $this->_string;
	}

	public function change($search, $replace){
		$this->_string = preg_replace($search, $replace, $this->_string);
	}

	public function toSurface(){
		//return $this->_string;
		return strtolower(str_replace(array('0', '+', '{', '::', '&', '#'), '', $this->_string));
	}

}

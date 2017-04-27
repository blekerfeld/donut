<?php
	
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: language.cset.php

class pAlphabet{

	private static $dataObject, $dataObjectGroups, $_graphemes, $order, $_init = false, $_groups = array();


	// This will print a freaking alphabet bar via pOut(new pAlphabet);
	public function __toString(){
		$output = '<div class="alphabetbar">';
		foreach (self::$dataObject->data()->fetchAll() as $grapheme)
			$output .= "<a href='".pUrl('?list/alphabet/'.$grapheme['id'].'/'.$grapheme['grapheme'])."'><span class='native'>".$grapheme['grapheme']."</span></a>";
		return $output."</div>";
	}

	public function __construct(){
		if(self::$_init)
			return false;
		else
			$this->init();
	}

	// This makes that the alphabet can be called like this: pAlphabet::init()->sort(array);
	public function init(){
		// If the alphabet is already initialized we do not need to do it again
		if(self::$_init == true)
			return new self;

		// Let's get our alphabet
		self::$dataObject = new pDataObject('graphemes');
		self::$dataObjectGroups = new pDataObject('graphemes_groups');
		self::$dataObject->setCondition(" WHERE in_alphabet = 1");
		self::$dataObject->setOrder(" sorter ASC ");
		self::$dataObject->getObjects();

		// Let's create the order
		foreach(self::$dataObject->data()->fetchAll() as $grapheme)
			self::$order[] = $grapheme['grapheme'];

		// Remember this...
		self::$_init = true;

		return new self;
	}

	private function letterToNum($data) {
	    if($data == '' or $data == NULL)
	        return false;

	    $alpha_flip = array_flip(self::$order);
	    
	    $return_value = -1;

	    $length = mb_strlen($data, "UTF-8");

	    if(in_array($data, self::$order))
	      $return_value = array_search($data, self::$order);
	  
	    return $return_value;
	}

	// Run some tests
	private function isConsecutive($array) {
	  	if(empty($array))
	    	return false;
	    else
	    	return ((int)max($array)-(int)min($array) == (count($array)-1));
	}


	// Accepts an array of lemma's or translation's
	public function sort($lemmas){

		// The alphabet needs to be loaded first
		if(!self::$_init)
			self::init();

		$words_array = array();
		$count = 0;
		foreach($lemmas as $word){

		  // We have to set the original word
		  if(!($original_word = @$word->word))
		  	$original_word = $word->translation;

		  // Before we split the words, we need to loop through the array to sort it

		  $original_alphabet = self::$order;

		  $filter = array();
		  usort($original_alphabet, function($a, $b) {
		    global $filter;
		    if(mb_strlen($a, "UTF-8") != 1)
		      $filter[] = $a;
		    return mb_strlen($b, "UTF-8")  - mb_strlen($a, "UTF-8");
		  });

		  if(!($word_work_with = str_split_unicode($word->word)))
		  	$word_work_with = str_split_unicode($word->native);

		  foreach($filter as $substr){

		    $chars = str_split_unicode($substr);
		    $poss = array();
		    foreach($chars as $char){
		      $extra_pos = strpos($original_word, $char);
		      if(!($extra_pos === false))
		        $poss[] = $extra_pos;
		    }

		    if(self::isConsecutive($poss) and (count($poss) == mb_strlen($substr, "UTF-8")))
		     foreach($poss as $restore_char_key => $restore_char){
		        if($restore_char_key === 0)
		          $word_work_with[$restore_char] = $substr;
		        else
		          $word_work_with[$restore_char] = NULL;
		     }
		  }

		$max = 255;
		$count = 0;
		$big_number = '';

		foreach($word_work_with as $letter){
		  
			$number = self::letterToNum($letter) + 1;
		 
			if($number < 10)
				$number_string = '0'.((string)$number).'9 ';
			elseif($number < 100)
				$number_string = ((string)$number).'9 ';
			else
				$number_string = "999 ";

			$big_number .= $number_string;

			$count++;

		}

		$index = str_pad($big_number, ($max - $count)*3,"000 ").$count.$word['id'];

		$words_array[$index] = array($index, $word);
		
		$big_number = '';

		}

		ksort($words_array);

		return $words_array;
	}


	public function getGroups($groupstring){

		if(isset(self::$_groups[$groupstring]))
			return self::$_groups[$groupstring];

		// The alphabet needs to be loaded first
		if(!self::$_init)
			self::init();

		$output = array();
		
		self::$dataObjectGroups->setCondition(" WHERE groupstring = '".$groupstring."' ");
		
		foreach(self::$dataObjectGroups->getObjects()->fetchAll() as $grapheme)
			$output[] = $grapheme['grapheme'];
		
		self::$_groups[$groupstring]  = $output;

		return $output;
	}

	public function getGroupsAsString($groupstring){

		// The alphabet needs to be loaded first
		if(!self::$_init)
			self::init();

		// First we need to get the group
		$group = self::getGroups($groupstring);

		$string = '';

		foreach($group as $letter)
			$string .= $letter;

		return $string;

	}

}
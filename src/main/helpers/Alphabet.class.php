<?php
	// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: language.class.php

class pAlphabet{

	private static $dataModel, $dataModelGroups, $_graphemes, $order, $_init = false, $_groups = array();


	// This will print a freaking alphabet bar via p::Out(new pAlphabet);
	public function __toString(){
		$output = '<div class="alphabetbar">';
		foreach (self::$dataModel->data()->fetchAll() as $grapheme)
			$output .= "<a href='".p::Url('?list/alphabet/'.$grapheme['id'].'/'.$grapheme['grapheme'])."'><span class='native'>".$grapheme['grapheme']."</span></a>";
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
		self::$dataModel = new pDataModel('graphemes');
		self::$dataModelGroups = new pDataModel('graphemes_groups');
		self::$dataModel->setCondition(" WHERE in_alphabet = 1");
		self::$dataModel->setOrder(" sorter ASC ");
		self::$dataModel->getObjects();

		// Let's create the order
		foreach(self::$dataModel->data()->fetchAll() as $grapheme)
			self::$order[] = $grapheme['grapheme'];

		// Remember this...
		self::$_init = true;

		return new self;
	}

	public static function getByStart($start){
		if(!self::$_init)
			self::init();
		$output = array();
		foreach(self::$dataModel->data()->fetchAll() as $letter)
			if(p::StartsWith($letter['grapheme'], $start))
				$output[] = $letter;
		return $output;
	}

	public function getFilter($letter){
		$alphabet_filter = self::getByStart($letter);
		$output = array();
		foreach($alphabet_filter as $alphabet_filter_instance) {
			if(!(mb_strlen($alphabet_filter_instance['grapheme'], "UTF-8") == mb_strlen($letter, "UTF-8")))
				$output[] = $alphabet_filter_instance['grapheme'];
		}
		return $output;
	}

	public static function getAll(){
		// The alphabet needs to be loaded first
		if(!self::$_init)
			self::init();
		return self::$dataModel->data()->fetchAll();
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
		  if(!($original_word = @$word['native']))
		  	$original_word = $word['translation'];

		  // Before we split the words, we need to loop through the array to sort it

		  $original_alphabet = self::$order;

		  $filter = array();
		  usort($original_alphabet, function($a, $b) {
		    global $filter;
		    if(mb_strlen($a, "UTF-8") != 1)
		      $filter[] = $a;
		    return mb_strlen($b, "UTF-8")  - mb_strlen($a, "UTF-8");
		  });

		  if(!($word_work_with = str_split_unicode($word['native'])))
		  	$word_work_with = str_split_unicode($word['native']);

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
		
		self::$dataModelGroups->setCondition(" WHERE groupstring = '".$groupstring."' ");
		
		foreach(self::$dataModelGroups->getObjects()->fetchAll() as $grapheme)
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

function str_split_unicode($str, $l = 0) {
    if ($l > 0) {
        $ret = array();
        $len = mb_strlen($str, "UTF-8");
        for ($i = 0; $i < $len; $i += $l) {
            $ret[] = mb_substr($str, $i, $l, "UTF-8");
        }
        return $ret;
    }
    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
}
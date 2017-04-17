<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSearchObject extends pObject{

	private $_template, $_meta;

	public function __construct(){
		// First we are calling the parent's constructor (pObject)
		call_user_func_array('parent::__construct', func_get_args());

		// Now we need to know if there is a 
		if(!isset($this->_activeSection['entry_meta']))
			return trigger_error("pEntryObject needs a **entry_meta** key in its array structure.", E_USER_ERROR);

		$this->_meta = $this->_activeSection['entry_meta'];
	}

	public function render(){

		$languageQuery = explode('-', pAdress::arg()['section']);

		// Fetching the search-language
		if(isset($languageQuery[0]))
			$searchlang = new pLanguage(strtoupper($languageQuery[0]));
		else
			$searchlang = new pLanguage(1);

		if(isset($languageQuery[1]))
			$returnlang = new pLanguage(strtoupper($languageQuery[1]));
		else
			$returnlang = new pLanguage(0);


		foreach((new pLemmaDataObject)->search($searchlang->id, $returnlang->id, pAdress::arg()['query'], false) as $lemma)
			var_dump($lemma);
	}
}
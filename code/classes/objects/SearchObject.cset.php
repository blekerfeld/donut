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

	private function parseSearchResults($query, $results, $searchlang){
		if($searchlang->read('id') == 0){
			$results->setSearchQuery($query);
			return $results->renderSearchResult(0);
		}
		else
			foreach($results as $lemma){
				$lemma->setSearchQuery($query);
				$lemma->renderSearchResult($searchlang->read("id"));
			}
		return true;
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


		// Let's save the return lang inside a session

		pAdress::session('searchLanguage', $searchlang->read('locale').'-'.$returnlang->read('locale'));
		pAdress::session('returnLanguage', ($searchlang->read('id') == '0') ? $returnlang->read('id') : $searchlang->read('id'));

		// The query could have come here by post or by arguments, post has priority
		$query = (isset(pAdress::post()['query'])) ? pAdress::post()['query'] : ((isset(pAdress::arg()['query'])) ? (isset(pAdress::arg()['query'])) : ''); 
		$wholeword = (isset(pAdress::post()['exactMatch'])) ? (pAdress::post()['exactMatch'] == 'true') : ((isset(pAdress::arg()['exactMatch'])) ? (pAdress::arg()['exactMatch'] == 'true') : ''); 

		pAdress::session('searchQuery', $query);
		pAdress::session('exactMatch', $wholeword);


		$lemmaObject = new pLemmaDataObject;


		$fetchSearch = $lemmaObject->search($searchlang->id, $returnlang->id, $query, $wholeword);

		// If not ajax we need to fancy up a bit here TODO

		ajaxSkip:
			if(count($fetchSearch) == 0)
				pOut("<div class='danger-notice small'>".(new pIcon('fa-warning', 12))." ".DICT_NO_HITS."</div>");

			pOut("<table class='dWordTable'>");
			foreach($fetchSearch as $lemma)
				$this->parseSearchResults($query, $lemma, $searchlang);
			pOut('</table>');

			if(isset(pAdress::arg()['ajax']))
				return true;
	}
}
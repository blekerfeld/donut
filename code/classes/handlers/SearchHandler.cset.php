<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSearchHandler extends pHandler{

	private $_template, $_meta;

	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
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

		if(pRegister::arg()['section'] == 'wiki'){
			// TODO
			var_dump(pRegister::arg());
			return p::Out("wiki search is coming soon");
		}		


		$languageQuery = explode('-', pRegister::arg()['section']);

		// Fetching the search-language
		if(isset($languageQuery[0]))
			$searchlang = new pLanguage(strtoupper($languageQuery[0]));
		else
			$searchlang = new pLanguage(1);

		if($languageQuery[0] == $languageQuery[1])
			$returnlang = $searchlang;
		elseif(isset($languageQuery[1]))
			$returnlang = new pLanguage(strtoupper($languageQuery[1]));
		else
			$returnlang = new pLanguage(0);


		// Let's save the return lang inside a session

		pRegister::session('searchLanguage', $searchlang->read('locale').'-'.$returnlang->read('locale'));
		pRegister::session('returnLanguage', ($searchlang->read('id') == '0') ? $returnlang->read('id') : $searchlang->read('id'));

		// The query could have come here by post or by arguments, post has priority
		$query = (isset(pRegister::post()['query'])) ? pRegister::post()['query'] : ((isset(pRegister::arg()['query'])) ? (isset(pRegister::arg()['query'])) : ''); 
		$wholeword = (isset(pRegister::post()['exactMatch'])) ? (pRegister::post()['exactMatch'] == 'true') : ((isset(pRegister::arg()['exactMatch'])) ? (pRegister::arg()['exactMatch'] == 'true') : ''); 

		pRegister::session('searchQuery', $query);
		pRegister::session('exactMatch', $wholeword);


		$lemmaObject = new pLemmaDataModel;


		$fetchSearch = $lemmaObject->search($searchlang->id, $returnlang->id, $query, $wholeword);

		
		// If not ajax we need to fancy up a bit here TODO

		ajaxSkip:

			p::Out("<div class='card-tabs-bar titles'>
				<a class='ssignore back-mini' href='javascript:void(0);' onClick='$(\".word-search\").val(\"\");callBack();'>".(new pIcon('fa-arrow-left', 12))."</a>
				<a class='ssignore active' data-tab=''>".(new pIcon('fa-search', 12))." ".DICT_SEARCH_RESULTS."</a></div><br />");

			if(count($fetchSearch) == 0)
				p::Out("<div class='medium warning-notice'>".(new pIcon('fa-info-circle', 12))." ".DICT_NO_HITS."</div>");
			
			foreach($fetchSearch as $lemma)
				$this->parseSearchResults($query, $lemma, $searchlang);

			if(isset(pRegister::arg()['ajax']))
				return true;
	}
}
<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: EntryObject.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pSearchHandler extends pHandler{

	private $_view, $_meta;

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

		$fetchSearch = (new pLemmaDataModel)->search($searchlang->id, $returnlang->id, $query, $wholeword);

		
		// If not ajax we need to fancy up a bit here TODO

		ajaxSkip:


			if(count($fetchSearch) == 0 AND $query != '' AND $query != '.'){
				unset($_SESSION['searchQuery']);
				p::Out("<div class='medium warning-notice'><strong>".(new pIcon('fa-info-circle', 12))." ".DICT_NO_HITS_T."</strong><br /> ".sprintf(DICT_NO_HITS, "<strong>".$query."</strong>")."</div>");
				if(pUser::noGuest() AND pUser::checkPermission((int)CONFIG_PERMISSION_CREATE_LEMMAS))
					p::Out(pTemplate::NoticeBox('fa-info-circle', DICT_SEARCH_HINT_1."<a class='ssignore' href='".p::Url('?editor/new/pre-filled/'.urlencode($query))."'>".DICT_SEARCH_HINT_2."</a>", 'hint-notice medium'));
			}
			
			foreach($fetchSearch as $lemma){
				$this->parseSearchResults($query, $lemma, $searchlang);
			}

			if(isset(pRegister::arg()['ajax']))
				return true;
	}
}
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: Entry.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pExampleTemplate extends pEntryTemplate{

	// This function shows the translations title and a little additional information
	public function title(){

	
		$titleSection = new pEntrySection("<strong class='pWord'><span class='native'><a>".$this->_data['idiom']."</span></strong>", '', null, false, true);

		$titleSection->addInformationElement(LEMMA_EXAMPLE);

		return "<br />".$titleSection."<br /><span class='small-caps xsmall'>".sprintf(LEMMA_TRANSLATION_ADDED, "<a href='".pUrl('?profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", pDate($this->_data['created_on']))."</span>";

	}

	public function renderLemmas($lemmas){

		if(!is_array($lemmas))
			return false;

		$content = "<ol>";
		
		$title = LEMMA_EXAMPLE;
		
		foreach($lemmas as $lemma)
			$content .= $lemma->parseListItem();
		
		$content .= "</ol>";

		return pOut(new pEntrySection($title, $content));


	}

}
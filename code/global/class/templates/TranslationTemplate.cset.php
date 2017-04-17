<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: Entry.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pTranslationTemplate extends pEntryTemplate{

	// This function shows the translations title and a little additional information
	public function title($flag = ''){

		//"<a class='' href='javascript:void();'' onclick='window.history.back();'' >".(new pIcon('fa-arrow-left', 12))."</a><strong class='pWord'><span class='native'><a>"

		$titleSection = new pEntrySection("<strong class='pWord'><span class='native'><a>".$this->_data['translation']."</span></strong>", '', null, false, true);

		$titleSection->addInformationElement($flag." ".(new pLanguage($this->_data['language_id']))->parse());

		return "<br />".$titleSection."<br /><span class='small-caps xsmall'>".sprintf(LEMMA_TRANSLATION_ADDED, "<a href='".pUrl('?profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", pDate($this->_data['created_on']))."</span>";

	}

	public function renderLemmas($lemmas){

		if(!is_array($lemmas))
			return false;

		foreach($lemmas as $lemma){
			pOut($lemma->render());
		}


	}

}
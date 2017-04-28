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
	public function title($flag = '', $backhref = null){

		//"<a class='' href='javascript:void();'' onclick='window.history.back();'' >".(new pIcon('fa-arrow-left', 12))."</a><strong class='pWord'><span class='native'><a>"

		$back = '';
		if($backhref != null)
			$back = "<a class='tooltip' href='".$backhref."'>".(new pIcon('fa-arrow-left', 12))."</a>";	

		$titleSection = new pEntrySection($back." <strong class='pWord'><span class='native'><a>".$this->_data['translation']."</span></strong>", '', null, false, true);

		$titleSection->addInformationElement($flag." ".(new pLanguage($this->_data['language_id']))->parse());
		$titleSection->addInformationElement(DA_TRANSLATION);

		return "<br />".$titleSection."<br />";

	}

	public function renderInfo(){
		return "<span class='small-caps xsmall'>".sprintf(LEMMA_TRANSLATION_ADDED, "<a href='".pUrl('?profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", pDate($this->_data['created_on']))."</span>";
	}

	public function renderDesc($desc){
		return pOut(new pEntrySection((new pIcon('fa-info', 12))." ".TRANSLATION_DESC, $desc));
	}

	public function renderLemmas($lemmas){

		$content = "<ol>";
		$language = new pLanguage(0);
		
		$title = (new pDataField(null, null, null, 'flag'))->parse($language->read('flag')) . " " .  sprintf(TRANSLATION_LEMMAS, $language->parse());
		
		foreach($lemmas as $lemma)
			$content .= $lemma->parseListItem();
		
		$content .= "</ol>";

		return pOut(new pEntrySection($title, $content));
	}

}
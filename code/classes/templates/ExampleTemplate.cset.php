<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: ExampleTemplate.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pExampleTemplate extends pEntryTemplate{

	// This function shows the translations title and a little additional information
	public function title($backHref = null){

		$back = '';
		if($backHref != null)
			$back = "<a class='back-mini' href='".$backHref."'>".(new pIcon('fa-arrow-left', 12))."</a>";	

		$realTitle = p::Markdown("# ".$back." <strong class='pWord'><span class='native'><a>".$this->_data['idiom']."</span></strong>", true);

		$titleSection = new pEntrySection("", '', null, false, true);


		$titleSection->addInformationElement((new pDataField(null, null, null, 'flag'))->parse((new pLanguage(0))->read('flag'))." ".LEMMA_EXAMPLE);

		return $realTitle.$titleSection."<br /><span class='small-caps xsmall'>".sprintf(LEMMA_TRANSLATION_ADDED, "<a href='".p::Url('?profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", p::Date($this->_data['created_on']))."</span>";

	}

	public function renderLemmas($lemmas){

		if(!is_array($lemmas))
			return false;

		$content = "<ol>";
		
		$title = LEMMA_EXAMPLE;
		
		foreach($lemmas as $lemma)
			$content .= $lemma->parseListItem();
		
		$content .= "</ol>";

		return p::Out(new pEntrySection($title, $content));


	}

}
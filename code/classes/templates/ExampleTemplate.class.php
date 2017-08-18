<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      ExampleTemplate.class.php

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

		return $realTitle.$titleSection."<br /><span class='small-caps xsmall'>".sprintf(LEMMA_TRANSLATION_ADDED, "<a href='".p::Url('?auth/profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", p::Date($this->_data['created_on']))."</span>";

	}

	public function discussTitle(){
		p::Out("<span class='markdown-body'><h2>".sprintf(LEMMA_DISCUSS_TITLE, "<span class='native'><strong class='pWord'><a>".$this->_data['idiom']."</a></strong></span>")."</h2></span>");
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
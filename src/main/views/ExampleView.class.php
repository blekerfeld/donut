<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: ExampleView.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pExampleView extends pEntryView{

	// This function shows the translations title and a little additional information
	public function title($backHref = null){

		

		$realTitle = p::Markdown("### ".LEMMA_EXAMPLE.": <br /><strong class='pWord example'><span class='native'><a>".$this->_data['idiom']."</span></strong>", true);

		$titleSection = new pEntrySection("", '', null, false, true);

		return $realTitle.$titleSection;

	}

	public function discussTitle(){
		p::Out("<span class='markdown-body'><h2>".sprintf(LEMMA_DISCUSS_TITLE, "<span class='native'><strong class='pWord'><a>".$this->_data['idiom']."</a></strong></span>")."</h2></span>");
	}

	public function renderInfo(){
		return "<span class='small hide-partly'>".sprintf(LEMMA_EXAMPLE_ADDED, "<a href='".p::Url('?auth/profile/'.$this->_data['user_id'])."'>".(new pUser($this->_data['user_id']))->read('username')."</a>", p::Date($this->_data['created_on']))."</span>";
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
<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      Entry.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pDictionaryView extends pEntryView{

	public function renderEntry($lemma){
		return $lemma->renderSearchResult();
	}
	

}
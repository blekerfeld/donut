<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: Entry.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pDictionaryTemplate extends pEntryTemplate{

	public function renderEntry($lemma){
		return $lemma->renderSearchResult();
	}
	

}
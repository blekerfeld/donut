<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: Entry.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLemmaTemplate extends pEntryTemplate{

	public function parseTranslations($translations){
		$overAllContent = "";
		// Going through the languages
		foreach($translations as $key => $languageArray){
			$language = new pLanguage($key);
			if($key == 0)
				$title = (new pDataField(null, null, null, 'flag'))->parse($language->read('flag')) . " " .  sprintf(LEMMA_TRANSLATIONS_MEANINGS, $language->parse());
			else
				$title = (new pDataField(null, null, null, 'flag'))->parse($language->read('flag')) . " " . sprintf(LEMMA_TRANSLATIONS_INTO, $language->parse());
			$content = "<ol>";
			foreach($languageArray as $translation)
				$content .= $translation->parseListItem().$translation->parseDescription();
			$content .= "</ol>";
			$overAllContent .= new pEntrySection($title, $content, '', true, true, true);
		}
		return new pEntrySection(LEMMA_TRANSLATIONS, $overAllContent);
	}

	// Requires the type, class and subclass
	public function title($type, $class, $subclass){

		//"<a class='' href='javascript:void();'' onclick='window.history.back();'' >".(new pIcon('fa-arrow-left', 12))."</a><strong class='pWord'><span class='native'><a>"

		$titleSection = new pEntrySection("<strong class='pWord'><span class='native'><a>".$this->_data['native']."</a></span></strong>", '', null, false, true);

		// Adding information elements to the title
		$titleSection->addInformationElement($type['name']);
		$titleSection->addInformationElement($class['name']);
		if($subclass != null)
			$titleSection->addInformationElement($subclass['name']);

		return $titleSection;

	}

	public function usageNotes($data, $icon){
		$parsed = '';
		// Parsing the notes
		foreach($data as $note)
			$parsed .= '<div class="pNotes">'.pMarkdown($note['note']).'</div>';

		// Retruning the notes in an entry section
		return new pEntrySection("Usage notes", $parsed, $icon);

	}

	public function synonyms($data, $icon, $antonyms = false){
		$output = '';
		foreach($data as $synonym){

			if($synonym['word_id_1'] == $this->_data['id'])
				$synonym_id = $synonym['word_id_2'];
			else
				$synonym_id = $synonym['word_id_1'];

			$synonymLemma = new pLemma($synonym_id, 'words');

			$output .= '<a class="ttip '.($antonyms ? 'antonym' : 'synonym').' score-'.$synonym['score'].'" title="Similarity: '.$synonym['score'].'%" href="'.pUrl('?entry/'.pHashId($synonymLemma->_entry['id'])).'">'.$synonymLemma->native().'</a> ';
		}

		return new pEntrySection(($antonyms ? LEMMA_ANTONYMS : LEMMA_SYNONYMS), $output, $icon);
	}

	public function antonyms($data, $icon){
		return $this->synonyms($data, $icon, true);
	}

}
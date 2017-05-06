<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: Entry.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLemmaTemplate extends pEntryTemplate{

	public function parseTranslations($translations, $justList = false){
		$overAllContent = "";
		// Going through the languages

		if(empty($translations))
			return false;

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

		// If there is a session that restricts the shown languages, offer a link that is able cancel that restriction
		$showAll = ((isset(pAdress::session()['returnLanguage'])) ? '<a class="xsmall float-right" href="'.pUrl("?entry/".$this->_data['id']."/resetTranslations/").'">'.LEMMA_TRANSLATIONS_RESET.'</a>' : '');

		if($justList)
				return $content;

		return new pEntrySection($showAll.(new pIcon('fa-language', 12))." ".LEMMA_TRANSLATIONS, $overAllContent);
	}


	public function parseExamples($examples){

		//return var_dump($examples);

		$overAllContent = "<ol>";
		// Going through the languages

		foreach($examples as $example)
			$overAllContent .= $example->parseListItem();

		return new pEntrySection((new pIcon('fa-quote-right', 12))." ".LEMMA_EXAMPLES, $overAllContent."</ol>");
	}

	public function parseInflections($inflector){
		return new pEntrySection((new pIcon('cards-variant', 12))." ".LEMMA_DECLENSION, $inflector->render()."<br id='cl' />", null, true, false, false, true);
	}


	// Requires the type, class and subclass
	public function title($type, $class, $subclass){

		//"<a class='' href='javascript:void();'' onclick='window.history.back();'' >".(new pIcon('fa-arrow-left', 12))."</a><strong class='pWord'><span class='native'><a>"


		$realTitle = '<a class="lemma-code big float-right ttip" href="'.pUrl('?entry/'.pHashId($this->_data['id'])).'" title="'.$this->_data['id'].'">'.(new pIcon('fa-bookmark-o', 12)).' '.pHashId($this->_data['id']).'</a>'.pMarkdown("# <span class='native'><strong class='pWord'><a>".$this->_data['native']."</a></strong></span>", true);

		$titleSection = new pEntrySection("", '', null, false, true);

		// Adding information elements to the title
		$titleSection->addInformationElement($type['name']);
		$titleSection->addInformationElement($class['name']);
		if($subclass != null)
			$titleSection->addInformationElement($subclass['name']);

		return $realTitle.$titleSection;

	}	

	public function usageNotes($data, $icon){
		$parsed = '';
		// Parsing the notes
		foreach($data as $note)
			$parsed .= '<div class="pNotes">'.pMarkdown($note['note']).'</div>';

		// Returning the notes in an entry section
		return new pEntrySection("Usage notes", $parsed, $icon);

	}

	public function synonyms($data, $icon, $section = 'synonym'){
		$output = '';
		foreach($data as $synonym){

			if($synonym['word_id_1'] == $this->_data['id'])
				$synonym_id = $synonym['word_id_2'];
			else
				$synonym_id = $synonym['word_id_1'];

			$synonymLemma = new pLemma($synonym_id, 'words');

			$output .= '<a class="ttip '.$section.' score-'.$synonym['score'].'" title="Similarity: '.($section == 'antonym' ? 100 - $synonym['score'] : $synonym['score']).'%" href="'.pUrl('?entry/'.pHashId($synonymLemma->_entry['id'])).'">'.$synonymLemma->native().'</a> ';
		}

		return new pEntrySection(($section == 'antonym' ? LEMMA_ANTONYMS : ($section == 'synonym' ? LEMMA_SYNONYMS : LEMMA_HOMOPHONES)), $output, $icon);
	}

	public function antonyms($data, $icon){
		return $this->synonyms($data, $icon, 'antonym');
	}

	public function homophones($data, $icon){
		return $this->synonyms($data, $icon, 'homophone');
	}

}
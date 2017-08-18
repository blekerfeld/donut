<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      Entry.class.php

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

		// // If there is a session that restricts the shown languages, offer a link that is able cancel that restriction
		// $showAll = ((isset(pRegister::session()['returnLanguage'])) ? '<a class="opacity-min float-right" href="'.p::Url("?entry/".$this->_data->_entry['id']."/resetTranslations/").'">'.LEMMA_TRANSLATIONS_RESET.'</a>' : '');
		$showAll = '';

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

		$back = '';
		if(isset(pRegister::arg()['is:result']))
			$back = "<a class='back-mini' onClick='$(\".pEntry\").slideUp();
        	$(\".searchLoad\").slideDown();callBack();' href='javascript:void();'>".(new pIcon('fa-arrow-left', 12))."</a>";

		//"<a class='' href='javascript:void();'' onclick='window.history.back();'' >".(new pIcon('fa-arrow-left', 12))."</a><strong class='pWord'><span class='native'><a>"

		// Sorry sorry sorry about the long code
		$realTitle = $back.' <a class="lemma-code float-right big print" href="#">'.(new pIcon('fa-share-alt',12)).'</a><a target="_blank" class="lemma-code float-right big print" href="'.p::Url('?entry/'.p::HashId($this->_data['id'])."/print").'">'.(new pIcon('fa-print', 12)).'</a><a class="lemma-code big float-right ttip" href="'.p::Url('?entry/'.p::HashId($this->_data['id'])).'" title="'.$this->_data['id'].'">'.(new pIcon('fa-bookmark-o', 12)).' '.p::HashId($this->_data['id']).'</a>'.p::Markdown("# <span class='native'><strong class='pWord'><a class='native'>".$this->_data['native']."</a></strong></span>".($this->_data['ipa'] != '' ? " <span class='pIpa'>/".$this->_data['ipa']."/</span>" : '').($this->_data['hidden'] == 1 ? "<span class='pExtraInfo'>".(new pIcon('fa-eye-slash', 12))." ".LEMMA_HIDDEN."</span>" : ''), true);

		$titleSection = new pEntrySection("", '', null, false, true);

		// Adding information elements to the title
		$titleSection->addInformationElement($type['name']);
		$titleSection->addInformationElement($class['name']);
		if($subclass != null)
			$titleSection->addInformationElement($subclass['name']);

		return $realTitle.$titleSection;

	}	

	public function discussTitle(){
		p::Out("<span class='markdown-body'><h2>".sprintf(LEMMA_DISCUSS_TITLE, "<span class='native'><strong class='pWord'><a>".$this->_data['native']."</a></strong></span>")."</h2></span>");
	}

	public function usageNotes($data, $icon){
		$parsed = '';
		// Parsing the notes
		foreach($data as $note){
			if($note['contents'] != '')
				$parsed .= p::Markdown($note['contents'], false);
		}

		// Returning the notes in an entry section
		if($parsed != '')
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

			$output .= '<a class="ttip '.$section.' score-'.$synonym['score'].'" title="Similarity: '.($section == 'antonym' ? 100 - $synonym['score'] : $synonym['score']).'%" href="'.p::Url('?entry/'.p::HashId($synonymLemma->_entry['id'])).'">'.$synonymLemma->native().'</a> ';
		}

		return new pEntrySection(($section == 'antonym' ? LEMMA_ANTONYMS : ($section == 'synonym' ? LEMMA_SYNONYMS : LEMMA_HOMOPHONES)), $output, $icon);
	}

	public function renderSearchResult($searchlang = 0){
		if(!($this->_data->_hitTranslation == null))
			$hitTranslation = '<em class="dHitTranslation">'.p::Highlight($this->_data->_query, $this->_data->_hitTranslation, '<strong class="dQueryHighlight">', '</strong>').'</em> '.(new pIcon('arrow-right').' ');
		else
			$hitTranslation = '';

		if($searchlang == 0)
			$linkToWord = "<a href='".$this->_data->renderSimpleHref(true)."'>".p::Highlight($this->_data->_query, $this->_data->_entry['native'], '<strong class="dQueryHighlight">','</strong>')."</a>";
		else
			$linkToWord = $this->_data->renderSimpleLink(true);

		p::Out('<div class="dWordWrapper">'.$hitTranslation.'<strong class="dWord"><span class="native">'.$linkToWord."</span>".($this->_data->_entry['ipa'] != '' ? "<span class='dType'> · </span><span class='pIpa small'>/".$this->_data->_entry['ipa']."/</span>" : '')."</strong><span class='dType'> · ".$this->_data->generateInfoString()."</span> ".($this->_data->_entry['hidden'] == 1 ? "<span class='pExtraInfo'>".(new pIcon('fa-eye-slash', 12))." ".LEMMA_HIDDEN."</span>" : '')." <br />".$this->parseTranslations($this->_data->_translations, true)."</div>");
	}

	public function parseListItem($entry){
		return "<li><span><span class='lemma lemma_".$entry['id']." tooltip'><strong class='dWordTranslation'><a href='".p::Url('?entry/'.$entry['id'])."' class='native'>".$entry['native']."</a></strong></span><span class='dType'>
				".$this->_data->generateInfoString()."</span></span></li>";
	}

	public function antonyms($data, $icon){
		return $this->synonyms($data, $icon, 'antonym');
	}

	public function homophones($data, $icon){
		return $this->synonyms($data, $icon, 'homophone');
	}

}
<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: dictionaryfactory.class.php

class pDictionaryFactory{

	protected $_titlePage, $_primaryLanguage = 0, $_secondaryLanguage = 14, $_PDF, $_filename, $_sections;

	public function __construct($filename = 'dictionary', $sLang = 1, $pLang = 0){
		// Create a new pdf-helper
		$this->_PDF = new pDictionaryPDF($filename);
		// Set filename
		$this->_filename = $filename;

		// Languages
		$this->_secondaryLanguage = $sLang;

		// Reset page-header session, this thing will keep track of which lemma-collection on which page (Like bus - butter)
		$_SESSION['generateWords'] = array();
	}

	public function compile(){
		// Start building
		$this->_PDF->startBuilding();
		// Get primary data
		$this->getPrimaryData();
		$count = 0;
		foreach($this->_sections['primary'] as $keyN => $section){
			if(!empty($section['lemmas']))
			$this->addSectionPrimary($section, $keyN);
		}
		// Correct header setting
		for ($i=$this->_PDF->getPageNum(); $i > 0 ; $i--) { 
			if(!isset($_SESSION['generateWords'][$i])){
				$_SESSION['generateWords'][$i]['first'] = '';
				$_SESSION['generateWords'][$i]['last'] = '';
			}
		}
	}

	// This function will get all data for the first part of the dictionary: 0 - Sec
	public function getPrimaryData(){
		$count = 0;
		foreach(pAlphabet::getAll() as $letter){
			$output = array();
			$output['letter'] = $letter;
			$output['filter'] = pAlphabet::getFilter($letter['grapheme']);
			$output['lemmas'] = $this->getLemmas($letter['grapheme'], $output['filter']);
			$this->_sections['primary'][] = $output;
			// First header shall be correct
			if($count == 0){
				$this->_PDF->setHeader('||{WORD1} – {WORD2}');
				$count++;
			}
		}
	}

	// This will get lemmas
	protected function getLemmas($letter, $filter){

		// Some initial stuff
		$filterString = '';
		$lemmas = array();

		foreach ($filter as $filterInstance)
			$filterString .= " AND native NOT LIKE '".$filterInstance."%'";
		
		$words = (new pDataModel('words'))->complexQuery("SELECT words.id, words.native FROM words JOIN translation_words, translations WHERE translations.language_id = ".$this->_secondaryLanguage." AND translation_words.translation_id = translations.id AND translation_words.word_id = words.id AND words.hidden = 0 AND words.native LIKE '".$letter."%' ".$filterString .";")->fetchAll(); 

		// Sort the whole thing
		$words = pAlphabet::sort($words);

		// Create a lemma object for the collection
		foreach ($words as $lemma) {
			$lemmaInstance = new pLemma($lemma[1]['id']);
			$lemmaInstance->bindAll($this->_secondaryLanguage);
			$lemmas[$lemma[1]['native']] = $lemmaInstance;
		}

		// Return the whole collection
		return $lemmas;
	
	}



	public function addSectionPrimary($section, $keyN){
		p::Out("<div style='text-align: center'><h3 class='title'>".$section['letter']['uppercase'].$section['letter']['grapheme']."</h3></div>");
		p::Out("<columns  column-count='2' vAlign='justify' />");
		$count = 0;
		foreach ($section['lemmas'] as $key => $lemma){
			p::Out("<div class='main'>");
			if(isset($_SESSION['generateWords'][$this->_PDF->getPageNum()]['first']))
				$_SESSION['generateWords'][$this->_PDF->getPageNum()]['last'] = $lemma->read('native');
			else
				$_SESSION['generateWords'][$this->_PDF->getPageNum()]['first'] = $lemma->read('native');
			$startPage = $this->_PDF->getPageNum();
			$_SESSION['word_last'] = $lemma->read('native');
			$lemma->renderDictionaryEntry();
			$this->_PDF->addSection();
			$currentPage = $this->_PDF->getPageNum();
			// correction for otherwise skipped pages
			if($currentPage != $startPage){
				for ($i = $startPage; $i < $currentPage - $startPage; $i++) { 
					if(isset($_SESSION['generateWords'][$i]['first']))
						$_SESSION['generateWords'][$i]['last'] = $lemma->read('native');
					else
						$_SESSION['generateWords'][$i]['first'] = $lemma->read('native');
				}
			}
			$count++;
			p::Out("</div>");
		}
		if($count == 0){
			$_SESSION['generateWords'][$this->_PDF->getPageNum()]['first'] = 'a';
			$_SESSION['generateWords'][$this->_PDF->getPageNum()]['last'] =  'a';
		}
		p::Out("<columns  column-count='1' vAlign='justify' />");
		$this->_PDF->addSection();
		if(isset($this->_sections['primary'][$keyN + 1]['letter']['grapheme']))
			$this->_PDF->setHeader('||{WORD1} – {WORD2}');
		else
			$this->_PDF->setHeader(''); 
	}

	public function produce(){
		return $this->_PDF->pass()->Output();
	}


}
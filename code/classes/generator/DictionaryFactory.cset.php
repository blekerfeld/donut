<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: dictionaryfactory.cset.php

class pDictionaryFactory{

	protected $_titlePage, $_primaryLanguage = 0, $_secondaryLanguage = 1, $_PDF, $_filename, $_sections;

	public function __construct($filename = 'dictionary'){
		$this->_PDF = new pDictionaryPDF;
		$this->_filename = $filename;
	}

	public function compile(){
		$this->getPrimaryLemmas();
		foreach($this->_sections as $section)
			$this->addSection($section);
	}

	protected function getLemmas($letter, $filter){

		$filterString = '';
		$lemmas = array();

		foreach ($filter as $filterInstance) {
			$filterString .= " AND native NOT LIKE '".$filterInstance."%'";
		}

		$words = (new pDataModel('words'))->customQuery("SELECT id, native FROM words WHERE hidden = 0 AND native LIKE '".$letter."%' ".$filterString .";")->fetchAll(); 

		// Sort the shit out of this
		$words = pAlphabet::sort($words);

		foreach ($words as $lemma) {
			$lemmaInstance = new pLemma($lemma[1]['id']);
			$lemmaInstance->bindAll($this->_secondaryLanguage);
			$lemmas[$lemma[1]['native']] = $lemmaInstance;
		}

		return $lemmas;
	
	}

	public function getPrimaryLemmas(){

		foreach(pAlphabet::getAll() as $letter){
			$this->_sections[$letter['grapheme']] = array();
			$this->_sections[$letter['grapheme']]['letter'] = $letter;
			$this->_sections[$letter['grapheme']]['filter'] = pAlphabet::getFilter($letter['grapheme']);
			$this->_sections[$letter['grapheme']]['lemmas'] = $this->getLemmas($letter['grapheme'], $this->_sections[$letter['grapheme']]['filter']);
		}
	}

	public function addSection($section){
		p::Out("<div style='text-align: center'><h3>".strtoupper($section['letter']['grapheme']).$section['letter']['grapheme']."</h3></div>");
		p::Out("<columns  column-count='2' vAlign='justify' />");
		foreach ($section['lemmas'] as $key => $lemma)
			$lemma->renderSearchResult();
		p::Out("<columns  column-count='1' vAlign='justify' />");
		$this->_PDF->addSection();
	}

	public function produce(){
		$this->_PDF->buildHTML();
		
		return $this->_PDF->pass()->Output();
	}


}
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: example.cset.php


// This represents an example, please note that the input of this class can only come from a lemma data object

class pIdiom extends pEntry{

	private $_lemma, $_translations, $_keyword;

	public function __construct(){
		// First we are calling the parent's constructor (pEntry)
		call_user_func_array('parent::__construct', func_get_args());
		$this->text = $this->_entry['idiom'];
	}

	// This function is called to render the translation as an entry
	public function renderEntry(){
		global $donut;
		// Setting the page title
		$donut['page']['title'] = $this->_entry['idiom']." - ".CONFIG_SITE_TITLE;

		// If there is someplace to return to, we better do that.
		if(isset(pAdress::arg()['return'])){
			$returnTo = explode(':', pAdress::arg()['return']);
			$backHref = p::Url('?entry/'.($returnTo[0] == '' ? '' : $returnTo[0].'/').$returnTo[1]);
		}

		p::Out($this->_template->title($backHref));

		// Let's render all the lemma's
		$this->bindLemma();
		$this->_template->renderLemmas($this->_lemmas->get());
		
	}

	public function bindLemma($lemma_id = null){
		$this->_lemma = $lemma_id;
		$this->_lemmas = null;
		$linkTable = new pDataModel('idiom_words');
		
		if($lemma_id != null){
			$linkTable->setCondition(" WHERE word_id = '".$lemma_id."' AND idiom_id = '".$this->_entry['id']."' ");
			$linkTable->getObjects();
			if($linkTable->data()->rowCount() != 0)
				$this->_keyword = $linkTable->data()->fetchAll()[0]['keyword'];
		}	else{
			$linkTable->setCondition(" WHERE idiom_id = '".$this->_id."' ");
			$linkTable->getObjects();
			$this->_lemmas = new pSet;

			if($linkTable->data()->rowCount() != 0)
				foreach($linkTable->data()->fetchAll() as $lemma)
					$this->_lemmas->add(new pLemma($lemma['word_id'], 'words'));
		}
		
	}

	public function bindTranslations($language_id = null){

		// Dataobject for the linking table
		$linkTable = new pDataModel('idiom_translations');
		$condition =  " WHERE idiom_id = ".$this->_entry['id'];
		if($language_id != null)
			$condition .= " AND language_id = ".$language_id;	
		$linkTable->setCondition($condition);
		$linkTable->getObjects();

		foreach($linkTable->data()->fetchAll() as $translation)
			$this->_translations[$translation['language_id']][] = $translation;
	}

	public function parseListItem(){
		$output = '<li><a href="'.p::Url('?entry/example/'.$this->_id.'/return/:'.p::HashId($this->_lemma)).'"><span class="pIdiom native">'. p::Markdown(p::Highlight($this->_keyword, $this->text, '<span class="pIdiomHighlight">', '</span>'), false).'</a>';
		if($this->_translations != null)
		foreach($this->_translations as $key => $language){
			$output .= "<br />(<em>".(new pLanguage($key))->parse()."</em>) ";
			$translations = array();
		foreach($language as $idiom)
				$translations[] = "<span class='pIdiomTranslation'>".p::Markdown($idiom['translation'], false)."</span>";
			$output .= implode(', ', $translations);
		}

		return $output."</li>";
	}

}

<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: lemma.cset.php


// This represents a translation, please not that the input of this class can only come from a lemma data object

class pTranslation extends pEntry{

	private $_specification = null, $_lemma, $_language;
	public $language, $translation;

	public function __construct(){
		// First we are calling the parent's constructor (pObject)
		call_user_func_array('parent::__construct', func_get_args());
		$this->language = $this->_entry['language_id'];
		$this->translation = $this->_entry['translation'];

		if(!isset($this->_entry['real_id']))
			$this->_entry['real_id'] = $this->_entry['id'];

		$this->_language = new pLanguage($this->_entry['language_id']);
	}


	// This function is called to render the translation as an entry
	public function renderEntry(){
		global $donut;

		// Setting the page title
		$donut['page']['title'] = $this->_entry['translation']." - ".CONFIG_SITE_TITLE;

		$backHref = null;

		// If there is someplace to return to, we better do that.
		if(isset(pAdress::arg()['return'])){
			$returnTo = explode(':', pAdress::arg()['return']);
			$backHref = p::Url('?entry/'.($returnTo[0] == '' ? '' : $returnTo[0].'/').$returnTo[1]);
		}


		p::Out($this->_template->title((new pDataField(null, null, null, 'flag'))->parse($this->_language->read('flag')), $backHref));

		// Let's render all the lemma's
		$this->bindLemma();

		if($this->_entry['description'] != '')
			$this->_template->renderDesc($this->_entry['description']);

		$this->_template->renderLemmas($this->_lemmas->get());
		
		p::Out("<span class='xsmall'>".$this->_template->renderInfo()."</span>");

	}

	public function bindLemma($lemma_id = null){
		$this->_lemma = $lemma_id;
		$this->_lemmas = null;
		$linkTable = new pDataModel('translation_words');
		
		if($lemma_id != null){
			$linkTable->setCondition(" WHERE word_id = '".$lemma_id."' AND translation_id = '".$this->_entry['real_id']."' ");
			$linkTable->getObjects();
			if($linkTable->data()->rowCount() != 0)
				$this->_specification = $linkTable->data()->fetchAll()[0]['specification'];
		}	else{
			$linkTable->setCondition(" WHERE translation_id = '".$this->_id."' ");
			$linkTable->getObjects();
			$this->_lemmas = new pSet;

			if($linkTable->data()->rowCount() != 0)
				foreach($linkTable->data()->fetchAll() as $lemma)
					$this->_lemmas->add(new pLemma($lemma['word_id'], 'words'));
		}
		
	}

	public function parseListItem(){
		return "<li><span>".($this->_specification != '' ? "<em class='dSpec'>(".$this->_specification.")</em> " : '')."<span href='javascript:void(0);' class='translation trans_".$this->_entry['id']." tooltip'><strong class='dWordTranslation'><a href='".p::Url('?entry/translation/'.$this->_entry['real_id'].'/return/:'.p::HashId($this->_lemma))."'>".$this->translation."</a></strong></span></span></li>";
	}

	public function parseDescription(){
		if($this->_entry['description'] != '')
			return "<div class='desc'>".p::Markdown($this->_entry['description'], false)."</div>";
	}

	public function getInputForm(){
		if($this->_specification != null OR $this->_specification != '')
			return $this->translation . '>' . $this->_specification;
		else
			return $this->translation;
	}

}

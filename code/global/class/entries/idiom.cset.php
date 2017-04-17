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
		$donut['page']['title'] = $this->_entry['translation']." - ".CONFIG_SITE_TITLE;

		// If there is someplace to return to, we better do that.
		if(isset(pAdress::arg()['return'])){
			$returnTo = explode(':', pAdress::arg()['return']);
			$backHref = pUrl('?entry/'.($returnTo[0] == '' ? '' : $returnTo[0].'/').$returnTo[1]);
		}

		pOut($this->_template->title((new pDataField(null, null, null, 'flag'))->parse($this->_language->read('flag'))));

		// Let's render all the lemma's
		$this->bindLemma();

		$this->_template->renderLemmas($this->_lemmas->get());
	
		if(isset($backHref))
			pOut("<br /><br /><a href='".$backHref."' class='actionbutton'>".(new pIcon('fa-arrow-left',12))." ".BACK."</a>");
		
	}

	public function bindLemma($lemma_id = null){
		$this->_lemma = $lemma_id;
		$this->_lemmas = null;
		$linkTable = new pDataObject('idiom_words');
		
		if($lemma_id != null){
			$linkTable->setCondition(" WHERE word_id = '".$lemma_id."' AND idiom_id = '".$this->_entry['id']."' ");
			$linkTable->getObjects();
			if($linkTable->data()->rowCount() != 0)
				$this->_keyword = $linkTable->data()->fetchAll()[0]['keyword'];
		}	else{
			$linkTable->setCondition(" WHERE translation_id = '".$this->_id."' ");
			$linkTable->getObjects();
			$this->_lemmas =  $linkTable->data()->fetchAll();
		}
		
	}

	public function bindTranslations($language_id = null){

		// Dataobject for the linking table
		$linkTable = new pDataObject('idiom_translations');
		$condition =  " WHERE idiom_id = ".$this->_entry['id'];
		if($language_id != null)
			$condition .= " AND language_id = ".$language_id;	
		$linkTable->setCondition($condition);
		$linkTable->getObjects();

		$this->_translations = $linkTable->data()->fetchAll();
	}

	public function parseListItem(){
		return "<li><span>".($this->_specification != '' ? "<em class='dSpec'>(".$this->_specification.")</em> " : '')."<span href='javascript:void(0);' class='translation trans_".$this->_entry['id']." tooltip'><a href='".pUrl('?entry/translation/'.$this->_entry['real_id'].'/return/:'.pHashId($this->_lemma))."'>".$this->translation."</a></span></span></li>";
	}

	public function parseDescription(){
		if($this->_entry['description'] != '')
			return "<div class='desc'>".pMarkdown($this->_entry['description'])."</div>";
	}

}

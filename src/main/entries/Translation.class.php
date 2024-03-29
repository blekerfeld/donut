<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: lemma.class.php


// This represents a translation, please not that the input of this class can only come from a lemma data object

class pTranslation extends pEntry{

	private  $_lemma, $_language;
	public $language, $translation, $_specification;

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


		// Setting the page title
		pTemplate::setTitle($this->_entry['translation']);

		$backHref = null;

		// If there is someplace to return to, we better do that.
		if(isset(pRegister::arg()['return'])){
			$returnTo = explode(':', pRegister::arg()['return']);
			$backHref = p::Url('?entry/'.($returnTo[0] == '' ? '' : $returnTo[0].'/').$returnTo[1]);
		}


		p::Out($this->_view->title((new pDataField(null, null, null, 'flag'))->parse($this->_language->read('flag')), $backHref));

		p::Out("<span class='xsmall'>".$this->_view->renderInfo()."</span>");

		// Let's render all the lemma's
		$this->bindLemma();

		if($this->_entry['description'] != '')
			$this->_view->renderDesc($this->_entry['description']);

		$this->_view->renderLemmas($this->_lemmas->get());
		

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
		return "<li><span>".($this->_specification != '' ? "<em class='dSpec'>(".$this->_specification.")</em> " : '')."<span href='javascript:void(0);' class='translation trans_".$this->_entry['id']." ".($this->_entry['language_id'] == 0 ? 'native' : '')." tooltip'><strong class='dWordTranslation'><a href='".p::Url('?entry/translation/'.$this->_entry['real_id'].'/return/:'.p::HashId($this->_lemma))."'>".$this->translation."</a></strong></span>  ".(new pIcon('chevron-right', 13, 'opacity-6'))."</span></li>";
	}

	public function parseListItemPreview(){
		return "<li><span>".($this->_specification != '' ? "<em class='dSpec'>(".$this->_specification.")</em> " : '')."<span href='javascript:void(0);' class='translation trans_".$this->_entry['id']." ".($this->_entry['language_id'] == 0 ? 'native' : '')." tooltip'><strong class='dWordTranslation'><a href='javascript:void(0)' onClick='$(\".preview-load\").load(\"".p::Url('?entry/translation/'.$this->_entry['real_id'].'/return/:'.p::HashId($this->_lemma))."ajaxLoad/ajax\");'>".$this->translation."</a></strong></span>  ".(new pIcon('chevron-right', 13, 'opacity-6'))."</span></li>";

		return "<a class='".$class." 'href='javascript:void(0)' onClick='$(\".preview-load\").load(\"".$this->renderSimpleHref($result)."/ajaxLoad/ajax\");'>".$this->_entry['native']."</a> ";
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

	public static function add($translation, $language, $lookUp = array()){
		if(isset($lookUp[$translation['trans'].'_'.$language.'_'.$translation['spec']])){
			return array(false, $lookUp[$translation['trans'].'_'.$language.'_'.$translation['spec']]);
		}

		// We need a correction of some kind?
		if($language === 0)
			$language = '0';

		$dM = new pDataModel('translations');
		$dM->setCondition(" WHERE language_id = $language AND translation = ".p::Quote($translation['trans'])."");
		$dM->getObjects();
		if($dM->data()->rowCount() == 0){
			$dM->setCondition('');
			$dM->prepareForInsert(array($language, $translation['trans'], '', date('Y-m-d H:i:s'), pUser::read('id')));
			$dM->insert();
			return self::add($translation, $language, $lookUp);
		}
		else
			return $dM->data()->fetchAll()[0]['id'];
	}


	public static function finalDelete($ID){
		// We will check if there are any links left with this translation otherwise the translations will be gone as well
		$dM = new pDataModel('translation_words');
		$dM->setCondition(" WHERE translation_id = '".$ID."' ");
		$dM->getObjects();

		if($dM->data()->rowCount() == 0)
			$dM->complexQuery("DELETE FROM translations WHERE id = ".$ID);
		else
			return false;
	}


}

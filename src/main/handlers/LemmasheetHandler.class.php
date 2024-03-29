<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: EntryObject.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLemmasheetHandler extends pHandler{

	public $_view, $_rulesheetModel;

	// Constructor needs to set up the view as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel


		if($this->_activeSection['table'] == 'translations'){
			$dfs = new pSet;
			$dfs->add(new pDataField('translation', DA_TRANSLATION, '67%', 'input', true, true, true));
			$dfs->add(new pDataField('language_id', DA_LANG_1, '10%', 'select', true, true, true, 'small-caps', false, new pSelector('languages', null, 'name', true, 'languages')));
			$dfs->add(new pDataField('description', TRANSLATION_DESC, '40%', 'markdown', true, true, false));
			$this->dataModel = new pDataModel('translations', $dfs);
		}
		else{
			try {
				$this->dataModel = new pLemmasheetDataModel($this->_activeSection['table'], (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : null));
			} catch (Exception $e) {
				return false;
			}
			
		}
	}

	public function render(){

	}

	// This is only the default behaviour of the catchAction, other objects might handle this differently!
	public function catchAction($action, $view, $arg = null){
		if($this->_section == 'translation'){

		$this->_activeSection['save_strings'][0] = (new pTranslationView($this->_data[0]))->title((new pDataField(null, null, null, 'flag'))->parse((new pLanguage($this->_data[0]['language_id']))->read('flag')));

			$action = new pMagicActionForm(pRegister::arg()['action'], $this->_activeSection['table'], $this->dataModel->_fields, $this->_activeSection['save_strings'], $this->_app, $this->_section, $this); 

			$action->compile();

			if(isset(pRegister::arg()['ajax']))
				return $action->ajax(false);

			else
				return $action->form(false);
		}

		parent::catchAction($action, $view, $arg);

	}

	public function ajaxGenerateIPA(){
		$twolc = new pTwolc((new pTwolcRules('phonology_ipa_generation'))->toArray());
		$twolc->compile();
		if(isset(pRegister::post()['lemma']))
			p::Out('<script type="text/javascript">$(".lemma-ipa").val("'.@$twolc->feed(pRegister::post()['lemma'])->toSurface().'");</script>');
	}

	public function ajaxSave($editBool = false){
		$edit = false;

		while($edit == false){


			// Let's update the basics
			$edit = $this->dataModel->Basics(pRegister::post()['native'], pRegister::post()['lexform'], pRegister::post()['ipa'], pRegister::post()['lexcat'], pRegister::post()['gramcat'], pRegister::post()['tags'], pRegister::post()['hidden'], $editBool);

			// Let's update the translations
			$edit = $this->dataModel->updateTranslations(json_decode(pRegister::post()['translations'], true));

			// All easy lemma links are fixed like this: (synonyms, antonyms and homophones)
			$links = array('synonyms', 'antonyms', 'homophones');
			foreach($links as $link)
				if(isset(pRegister::post()[$link]))
					$edit = $this->dataModel->updateLinks($link, pRegister::post()[$link]);
				else
					$edit = $this->dataModel->deleteLinks($link);

			if(isset(pRegister::post()['examples']))
				$edit = $this->dataModel->updateLinks('idiom_words', pRegister::post()['examples'], true, 'idiom_id', 'keyword');
			else
				$edit = $this->dataModel->deleteLinks('idiom_words', true);

			if(isset(pRegister::post()['usage_notes']))
				$edit = $this->dataModel->updateUsageNotes(pRegister::post()['usage_notes']);

			if(isset(pRegister::post()['ety_year'], pRegister::post()['ety_desc']))
				$edit = $this->dataModel->updateEtymology(pRegister::post()['ety_year'], pRegister::post()['ety_desc']);

			if($editBool){
				// Prepare the irregular forms 
				$forms = array();
				foreach(array('irregular') as $keyReg){
				$forms[$keyReg] = pRegister::post()[$keyReg];
					foreach($forms[$keyReg] as $key => $form)
					if($form['value'] == '')
						unset($forms[$keyReg][$key]);
				}

				$edit = $this->dataModel->updateForms($forms);
			}

		}
		
		if($edit == false)
			echo pTemplate::NoticeBox('fa-warning', SAVED_ERROR, 'hide warning-notice errorSave');
		else
			echo pTemplate::NoticeBox('fa-check', SAVED, 'hide succes-notice successSave');


		// Some logging
		pLogbook::write(!$editBool ? 'new_lemma' : 'edit_lemma', $this->dataModel->_LemmaID);

		if(!$editBool)
			echo '<script>$("#LemmaSheetForm").trigger("reset");</script>';

			die('<script>$(".saving").slideUp();'.(($edit == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();'.($editBool ? 'setTimeout(function(){ window.location = "'.p::Url('?editor/edit/'.pRegister::arg()['id']).'"; }, 1000);' : ''))."</script>");
	}

	public function ajaxEdit(){
		$this->ajaxSave(true);
	}

	public function ajaxNew(){
		$this->ajaxSave(false);
	}

}
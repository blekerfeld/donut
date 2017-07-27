<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pBatchHandler extends pHandler{

	public $_template, $_rulesheetModel;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel
		$this->_dataModel = null;
		//Template
		$this->_template = new pBatchTemplate($this->_section);
	}

	// This would render the rule list table :)
	public function render(){
		return $this->_template->render($this->_section, array('language', BATCH_CHOOSE_LANGUAGE, pLanguage::allActive(), 'name'));
	}

	public function getData($id = -1){

		if($this->_section == 'translate' AND isset(pRegister::session()['btChooser-translate'])){
			$this->_dataModel = new pDataModel('words');
		
			$this->_data = $this->_dataModel->customQuery("SELECT DISTINCT words.id, words.native, words.classification_id, words.type_id, words.subclassification_id 
			FROM words
			JOIN translation_words 
			JOIN translations ON translations.id = translation_words.translation_id
			WHERE (translation_words.id IS NULL 
			OR (translation_words.id IS NOT NULL AND NOT EXISTS (SELECT * FROM translation_words JOIN translations ON translations.id = translation_words.translation_id WHERE translation_words.word_id = words.id AND translations.language_id = ".$_SESSION['btChooser-translate'].")  
			) AND NOT EXISTS (SELECT * FROM translation_exceptions WHERE word_id = words.id AND language_id = ".$_SESSION['btChooser-translate']." AND user_id = ".pUser::read('id').")) AND  words.id AND  words.id NOT IN ( '" . implode($_SESSION['btSkip-translate'], "', '") . "' ) LIMIT 1;")->fetchAll();
		}
		

		return false;
	}


	public function catchAction($action, $template, $arg = null){
		$function = "catchAction" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function($action, $template, $arg = null);
		else
			return parent::catchAction($action, $template, $arg);
	}

	// This will alter the behavior of action catching for this handler.
	public function catchActionTranslate($action, $template, $arg = null){

		if($action == 'choose' AND isset(pRegister::arg()['ajax'], pRegister::post()['btChooser']))
			return $this->ajaxSetSession();
		if($action == 'serve' AND isset(pRegister::arg()['ajax']))
			return $this->serveCard();
		if($action == 'skip')
			return $this->ajaxSkip();

	}

	public function serveCard(){
		$function = "serveCard" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function();
	}

	public function serveCardTranslate(){
		return $this->_template->cardTranslate($this->_data[0], $this->_section);
	}

	public function ajaxHandle(){

	}

	public function ajaxSkip(){
		$_SESSION['btSkip-'.$this->_section][] = pRegister::post()['skip'];
	}

	public function ajaxSetSession(){
		return pRegister::session('btChooser-'.$this->_section, pRegister::post()['btChooser']);
	}

	
}
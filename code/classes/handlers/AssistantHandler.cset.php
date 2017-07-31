<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pAssistantHandler extends pHandler{

	public $_template, $_rulesheetModel;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel
		$this->_dataModel = null;
		//Template
		$this->_template = new pAssistantTemplate($this->_section);
	}


	public function render(){
		$function = "render" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function();
	}

	public function renderTranslate(){
		$data = array();
		foreach(pLanguage::allActive() as $lang){
			$data[$lang->read('id')] = $this->countData($lang->read('id'));
		}
		return $this->_template->render($this->_section, $data);
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
			) AND NOT EXISTS (SELECT * FROM translation_exceptions WHERE word_id = words.id AND language_id = ".$_SESSION['btChooser-translate']." AND user_id = ".pUser::read('id').")) AND  words.id AND  words.id NOT IN ( '" . @implode($_SESSION['btSkip-translate'], "', '") . "' ) LIMIT 1;")->fetchAll();
		}
		

		return false;
	}

	public function countData($language = null){

		if($this->_section == 'translate' AND ((isset(pRegister::session()['btChooser-translate']) AND $language == null) OR ($language != null))){
	
			$result = array('left' => 0, 'total' => 0, 'percentage' => 0);

			$dM = new pDataModel('words');


			$left = $dM->customQuery("SELECT COUNT(DISTINCT words.id) AS cnt 
			FROM words
			JOIN translation_words 
			JOIN translations ON translations.id = translation_words.translation_id
			WHERE (translation_words.id IS NULL 
			OR (translation_words.id IS NOT NULL AND NOT EXISTS (SELECT * FROM translation_words JOIN translations ON translations.id = translation_words.translation_id WHERE translation_words.word_id = words.id AND translations.language_id = ".($language == null ? $_SESSION['btChooser-translate'] : $language).")  
			) AND NOT EXISTS (SELECT * FROM translation_exceptions WHERE word_id = words.id AND language_id = ".($language == null ? $_SESSION['btChooser-translate'] : $language)." AND user_id = ".pUser::read('id').")) AND  words.id AND  words.id NOT IN ( '" . @implode($_SESSION['btSkip-translate'], "', '") . "' );")->fetchAll()[0];

			$total = $dM->customQuery("SELECT COUNT(DISTINCT words.id) AS cnt 
			FROM words")->fetchAll()[0];

			return array('left' => $left['cnt'], 'total' => $total['cnt'], 'percentage' => ($left['cnt'] / $total['cnt']) * 100);
		}
		

		return false;

	}
	



	public function catchAction($action, $template, $arg = null){
		if($action == 'choose' AND isset(pRegister::arg()['ajax'], pRegister::post()['btChooser']))
			return $this->ajaxSetSession();
		elseif($action == 'serve' AND isset(pRegister::arg()['ajax']))
			return $this->serveCard();
		elseif($action == 'skip' AND isset(pRegister::arg()['ajax']))
			return $this->ajaxSkip();
		elseif($action == 'handle' AND isset(pRegister::arg()['ajax']))
			return $this->ajaxHandle();
		elseif($action == 'never' AND isset(pRegister::arg()['ajax']))
			return $this->ajaxNever();
		elseif($action == 'reset' AND isset(pRegister::arg()['ajax']))
			return $this->ajaxReset();
		else
			return parent::catchAction($action, $template, $arg);
	}


	public function serveCard(){
		$function = "serveCard" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function();
	}

	public function serveCardTranslate(){
		if(!isset($_SESSION['btChooser-translate']))
			return $this->renderTranslate();
		if(isset($this->_data[0]))
			return $this->_template->cardTranslate($this->_data[0], $this->_section);
		else{
			unset($_SESSION['btChooser-translate']);
			unset($_SESSION['btSkip-translate']);
			return $this->_template->cardTranslateEmpty($this->_section);
		}
	}

	public function ajaxHandle(){
		$function = "ajaxHandle" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function();
	}

	public function ajaxReset(){
		unset($_SESSION['btChooser-translate']);
		unset($_SESSION['btSkip-translate']);
	}

	public function ajaxHandleTranslate(){
		if(pRegister::post()['translations'] == '')
			return $_SESSION['btSkip-'.$this->_section][] = $this->_data[0]['id'];
		$translations = array($_SESSION['btChooser-translate'] => pRegister::post()['translations']
			);
		$dM  = new pLemmaSheetDataModel('words', $this->_data[0]['id']);
		$dM->updateTranslations($translations, true);
		$dM->cleanCache('words');
	}

	public function ajaxSkip(){
		$_SESSION['btSkip-'.$this->_section][] = pRegister::post()['skip'];
	}

	public function ajaxNever(){
		$function = "ajaxNever" . ucfirst($this->_section);
		if(method_exists($this, $function))
			return $this->$function();
	}

	public function ajaxNeverTranslate(){
		$dM = new pDataModel('translation_exceptions');
		$dM->prepareForInsert(array(pRegister::post()['never'], $_SESSION['btChooser-translate'], pUser::read('id')));
		$dM->insert();
		$dM->cleanCache('words');
	}



	public function ajaxSetSession(){
		$_SESSION['btSkip-'.$this->_section] = array();
		return pRegister::session('btChooser-'.$this->_section, pRegister::post()['btChooser']);
	}

	
}
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
		return false;
	}


	public function catchAction($action, $template, $arg = null){
		$function = "catchAction" . ucfirst($this->_section);
		if(method_exists($this, $function))
			$this->$function($action, $template, $arg = null);
		else
			parent::catchAction($action, $template, $arg);
	}

	// This will alter the behavior of action catching for this handler.
	public function catchActionTranslate($action, $template, $arg = null){

		if($action == 'choose' AND isset(pRegister::arg()['ajax'], pRegister::post()['btChooser']))
			return $this->ajaxSetSession();
		if($action == 'serve' AND isset(pRegister::arg()['ajax']))
			die("SERVING CARD");

	}

	public function serveCard(){

	}

	public function ajaxSetSession(){
		return pRegister::session('btChooser-'.$this->_section, pRegister::post()['btChooser']);
	}

	
}
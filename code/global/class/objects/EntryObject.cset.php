<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pEntryObject extends pObject{

	private $_template, $_meta;

	public function __construct(){
		// First we are calling the parent's constructor (pObject)
		call_user_func_array('parent::__construct', func_get_args());

		// Now we need to know if there is a 
		if(!isset($this->_activeSection['entry_meta']))
			return trigger_error("pEntryObject needs a **entry_meta** key in its array structure.", E_USER_ERROR);

		$this->_meta = $this->_activeSection['entry_meta'];
	}

	public function catchAction($action){

		if($action = 'resetTranslations'){
			unset($_SESSION['returnLanguage']);
			pUrl('?entry/'.pAdress::arg()['id'], true);
		}

	}

	public function render(){

		$this->_actionbar->generate();

		pOut($this->_actionbar->output);

		// Shortcut to the data
		$data = $this->dataObject->data()->fetchAll()[0];

		// Passing the data on to the template
		$this->_template = new $this->_activeSection['template']($data, $this->_activeSection);

		// We might pass this job to an object if that is specified in the metadat
		if(isset($this->_meta['parseAsObject']) AND class_exists($this->_meta['parseAsObject'])){
			$object = new $this->_meta['parseAsObject']($this->dataObject);
			$object->setTemplate($this->_template);
			if(isset($this->_activeSection['subobjects'])){
				$object->fillSubEntries($this->_activeSection['subobjects']);
			}
			return $object->renderEntry();
		}

		// The title section
		pOut($this->_template->title($data[$this->_meta['title_field']]));

		foreach($this->_activeSection['subobjects'] as $subObject){
			$subObject->setID($this->id);
			$subObject->compile();
		}

	}
}
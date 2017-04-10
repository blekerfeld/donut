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

	public function render(){

		var_dump($_SERVER['REQUEST_URI']);

		// Shortcut to the data
		$data = $this->dataObject->data()->fetchAll()[0];

		// Passing the data on to the template
		$this->_template = new $this->_activeSection['template']($data, $this->_activeSection);

		// The title section
		pOut($this->_template->title($data[$this->_meta['title_field']]));

		foreach($this->_activeSection['subobjects'] as $subObject){
			$subObject->setValue($this->id);
			$subObject->compile();
		}

	}
}
<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pRulesheetHandler extends pHandler{

	public $_template, $_rulesheetModel;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel
		$this->dataModel = new pRuleDataModel((isset(pAdress::arg()['id']) ? pAdress::arg()['id'] : null));
	}

	// This would render the rule list table :)
	public function render(){
		$this->_template = new pRulesheetTemplate($this->_data, $this->_structure[$this->_section]);
		
	}

	// This is the ajax handler for desribing a rule, with help of the respective class
	public function ajaxDescribe(){
		if($this->_section == 'inflections' AND isset(pAdress::post()['rule'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			pOut($inflection->describeRule());
		}

	}

	public function ajaxExample(){
		if($this->_section == 'inflections' AND isset(pAdress::post()['rule'], pAdress::post()['lexform'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			pOut(@$twolc->feed($inflection->inflect(pAdress::post()['lexform']))->toRulesheet());
		}
	}


}
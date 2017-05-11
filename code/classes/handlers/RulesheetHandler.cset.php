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

		if($this->_section == 'inflections')
			$table = 'morphology';

		$this->dataModel = new pRuleDataModel($this->_activeSection['table'], (isset(pAdress::arg()['id']) ? pAdress::arg()['id'] : null));
	}

	// This would render the rule list table :)
	public function render(){
		$this->_template = new pRulesheetTemplate($this->_data, $this->_structure[$this->_section]);
		
	}

	// This is the ajax handler for describing a rule, with help of the respective class
	public function ajaxDescribe(){
		if($this->_section == 'inflections' AND isset(pAdress::post()['rule'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			pOut($inflection->describeRule());
		}

	}

	// This is the ajax handler for testing the rule with an example
	public function ajaxExample(){
		if($this->_section == 'inflections' AND isset(pAdress::post()['rule'], pAdress::post()['lexform'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			pOut(@$twolc->feed($inflection->inflect(pAdress::post()['lexform']))->toRulesheet());
		}
	}

	protected function generateLinksArray(){
		if($this->_section == 'inflections')
			$links = array('lexcat' => @pAdress::post()['lexcat'], 'gramcat' => @pAdress::post()['gramcat'], 'tag' => @pAdress::post()['tags'], 'modes' => @pAdress::post()['tables'], 'submodes' => @pAdress::post()['headings'], 'numbers' => @pAdress::post()['rows']);
		else
			$links = null;

		return $links;
	}

	public function ajaxEdit(){
		$links = $this->generateLinksArray();
		$edit = $this->dataModel->updateRule(pAdress::post()['name'], pAdress::post()['rule'], $links);
		if($edit == false)
			echo pNoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
		else
			echo pNoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".saving").slideUp();'.(($edit == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
	}

	public function ajaxNew(){
		$links = $this->generateLinksArray();
		$id = $this->dataModel->newRule(pAdress::post()['name'], pAdress::post()['rule'], $links);
		if($id == false){
			echo pNoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
			die('<script>$(".saving").slideUp();'.(($id == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
		}
		else
			echo pNoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();
			window.location = "'.pUrl('?rulesheet/'.$this->_section.'/edit/'.$id).'";</script>');
	}

}
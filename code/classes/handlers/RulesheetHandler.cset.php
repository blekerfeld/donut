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
		
	}

	// This is the ajax handler for describing a rule, with help of the respective class
	public function ajaxDescribe(){
		if($this->_section == 'inflection' AND isset(pAdress::post()['rule'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			p::Out($inflection->describeRule());
		}


	}

	// This is the ajax handler for testing the rule with an example
	public function ajaxExample(){
		if($this->_section == 'inflection' AND isset(pAdress::post()['rule'], pAdress::post()['lexform'])){
			$inflection = new pInflection(pAdress::post()['rule']);
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			p::Out(@$twolc->feed($inflection->inflect(pAdress::post()['lexform']))->toRulesheet());
		}
		if($this->_section == 'context' AND isset(pAdress::post()['rule'])){
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			echo @$twolc->feed($explode[1])->toDebug()."<br />";
		}
	}

	protected function generateLinksArray(){
		if($this->_section == 'inflection')
			$links = array('lexcat' => @pAdress::post()['lexcat'], 'gramcat' => @pAdress::post()['gramcat'], 'tag' => @pAdress::post()['tags'], 'modes' => @pAdress::post()['tables'], 'submodes' => @pAdress::post()['headings'], 'numbers' => @pAdress::post()['rows']);
		else
			$links = null;

		return $links;
	}


	// This will alter the behavior of action catching for this handler.
	public function catchAction($action, $template, $arg = null){

		if(p::StartsWith($action, 'new')){

			$explode = explode(':', pAdress::arg()['action']);
			// We will need to check if the ruleset number given is valid;
			$dM = new pDataModel('rulesets');
			if($dM->getSingleObject((isset($explode[1]) ? $explode[1] : 0))->rowCount() == 0)
				return p::Out(pMainTemplate::NoticeBox('fa-warning', 'Ruleset does not exist!', 'danger-notice rulesheet-margin'));
				
			if(isset(pAdress::arg()['ajax']))
				return $this->ajaxNew((isset($explode[1]) ? $explode[1] : 0));


			// Go back to default behaviour
			$template = (new pRulesheetTemplate($this->dataModel, $this->_structure[$this->_section]));
			return $template->renderNew($dM->data()->fetchAll()[0]);
		}


		if($action == 'edit'){
			$dM = new pDataModel('rulesets');
			$dM->getSingleObject((isset($this->_data[0]['ruleset']) ? $this->_data[0]['ruleset'] : 0));
			if(isset(pAdress::arg()['ajax']))
				return $this->ajaxEdit();
			
			$template = (new pRulesheetTemplate($this->dataModel, $this->_structure[$this->_section]));
			return $template->renderEdit($dM->data()->fetchAll()[0]);

		}

		// Other actions are handled as default

		return parent::catchAction($action, $template, $arg);

	}

	public function ajaxEdit(){
		$links = $this->generateLinksArray();
		$edit = $this->dataModel->updateRule(pAdress::post()['name'], pAdress::post()['rule'], pAdress::post()['ruleset'], $links);
		if($edit == false)
			echo pMainTemplate::NoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
		else
			echo pMainTemplate::NoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".saving").slideUp();'.(($edit == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
	}

	public function ajaxRemove(){
		$ruleset = $this->_data[0]['ruleset'];
		$this->dataModel->remove(0, 0, pAdress::arg()['id']);
		echo "<script>window.location = '".p::Url('?rules/view/'.str_replace('/', ':', $this->dataModel->customQuery("SELECT name FROM rulesets WHERE id = $ruleset")->fetchAll()[0]['name']))."';</script>";
	}

	public function ajaxNew($id = 0){
		$links = $this->generateLinksArray();
		$id = $this->dataModel->newRule(pAdress::post()['name'], pAdress::post()['rule'], $id, $links);
		if($id == false){
			echo pMainTemplate::NoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
			die('<script>$(".saving").slideUp();'.(($id == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
		}
		else
			echo pMainTemplate::NoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();
			window.location = "'.p::Url('?rulesheet/'.$this->_section.'/edit/'.$id).'";</script>');
	}

}
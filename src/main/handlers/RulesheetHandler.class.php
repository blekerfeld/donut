<?php
// Donut 0.13-dev - Emma de Roo - Licensed under MIT
// file: EntryObject.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pRulesheetHandler extends pHandler{

	public $_view, $_rulesheetModel;

	// Constructor needs to set up the view as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel

		if($this->_section == 'inflections')
			$table = 'morphology';

		$this->dataModel = new pRuleDataModel($this->_activeSection['table'], (isset(pRegister::arg()['id']) ? pRegister::arg()['id'] : null));

	}

	// This would render the rule list table :)
	public function render(){
		
	}

	// This is the ajax handler for describing a rule, with help of the respective class
	public function ajaxDescribe(){
		if($this->_section == 'inflection' AND isset(pRegister::post()['rule'])){
			$inflection = new pInflection(pRegister::post()['rule']);
			p::Out($inflection->describeRule());
		}


	}

	// This is the ajax handler for testing the rule with an example
	public function ajaxExample(){
		if($this->_section == 'inflection' AND isset(pRegister::post()['rule'], pRegister::post()['lexform'])){
			$inflection = new pInflection(pRegister::post()['rule']);
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			$twolc->compile();
			p::Out(@$twolc->feed($inflection->inflect(pRegister::post()['lexform']))->toRulesheet());
		}
		if($this->_section == 'context' AND isset(pRegister::post()['rule'])){
			$twolc = new pTwolc((new pTwolcRules('phonology_contexts'))->toArray());
			echo @$twolc->feed($explode[1])->toDebug()."<br />";
		}
	}

	protected function generateLinksArray(){
		if($this->_section == 'inflection')
			$links = array('lexcat' => @pRegister::post()['lexcat'], 'gramcat' => @pRegister::post()['gramcat'], 'tag' => @pRegister::post()['tags'], 'modes' => @pRegister::post()['tables'], 'submodes' => @pRegister::post()['headings'], 'columns' => @pRegister::post()['columns'], 'numbers' => @pRegister::post()['rows']);
		else
			$links = null;

		return $links;
	}


	// This will alter the behavior of action catching for this handler.
	public function catchAction($action, $view, $arg = null){

		if(p::StartsWith($action, 'new')){

			$explode = explode(':', pRegister::arg()['action']);
			// We will need to check if the ruleset number given is valid;
			$dM = new pDataModel('rulesets');
			if($dM->getSingleObject((isset($explode[1]) ? $explode[1] : 0))->rowCount() == 0)
				return p::Out(pTemplate::NoticeBox('fa-warning', 'Ruleset does not exist!', 'danger-notice rulesheet-margin'));
				
			if(isset(pRegister::arg()['ajax']))
				return $this->ajaxNew((isset($explode[1]) ? $explode[1] : 0));


			// Go back to default behaviour
			$view = (new pRulesheetView($this->dataModel, $this->_prototype[$this->_section]));
			return $view->renderNew($dM->data()->fetchAll()[0]);
		}


		if($action == 'edit'){
			$dM = new pDataModel('rulesets');
			$dM->getSingleObject((isset($this->_data[0]['ruleset']) ? $this->_data[0]['ruleset'] : 0));
			if(isset(pRegister::arg()['ajax']))
				return $this->ajaxEdit();
			
			$view = (new pRulesheetView($this->dataModel, $this->_prototype[$this->_section]));
			return $view->renderEdit($dM->data()->fetchAll()[0]);

		}

		// Other actions are handled as default

		return parent::catchAction($action, $view, $arg);

	}

	public function ajaxEdit(){
		$links = $this->generateLinksArray();
		$edit = $this->dataModel->updateRule(pRegister::post()['name'], pRegister::post()['rule'], pRegister::post()['ruleset'], $links);
		if($edit == false)
			echo pTemplate::NoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
		else
			echo pTemplate::NoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".saving").slideUp();'.(($edit == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
	}

	public function ajaxRemove(){
		$ruleset = $this->_data[0]['ruleset'];
		$this->dataModel->remove(0, 0, pRegister::arg()['id']);
		echo "<script>window.location = '".p::Url('?grammar/browser/view/'.str_replace('/', ':', $this->dataModel->complexQuery("SELECT name FROM rulesets WHERE id = $ruleset")->fetchAll()[0]['name']))."';</script>";
	}

	public function ajaxNew($id = 0){
		$links = $this->generateLinksArray();
		$id = $this->dataModel->newRule(pRegister::post()['name'], pRegister::post()['rule'], $id, $links);
		if($id == false){
			echo pTemplate::NoticeBox('fa-warning', SAVED_EMPTY, 'hide warning-notice errorSave');
			die('<script>$(".saving").slideUp();'.(($id == false) ? '$(".errorSave").slideDown();' : '$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();')."</script>");
		}
		else
			echo pTemplate::NoticeBox('fa-check', SAVED, 'hide succes-notice successSave');
		die('<script>$(".errorSave").slideUp();$(".successSave").slideDown().delay(1500).slideUp();
			window.location = "'.p::Url('?rulesheet/'.$this->_section.'/edit/'.$id).'";</script>');
	}

}
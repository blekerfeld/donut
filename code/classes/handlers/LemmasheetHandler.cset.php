<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: EntryObject.cset.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLemmasheetHandler extends pHandler{

	public $_template, $_rulesheetModel;

	// Constructor needs to set up the template as well
	public function __construct(){
		// First we are calling the parent's constructor (pHandler)
		call_user_func_array('parent::__construct', func_get_args());
		// Override the datamodel

		if($this->_section == 'inflections')
			$table = 'morphology';

		$this->dataModel = new pLemmasheetDataModel($this->_activeSection['table'], (isset(pAdress::arg()['id']) ? pAdress::arg()['id'] : null));
	}

	// This would render the rule list table :)
	public function render(){
		$this->_template = new pLemmasheetTemplate($this->_data, $this->_structure[$this->_section]);
	}	
	

	public function ajaxGenerateIPA(){
		$twolc = new pTwolc((new pTwolcRules('phonology_ipa_generation'))->toArray());
		$twolc->compile();
		if(isset(pAdress::post()['lemma']))
			p::Out('<script type="text/javascript">$(".lemma-ipa").val("'.@$twolc->feed(pAdress::post()['lemma'])->toSurface().'");</script>');
	}

	public function ajaxEdit(){
		var_dump(json_decode(pAdress::post()['translations']));
	}

	public function ajaxNew(){
		$links = $this->generateLinksArray();
		$id = $this->dataModel->newRule(pAdress::post()['name'], pAdress::post()['rule'], $links);
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
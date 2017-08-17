<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: RuleDataModel.class.php



class pRuleDataModel extends pDataModel{

	public $_links, $_RuleID;

	public function __construct($table, $id = 0){
		parent::__construct($table);
		$fields = new pSet;
		$fields->add(new pDataField('name'));
		$fields->add(new pDataField('rule'));
		$fields->add(new pDataField('ruleset'));
		$fields->add(new pDataField('in_set'));
		$this->setFields($fields);
		if($id != 0){
			$this->_RuleID = $id;
			$this->getSingleObject($id);
			$this->_rule = $this->data()->fetchAll()[0];
			// We need to get all links if we are dealing with a morphological rule, that is
			if($this->_table == 'morphology'){
				$this->_links['gramcat'] = $this->resultToSingleArray("SELECT gramcat_id FROM morphology_gramcat WHERE morphology_id = '".$id."';", 'gramcat_id');
				$this->_links['lexcat'] = $this->resultToSingleArray("SELECT lexcat_id FROM morphology_lexcat WHERE morphology_id = '".$id."';", 'lexcat_id');
				$this->_links['tag'] = $this->resultToSingleArray("SELECT tag_id FROM morphology_tags WHERE morphology_id = '".$id."';", 'tag_id');
				$this->_links['modes'] = $this->resultToSingleArray("SELECT mode_id FROM morphology_modes WHERE morphology_id = '".$id."';", 'mode_id');
				$this->_links['submodes'] = $this->resultToSingleArray("SELECT submode_id FROM morphology_submodes WHERE morphology_id = '".$id."';", 'submode_id');
				$this->_links['numbers'] = $this->resultToSingleArray("SELECT number_id FROM morphology_numbers WHERE morphology_id = '".$id."';", 'number_id');
				$this->_links['columns'] = $this->resultToSingleArray("SELECT column_id FROM morphology_columns WHERE morphology_id = '".$id."';", 'column_id');
			}
		}
	}


	public function newRule($name, $rule, $ruleset = 0, $links = null){
		if($name == '' OR $rule == '')
			return false;
		// First insert the rule itself
		$this->prepareForInsert(array($name, $rule, $ruleset, 1));
		$id = $this->insert();
		// We have to create the links
		if(is_array($links)){
			foreach($links as $key => $section){
				$dM = new pDataModel("morphology_".$key);
				if(is_array($section)){
					foreach($section as $sKey => $link){
						$dM->prepareForInsert(array($id, $link));
						$dM->insert();
					}
				}
			}
		}
		return $id;
	}

	public function updateRule($name, $rule, $ruleset = 0, $links = null){
		if($name == '' OR $rule == '')
			return false;
		// First change the basic info
		$this->prepareForUpdate(array($name, $rule, $ruleset, 1));
		$this->update();
		// If this is a rule with links, we need to add any new links, remove any others
		if(is_array($links)){
			foreach($links as $key => $section){
				if(is_array($section))
					foreach($section as $sKey => $link){
						if(!in_array($link, $this->_links[$key])){
							$dM = new pDataModel("morphology_".$key);
							$dM->prepareForInsert(array($this->_singleId, $link));
							$dM->insert();
						}
						unset($this->_links[$key][$sKey]);
					}
			}
			// The links that are left, present them please
			if(!empty($this->_links)){
				foreach($this->_links as $key => $deleteArray){
					foreach($deleteArray as $deleteLink){
						$this->customQuery("DELETE FROM morphology_".$key." WHERE morphology_id = '".$this->_singleId."' AND ".$key."_id = '".$deleteLink."'");
					}
				}
			}
			return true;
		}
	}
}


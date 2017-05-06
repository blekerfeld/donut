<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: RuleDataModel.cset.php



class pRuleDataModel extends pDataModel{

	private $_lemma;

	public $_links;

	public function __construct($id = 0){
		parent::__construct('morphology');
		if($id != 0){
			$this->getSingleObject($id);
			$this->_rule = $this->data()->fetchAll()[0];
			// We need to get all links
			$this->_links['gramcat'] = $this->resultToSingleArray("SELECT gramcat_id FROM morphology_gramcat WHERE morphology_id = '".$id."';", 'gramcat_id');
			$this->_links['lexcat'] = $this->resultToSingleArray("SELECT lexcat_id FROM morphology_lexcat WHERE morphology_id = '".$id."';", 'lexcat_id');
			$this->_links['tags'] = $this->resultToSingleArray("SELECT tag_id FROM morphology_tags WHERE morphology_id = '".$id."';", 'tag_id');
			$this->_links['tables'] = $this->resultToSingleArray("SELECT mode_id FROM morphology_modes WHERE morphology_id = '".$id."';", 'mode_id');
			$this->_links['headings'] = $this->resultToSingleArray("SELECT submode_id FROM morphology_submodes WHERE morphology_id = '".$id."';", 'submode_id');
			$this->_links['rows'] = $this->resultToSingleArray("SELECT number_id FROM morphology_numbers WHERE morphology_id = '".$id."';", 'number_id');
	
		}
	}

}


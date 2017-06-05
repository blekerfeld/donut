<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: pParadigm.cset.php

// The data model for a paradigm 

class pParadigm{

	protected $_id, $_data, $_dataModel, $_headings, $_rows;

	public function __construct($mode){
		$this->_data = $mode;
		$this->_id = $mode['id'];
		$this->_dataModel = new pDataModel('modes');
		if(is_numeric($mode)){
			$this->_data = $this->_dataModel->getSingleObject($mode)->fetchAll()[0];
			$this->_id = $mode;
		}
		$this->_headings = $this->_dataModel->customQuery("SELECT submodes.* FROM submodes JOIN submode_apply ON submodes.id = submode_apply.submode_id WHERE submode_apply.mode_type_id = ".$this->_data['mode_type_id'])->fetchAll();
		$this->_rows = $this->_dataModel->customQuery("SELECT numbers.* FROM numbers JOIN number_apply ON numbers.id = number_apply.number_id WHERE number_apply.mode_type_id =  ".$this->_data['mode_type_id'].";")->fetchAll();
	}

	public function compile($lemma){
		$output = array();

		foreach($this->_headings as $heading){
			$output[$heading['id']]['heading'] = $heading;
			$output[$heading['id']]['rows'] = array();
			foreach($this->_rows as $row ){
				// The row itself
				$output[$heading['id']]['rows']['row_'.$row['id']]['self'] = $this->findRowNative($row);
				// The inflection	
				$rules = $this->findRules($lemma, $heading, $row);
				$output[$heading['id']]['rows']['row_'.$row['id']]['rules'] = $rules;
				// The lexical form or stem
				$output[$heading['id']]['rows']['row_'.$row['id']]['stems'] = $this->findIrregularForms($lemma, $heading, $row, $rules);
				// Aux info
				$output[$heading['id']]['rows']['row_'.$row['id']]['aux'] = $this->findAux($row, $heading, $lemma);
			}
		}

		return $output;
	}

	protected function findInput($lemma){

		// First we need to find out if there is an irregular stem
		if(true)
			return $lemma['native'];

	}

	protected function findRowNative($row){

		$check = $this->_dataModel->customQuery("SELECT native FROM words JOIN row_native ON row_native.word_id = words.id WHERE row_native.row_id = ".$row['id']);

		if($check->rowCount() != 0)
			$row['name'] = $check->fetchAll()[0]['native'];

		return $row;
	}

	protected function findAux($row, $heading, $lemma){

		$checkGramGroups = $this->_dataModel->customQuery("

			SELECT aux.word_id AS id, aux.mode_id, aux.placement, aux.inflect FROM gram_groups_aux AS aux
			JOIN gram_groups_members AS gg ON aux.gram_group_id = gg.gram_group_id
			WHERE gg.mode_id = ".$this->_id." AND gg.submode_id = ".$heading['id']." AND (gg.number_id = ".$row['id']." OR gg.number_id = 0) AND
			(gg.type_id = '".$lemma->read('type_id')."' OR gg.type_id = 0) AND
			(gg.classification_id = '".$lemma->read('classification_id')."' OR gg.classification_id = 0) AND
			(gg.subclassification_id = '".$lemma->read('subclassification_id')."' OR gg.classification_id = 0)

			");

		if($checkGramGroups->rowCount() == 0)
			return null;
		else
			return $checkGramGroups->fetchAll();
	}

	protected function findIrregularForms($lemma, $heading, $row, $rules){

		$output = array();
		$irregRules = $this->findRules($lemma, $heading, $row, true);

		if($lemma->read('lexical_form') != '')
			$padding = array($lemma->read('lexical_form'), false);
		else
			$padding = array($lemma->read('native'), false);

		if(count($rules) == 0)
			$rules = array(false);

		if(count($irregRules) == 0)
			$irregRules = array(false);

		foreach($rules as $rule){
			// Now it's time to loop through the rules we have if we can maybe find irregular forms for the rules
			foreach($irregRules as $irregRule)
				if($irregRule == false){
					$output[] = $padding;
					continue;
				}
				else
					$output[] = array($irregRule['irregular_form'], ($irregRule['is_stem'] == 1));
		}

		return $output; 
	}

	protected function findRules($lemma, $heading, $row, $irregular = false){


		// First we need to fetch all potatial candidates

		$candidates = $this->_dataModel->customQuery("SELECT DISTINCT morphology_id FROM morphology_modes WHERE mode_id = ".$this->_id." UNION
		SELECT DISTINCT morphology_id FROM morphology_submodes WHERE submode_id = ".$heading['id']."  UNION
		SELECT DISTINCT morphology_id FROM morphology_numbers WHERE number_id = ".$row['id']." UNION
		SELECT DISTINCT morphology_id FROM morphology_lexcat WHERE lexcat_id = ".$lemma->read('type_id')." UNION
		SELECT DISTINCT morphology_id FROM morphology_gramcat WHERE gramcat_id = ".$lemma->read('classification_id')." UNION
		SELECT DISTINCT morphology_id FROM morphology_tags WHERE tag_id = ".$lemma->read('subclassification_id').";
		")->fetchAll();

		$rules = array();

		// We need to validate each candidate
		foreach($candidates as $candidate){


			$validation = $this->_dataModel->customQuery("SELECT id, morphology_id, mode_id AS selective, 'mode' AS selector FROM morphology_modes WHERE morphology_id = ".$candidate['morphology_id']." UNION
				SELECT id, morphology_id, submode_id AS selective, 'submode' AS selector FROM morphology_submodes WHERE morphology_id = ".$candidate['morphology_id']."  UNION
				SELECT id, morphology_id, number_id AS selective, 'number' AS selector FROM morphology_numbers WHERE morphology_id = ".$candidate['morphology_id']." UNION
				SELECT id, morphology_id, lexcat_id AS selective, 'lexcat' AS selector  FROM morphology_lexcat WHERE morphology_id = ".$candidate['morphology_id']." UNION
				SELECT id, morphology_id, gramcat_id AS selective, 'gramcat' AS selector  FROM morphology_gramcat WHERE morphology_id = ".$candidate['morphology_id']." UNION
				SELECT id, morphology_id, tag_id AS selective, 'tag' AS selector  FROM morphology_tags WHERE morphology_id = ".$candidate['morphology_id'].";")->fetchAll();
			

			// The first step is filtering out the ones that are not true
			$possibleMatches = array();
			foreach($validation as $key => $validate){
				$possibleMatches[$validate['selector']][$key] = $validate['selective'];
			}

			$accepted = false;

			foreach($possibleMatches as $keyMatch => $match){
				if($keyMatch == 'mode'){
					$accepted = in_array($this->_id, $match);
					if(!$accepted)
						break;
				}
				elseif($keyMatch == 'submode'){
					$accepted = in_array($heading['id'], $match);
					if(!$accepted)
						break;
				}
				elseif($keyMatch == 'number'){
					$accepted = in_array($row['id'], $match);
					if(!$accepted)
						break;
				}
				elseif($keyMatch == 'lexcat'){
					$accepted = in_array($lemma->read('type_id'), $match);
					if(!$accepted)
						break;
				}
				elseif($keyMatch == 'gramcat'){
					$accepted = in_array($lemma->read('classification_id'), $match);
					if(!$accepted)
						break;
				}
				elseif($keyMatch == 'tag'){
					$accepted = in_array($lemma->read('subclassification_id'), $match);
					if(!$accepted)
						break;
				}
			}

			if($accepted)
				$rules[] = 'id = '.$candidate['morphology_id'];
		}
 
		// Now it is finaly time to fetch the rules

		// Fail safe
		if(count($rules) == 0)
			$rules[] = 'id = -1';

		if(!$irregular)
			return $this->_dataModel->customQuery("SELECT * FROM morphology WHERE (".implode(" OR ", $rules).") AND is_irregular = 0;")->fetchAll();
		else
			return $this->_dataModel->customQuery("SELECT * FROM morphology WHERE (".implode(" OR ", $rules).") AND is_irregular = 1 AND lemma_id = ".$lemma->read('id').";")->fetchAll();

	}

	protected function validateRuleSelector($selector, $selective, $lemma, $heading, $row){
		if($selector == 'mode')
			return ($this->_id == $selective);
		elseif($selector == 'submode')
			return ($heading['id'] == $selective);
		elseif($selector == 'number')
			return ($row['id'] == $selective);
		elseif($selector == 'lexcat')
			return ($lemma->read('type_id') == $selective);
		elseif($selector == 'gramcat')
			return ($lemma->read('classification_id') == $selective);
		elseif($selector == 'tag')
			return ($lemma->read('subclassification_id') == $selective);		
	}

}
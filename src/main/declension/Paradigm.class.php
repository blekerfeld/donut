<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: Paradigm.class.php

// Paradigm: represents an inflectional system

class pParadigm{

	protected $_id, $dataModel;
	public  $_data, $_headings, $_rows, $_columns;

	public function __construct($mode){

		$this->_data = $mode;
		$this->_id = $mode['id'];
		$this->dataModel = new pDataModel('modes');
		if(is_numeric($mode)){
			$this->_data = $this->dataModel->getSingleObject($mode)->fetchAll()[0];
			$this->_id = $mode;
		}

		$headings = $this->dataModel->complexQuery("SELECT submodes.* FROM submodes JOIN submode_apply ON submodes.id = submode_apply.submode_id WHERE submode_apply.mode_id = ".$this->_data['id'])->fetchAll();

		foreach($headings as $heading)
			$this->_headings[$heading['id']] = $heading;

		// Create a dummy heading if none found
		if(empty($this->_headings))
			$this->_headings['-1'] = array(
				'id' => '-1',
				'name' => '',
				'short_name' => '',
			);

		$rows = $this->dataModel->complexQuery("SELECT numbers.* FROM numbers JOIN number_apply ON numbers.id = number_apply.number_id WHERE number_apply.mode_id =  ".$this->_data['id'].";")->fetchAll();

		foreach($rows as $row)
			$this->_rows[$row['id']] = $row;

		$columns = $this->dataModel->complexQuery("SELECT columns.* FROM columns JOIN column_apply ON columns.id = column_apply.column_id WHERE column_apply.mode_id =  ".$this->_data['id'].";")->fetchAll();

		foreach($columns as $column)
			$this->_columns[$column['id']] = $column;
	}


	public static function preview($name, $headings, $rows, $columns){
		return p::Out(new pPreviewTable($name, explode('//', $headings), explode('//', $rows), explode('//', $columns)));
	}

	
	public function updateHeadings($headingIDs){

		$workHeadings = $this->_headings;
		$dM = new pDataModel('submode_apply');

		foreach($headingIDs as $headingID){
			if($workHeadings == null OR !isset($workHeadings[$headingID])){
				$dM->prepareForInsert(array($headingID, $this->_data['mode_id']));
				$dM->insert();
			}elseif(isset($workHeadings[$headingID]))
				unset($workHeadings[$headingID]);

		}

		if($workHeadings != null){
			// The rest needs to be deleted then
			foreach($workHeadings as $heading){
				$dM->complexQuery("DELETE FROM submode_apply WHERE mode_id = '".$this->_data['mode_id']."' AND submode_id = '".$heading['id']."'");
			}
		}
	}

	public function updateRows($rowIDs){

		$workRows = $this->_rows;
		$dM = new pDataModel('number_apply');

		foreach($rowIDs as $rowID){
			if($workRows == null OR !isset($workRows[$rowID])){
				$dM->prepareForInsert(array($rowID, $this->_data['mode_id']));
				$dM->insert();
			}elseif(isset($workRows[$rowID]))
				unset($workRows[$rowID]);

		}

		if($workRows != null){
			// The rest needs to be deleted then
			foreach($workRows as $row){
				$dM->complexQuery("DELETE FROM number_apply WHERE mode_id = '".$this->_data['mode_id']."' AND number_id = '".$row['id']."'");
			}
		}
	}

	public function updateColumns($columnIDs){

		$workColumns = $this->_columns;
		$dM = new pDataModel('column_apply');

		foreach($columnIDs as $columnID){
			if($workColumns == null OR !isset($workColumns[$columnID])){
				$dM->prepareForInsert(array($columnID, $this->_data['mode_id']));
				$dM->insert();
			}elseif(isset($workColumns[$columnID]))
				unset($workColumns[$columnID]);

		}

		if($workColumns != null){
			// The rest needs to be deleted then
			foreach($workColumns as $column){
				$dM->complexQuery("DELETE FROM column_apply WHERE mode_id = '".$this->_data['mode_id']."' AND column_id = '".$column['id']."'");
			}
		}
	}

	public function compile($lemma){
		$output = array();

		// Gettins the columns in case we need them
		$columns = $this->findColumns();
		$this->_columns = $columns;

		$output['columns'] = $columns;
		if($this->_headings != null)
			foreach($this->_headings as $heading){
				$output[$heading['id']]['heading'] = $heading;
				$output[$heading['id']]['heading']['columns'] = $columns;
				$output[$heading['id']]['rows'] = array();
				if($this->_rows != null)
					foreach($this->_rows as $row ){
						// The row itself
						$output[$heading['id']]['rows']['row_'.$row['id']]['self'] = $this->findRowNative($row, $heading);
						// The inflection	
						$rules = $this->findRules($lemma, $heading, $row);
						$output[$heading['id']]['rows']['row_'.$row['id']]['rules'] = $rules;
						$output[$heading['id']]['rows']['row_'.$row['id']]['columns'] = $columns;
						// The lexical form or stem
						$output[$heading['id']]['rows']['row_'.$row['id']]['stems'] = $this->findIrregularForms($lemma, $heading, $row, $rules);
						// Aux info
						$output[$heading['id']]['rows']['row_'.$row['id']]['aux'] = $this->findAux($row, $heading, $lemma);
					}
			}


		return $output;
	}

	protected function findColumns(){

		return $this->dataModel->complexQuery("SELECT DISTINCT(columns.id), columns.* FROM columns JOIN column_apply WHERE mode_id = ".$this->_id)->fetchAll();

	}

	protected function findInput($lemma){

		// First we need to find out if there is an irregular stem
		if(true)
			return $lemma['native'];

	}

	protected function findRowNative($row, $heading){

		$check = $this->dataModel->complexQuery("SELECT native FROM words JOIN row_native ON row_native.word_id = words.id WHERE row_native.row_id = ".$row['id']." AND row_native.heading_id = ".$heading['id']);

		if($check->rowCount() != 0)
			$row['name'] = $check->fetchAll()[0]['native'];

		return $row;
	}

	protected function findAux($row, $heading, $lemma){

		$checkAux = $this->findRules($lemma, $heading, $row, false, true);

		if($checkAux->rowCount() == 0)
			return null;
		else
			return $checkAux;
	}

	protected function findIrregularForms($lemma, $heading, $row, $rules){

		$output = array();
		$irregRules = $this->findRules($lemma, $heading, $row, true);

		if($lemma->read('lexical_form') != '')
			$padding = array($lemma->read('lexical_form'), false, false, 0);
		else
			$padding = array($lemma->read('native'), false, false, 0);

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
					$output[] = array($irregRule['irregular_form'], ($irregRule['is_stem'] == 1), true, $irregRule['id'], $this->findIrregularFormsColumns($irregRule['id']), $padding);
		}

		return $output; 
	}

	protected function findIrregularFormsColumns($ruleID){
		$ids = $this->dataModel->complexQuery("SELECT column_id AS id FROM morphology_columns AS mc WHERE mc.morphology_id = $ruleID");
		$output = array();
		foreach($ids->fetchAll() as $id)
			$output[] = $id['id'];
		return $output;
	}

	protected function findRules($lemma, $heading, $row, $irregular = false, $isAux = false){


		// First we need to fetch all potatial candidates

		$candidates = $this->dataModel->complexQuery("SELECT DISTINCT morphology_id FROM morphology_modes WHERE mode_id = ".$this->_id." UNION
		SELECT DISTINCT morphology_id FROM morphology_submodes WHERE submode_id = ".$heading['id']."  UNION
		SELECT DISTINCT morphology_id FROM morphology_numbers WHERE number_id = ".$row['id']." UNION
		SELECT DISTINCT morphology_id FROM morphology_lexcat WHERE lexcat_id = ".$lemma->read('type_id')." UNION
		SELECT DISTINCT morphology_id FROM morphology_gramcat WHERE gramcat_id = ".$lemma->read('classification_id')." UNION
		SELECT DISTINCT morphology_id FROM morphology_tags WHERE tag_id = ".$lemma->read('subclassification_id').";
		")->fetchAll();

		$rules = array();

		// We need to validate each candidate
		foreach($candidates as $candidate){


			$validation = $this->dataModel->complexQuery("SELECT id, morphology_id, mode_id AS selective, 'mode' AS selector FROM morphology_modes WHERE morphology_id = ".$candidate['morphology_id']." UNION
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

		if($irregular)
			return $this->dataModel->complexQuery("SELECT * FROM morphology WHERE (".implode(" OR ", $rules).") AND is_irregular = 1 AND lemma_id = ".$lemma->read('id')." ORDER BY sorter ASC;")->fetchAll();
			
		elseif($isAux)
			return $this->dataModel->complexQuery("SELECT * FROM morphology WHERE (".implode(" OR ", $rules).") AND is_aux = 1  ORDER BY sorter ASC;");
		else
			return $this->dataModel->complexQuery("SELECT * FROM morphology WHERE (".implode(" OR ", $rules).") AND is_irregular = 0  ORDER BY sorter ASC;")->fetchAll();			

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
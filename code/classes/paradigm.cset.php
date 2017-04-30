<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: pParadigm.cset.php

// The data model for a paradigm 

class pParadigm{

	protected $_id, $_data, $_dataObject, $_headings, $_rows;

	public function __construct($mode){
		$this->_data = $mode;
		$this->_id = $mode['id'];
		$this->_dataObject = new pDataObject('modes');
		if(is_numeric($mode)){
			$this->_data = $this->_dataObject->getSingleObject($mode)->fetchAll()[0];
			$this->_id = $mode;
		}
		$this->_headings = $this->_dataObject->customQuery("SELECT submodes.* FROM submodes JOIN submode_apply ON submodes.id = submode_apply.submode_id WHERE submode_apply.mode_type_id = ".$this->_data['mode_type_id'])->fetchAll();
		$this->_rows = $this->_dataObject->customQuery("SELECT numbers.* FROM numbers JOIN number_apply ON numbers.id = number_apply.number_id WHERE number_apply.mode_type_id =  ".$this->_data['mode_type_id'].";")->fetchAll();
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
				$output[$heading['id']]['rows']['row_'.$row['id']]['stems'] = $this->findIrregularForms($lemma, $rules, $heading, $row);
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

		$check = $this->_dataObject->customQuery("SELECT native FROM words JOIN row_native ON row_native.word_id = words.id WHERE row_native.row_id = ".$row['id']);

		if($check->rowCount() != 0)
			$row['name'] = $check->fetchAll()[0]['native'];

		return $row;
	}

	protected function findAux($row, $heading, $lemma){

		$checkGramGroups = $this->_dataObject->customQuery("

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

	protected function findIrregularForms($lemma, $rules, $heading, $row){

		$output = array();
		$rulesLength = count($rules);

		// Empty rules still need a stem
		if(empty($rules))
			$rules = array(false);

		// First we need to check if there maybe is an irregular form for the whole grammatical group

		$checkGramGroups = $this->_dataObject->customQuery("SELECT i.id, i.irregular_form, i.stem FROM gram_groups_irregular AS i
			JOIN gram_groups_members gg ON gg.mode_id = ".$this->_id." AND gg.submode_id = ".$heading['id']." AND (gg.number_id = ".$row['id']." OR gg.number_id = 0) AND
			(gg.type_id = '".$lemma->read('type_id')."' OR gg.type_id = 0) AND
			(gg.classification_id = '".$lemma->read('classification_id')."' OR gg.classification_id = 0) AND
			(gg.subclassification_id = '".$lemma->read('subclassification_id')."' OR gg.classification_id = 0)
			WHERE i.word_id = ".$lemma->read('id')."");

		if($checkGramGroups->rowCount() != 0)
			$padding = array($checkGramGroups->fetchAll()[0]['irregular_form'], ($checkGramGroups->fetchAll()[0]['stem'] == 0));
		elseif($lemma->read('lexical_form') != '')
			$padding = array($lemma->read('lexical_form'), false);
		else
			$padding = array($lemma->read('native'), false);

		// Now it's time to loop through the rules we have if we can maybe find irregular forms for the rules

		foreach($rules as $rule){
			if($rule == false){
				$output = $padding;
				continue;
			}

			$checkMorphology = $this->_dataObject->customQuery("SELECT i.id, i.irregular_form, i.stem FROM morphology_irregular AS i
			JOIN morphology AS m ON m.id = i.morphology_id
			WHERE i.word_id = ".$lemma->read('id')." AND m.id = ".$rule['id']);
			if($checkMorphology->rowCount() != 0)
				$output[] = array($checkMorphology->fetchAll()[0]['irregular_form'], ($checkMorphology->fetchAll()[0]['stem'] == 0));
			else
				$output[] = $padding;
		}
			
		return $output; 

	}

	protected function findRules($lemma, $heading, $row){


		echo "";


		return $this->_dataObject->customQuery("

			SELECT DISTINCT m.* FROM morphology AS m
			JOIN morphology_modes AS mm ON mm.morphology_id = m.id
			JOIN morphology_submodes AS s ON s.morphology_id = m.id
			JOIN morphology_numbers AS n ON n.morphology_id = m.id
			WHERE 
			((mm.mode_id = ".$this->_id." AND s.submode_id = ".$heading['id'].") AND (m.number_optional = 1)) OR
			(mm.mode_id = ".$this->_id." AND s.submode_id = ".$heading['id']." AND n.number_id = ".$row['id'].")

			UNION

			SELECT m.* FROM morphology AS m

			JOIN gram_groups_members AS gg ON gg.mode_id = ".$this->_id." AND gg.submode_id = ".$heading['id']." AND (gg.number_id = ".$row['id']." OR gg.number_id = 0) AND
				(gg.type_id = '".$lemma->read('type_id')."' OR gg.type_id = 0) AND
				(gg.classification_id = '".$lemma->read('classification_id')."' OR gg.classification_id = 0) AND
				(gg.subclassification_id = '".$lemma->read('subclassification_id')."' OR gg.classification_id = 0)
			JOIN morphology_gram_groups AS mgg ON mgg.gram_group_id = gg.gram_group_id AND mgg.morphology_id = m.id

			UNION

			SELECT m.* FROM morphology AS m

			JOIN lex_groups_members AS lg ON lg.lexcat_id = '".$lemma->read("type_id")."' AND lg.gramcat_id = '".$lemma->read("classification_id")."' AND (lg.tag_id = '".$lemma->read("subclassification_id")."' OR lg.tag_id = 0)
			JOIN morphology_lex_groups AS mlg ON mlg.lex_group_id = lg.lex_group_id AND mlg.morphology_id = m.id

			UNION

			SELECT m.* FROM morphology AS m

			JOIN morphology_lexcat AS lc ON lc.lexcat_id AND m.id = lc.morphology_id

			WHERE lc.lexcat_id = ".$lemma->read("type_id")."

			UNION

			SELECT m.* FROM morphology AS m

			JOIN morphology_gramcat AS gc ON gc.gramcat_id AND m.id = gc.morphology_id

			WHERE gc.gramcat_id = ".$lemma->read("classification_id")."

			UNION

			SELECT m.* FROM morphology AS m

			JOIN morphology_gramcat AS gc ON gc.gramcat_id AND m.id = gc.morphology_id

			WHERE gc.gramcat_id = ".$lemma->read("subclassification_id")."

			")->fetchAll();


	}


}
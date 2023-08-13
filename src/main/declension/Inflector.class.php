<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Emma de Roo - MIT License
	//	++		File: inflector.class.php

// This class does all the inflection related stuff!

class pInflector{
	
	public $_modes, $_tables, $dataModel, $_lemma, $_compiledParadigms, $_twolc, $_twolcRules, $_auxNesting, $_inflCache, $_dmCache, $_irregularRows;

	public function __construct($lemma, $twolcRules, $nesting = 0, $auxNesting = 0){
		if($nesting > 12){
			die();
		}
		$this->_lemma = $lemma;
		$this->_dMCache = new pDataModel('lemmatization');
		$this->dataModel = new pDataModel('modes');
		$this->_modes = $this->dataModel->complexQuery("SELECT DISTINCT * FROM modes WHERE modes.type_id = ".$this->_lemma->read('type_id'))->fetchAll();
		$this->_compiledParadigms = new pSet;
		$this->_twolc = new pTwolc($twolcRules);
		$this->_twolc->compile();
		$this->_twolcRules = $twolcRules;
		$this->_nesting = $nesting;
		$this->_auxNesting = $auxNesting;
		$this->_irregularRows = array();
	}

	public function compile(){

		$tempArray = array();

		// For every mode the paradigm gets compiled
		foreach($this->_modes as $mode){
			$paradigm = new pParadigm($mode);
			$tempArray[$mode['id']] = $paradigm->compile($this->_lemma);
		}

		foreach($this->_modes as $mode){
			foreach($tempArray[$mode['id']] as $p_key => $paradigm){
				// Well, we won't be needing those
				if($p_key == 'columns')
					continue;	
				foreach($paradigm['rows'] as $r_key => $row){

					// Let's look if we have columns
					if(!empty($tempArray[$mode['id']][$p_key]['rows'][$r_key]['columns'])){
						$tempArray[$mode['id']][$p_key]['rows'][$r_key]['inflected'] = array();
						foreach($tempArray[$mode['id']][$p_key]['rows'][$r_key]['columns'] as $column)
							$tempArray[$mode['id']][$p_key]['rows'][$r_key]['inflected'][$column['id']] = $this->inflectRow($row, $paradigm['heading'], $column);
					}
					else{
						// Other wise we'll just stick with a single inflection
						$tempArray[$mode['id']][$p_key]['rows'][$r_key]['inflected'] = $this->inflectRow($row, $paradigm['heading']);
					}
				
				}
			}
		}
		// For every compiled paradigm the inflections get build and added to the row

		$this->_compiledParadigms = $tempArray;

		return $tempArray;

	}



	public function inflectRow($row, $heading, $column = null){


		$output = '';
		$irregular = false;
		$output = $row['stems'][0][0];

		if(isset($row['stems'][0][2]) AND $row['stems'][0][2] == true)
			$irregular = true;

		if($irregular AND $column != NULL AND !in_array($column['id'], $row['stems'][0][4]))
			$output = $row['stems'][0][5][0];

		foreach($row['rules'] as $key => $rule){
			if(!empty($row['columns']) AND $column != NULL AND !$this->checkRuleRowColumn($rule, $row, $column))
				continue;

			$inflection = new pInflection($rule['rule']);
			if($row['stems'][$key][0] == $this->_lemma->read('native'))
				$input = $output;
			else
				$input = $row['stems'][$key][0];

			// Is this an irregular override?

			if($row['stems'][$key][2]){
				$output = $input;
				$irregular = true;
			}
			else{
				$output = $inflection->inflect($input);
				$irregular = false;
			}

			if($irregular == true)
				$this->_irregularRows[] = $row['self']['id'];
		}


		// Aux time max nests: 12


		if($this->_nesting < 12){
			if($row['aux'] == null)
				goto aux_end;

			foreach($row['aux'] as $key => $aux){
				$auxLemma = new pLemma($aux['id']);
				if($aux['aux_mode_id'] == 0)
					$auxInflected = $auxLemma->read('native');
				else{
					$tempForce = $row['aux'];
					unset($tempForce[$key]);
					$auxInflector = new pInflector($auxLemma, $this->_twolcRules, $this->_nesting + 1);
					$auxInflector->compile();
					$auxInflected = $auxInflector->requestSingleInflection($aux['aux_mode_id'], $row['self']['id'], $heading['id']);
				}
				if($aux['placement'] == 0){
					$output = $auxInflected . ' ' . $output;
				}
				else{
					$output = $output . ' ' . $auxInflected;
				}
			}
		}

		aux_end:

		return array($output, $irregular);
	}

	public function buildMode($mode_array, $class = "pInflectionTable"){

		$table = (new $class($this->_dMCache, $this->_lemma, $this->_twolc, $mode_array, $this->_compiledParadigms));

		// Leaving place for future actions

		return $table;

	}

	public function checkRuleRowColumn($rule, $row, $column){

		// If there is an record in morphology_numbers + morphology_columns for this rule, then we are good to go

		$dM = new pDataModel('morphology');
		$firstCheck = $dM->complexQuery("SELECT m.id FROM morphology AS m INNER JOIN morphology_numbers AS mn
			INNER JOIN morphology_columns AS mc WHERE mn.morphology_id = m.id AND mc.morphology_id = m.id AND m.id = ".$rule['id']." AND mc.column_id = ".$column['id']." AND mn.number_id = ".$row['self']['id']." LIMIT 1;")->rowCount();
		if($firstCheck == 1)
			return true;
		elseif($firstCheck == 0 AND $dM->complexQuery("SELECT m.id FROM morphology AS m INNER JOIN morphology_numbers AS mn
			INNER JOIN morphology_columns AS mc WHERE mn.morphology_id = m.id AND mc.morphology_id = m.id AND m.id = ".$rule['id']." AND mn.number_id = ".$row['self']['id']." LIMIT 1;")->rowCount() == 0)
			return true;
		else
			return false;

	}


	public function requestSingleInflection($mode_id, $heading_id, $row_id){
		@$row = $this->_compiledParadigms[$mode_id][$heading_id]['rows']['row_'.$row_id];
		if($row != null)
			return $this->inflectRow($row, $this->_compiledParadigms[$mode_id][$heading_id]['heading']);
		else
			return $this->_lemma->read('native');
	}

	public function render($class = "pInflectionTable", $modeOverwrite = null){
		$output = "<div class='inflector'>";
		if($modeOverwrite != null)
			$output .= $this->buildMode($modeOverwrite, $class);
		else
			foreach($this->_modes as $mode)
				$output .= $this->buildMode($mode, $class);
		return $output."</div>";
	}



}

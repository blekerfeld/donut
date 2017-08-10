<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: inflector.cset.php

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
		$this->_modes = $this->dataModel->customQuery("SELECT DISTINCT * FROM modes WHERE modes.mode_type_id = ".$this->_lemma->read('type_id'))->fetchAll();
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
				foreach($paradigm['rows'] as $r_key => $row){
					$tempArray[$mode['id']][$p_key]['rows'][$r_key]['inflected'] = $this->inflectRow($row, $paradigm['heading']);
				}
			}
		}
		// For every compiled paradigm the inflections get build and added to the row

		$this->_compiledParadigms = $tempArray;

		return $tempArray;

	}



	public function inflectRow($row, $heading){

		$output = '';
		$irregular = false;

		if(empty($row['rules']))
			$output = $row['stems'][0][0];
		else
			$output = $row['stems'][0][0];

		if(isset($row['stems'][0][2]) AND $row['stems'][0][2] == true)
			$irregular = true;

		foreach($row['rules'] as $key => $rule){
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


	public function requestSingleInflection($mode_id, $heading_id, $row_id){
		@$row = $this->_compiledParadigms[$mode_id][$heading_id]['rows']['row_'.$row_id];
		if($row != null)
			return $this->inflectRow($row, $this->_compiledParadigms[$mode_id][$heading_id]['heading']);
		else
			return $this->_lemma->read('native');
	}

	public function render($class = "pInflectionTable"){
		$output = "<div class='inflector'>";
		foreach($this->_modes as $mode)
			$output .= $this->buildMode($mode, $class);
		return $output."</div>";
	}



}

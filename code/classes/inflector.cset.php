<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: inflector.cset.php

// This class does all the inflection related stuff!

class pInflector{
	
	protected $_modes, $_tables, $_dataObject, $_lemma, $_compiledParadigms, $_twolc, $_twolcRules, $_auxNesting;

	public function __construct($lemma, $twolcRules, $nesting = 0, $auxNesting = 0){
		if($nesting > 12){
			die();
		}
		$this->_lemma = $lemma;
		$this->_dataObject = new pDataObject('modes');
		$this->_modes = $this->_dataObject->customQuery("SELECT DISTINCT modes.* FROM modes JOIN mode_apply ON modes.id = mode_apply.mode_id WHERE mode_apply.type_id = ".$this->_lemma->read('type_id'))->fetchAll();
		$this->_compiledParadigms = new pSet;
		$this->_twolc = new pTwolc($twolcRules);
		$this->_twolc->compile();
		$this->_twolcRules = $twolcRules;
		$this->_nesting = $nesting;
		$this->_auxNesting = $auxNesting;
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
		
		if(empty($row['rules']))
			$output = $row['stems'][0];
		else
			$output = $row['stems'][0][0];

		foreach($row['rules'] as $key => $rule){
			$inflection = new pInflection($rule['rule']);
			if($row['stems'][$key][0] == $this->_lemma->read('native'))
				$input = $output;
			else
				$input = $row['stems'][$key][0];
			// Is this an irregular override?
			if($row['stems'][$key][1])
				$output = $input;
			else{
				$output = $inflection->inflect($input);
			}
		}

		// array()

		// Aux time max nests: 12

		if($this->_nesting < 12){
			if($row['aux'] == null)
				goto aux_end;

			foreach($row['aux'] as $key => $aux){
				$auxLemma = new pLemma($aux['id']);
				if($aux['inflect'] == 0)
					$auxInflected = $auxLemma->read('native');
				else{
					$tempForce = $row['aux'];
					unset($tempForce[$key]);
					$auxInflector = new pInflector($auxLemma, $this->_twolcRules, $this->_nesting + 1);
					$auxInflector->compile();
					$auxInflected = $auxInflector->requestSingleInflection($aux['mode_id'], $row['self']['id'], $heading['id']);
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

		return $output;
	}

	public function buildMode($mode_array){

		$mode = $this->_compiledParadigms[$mode_array['id']];
		$mode_name = $mode_array['name'];

		$output = "<div class='inflections_mode_wrap'><table class='inflections'><tr class='title'><td colspan='2'>".$mode_name."</td></tr>";

		foreach($mode as $headingHolder){
			$heading = $headingHolder['heading'];
			$output .= "<tr class='heading'><td colspan='2'>".$heading['name']."</td></tr>";
			foreach($headingHolder['rows'] as $row){
				$output .= "<tr><td class='row_name'>".$row['self']['name']."</td><td class='row_inflected'>".($this->_twolc->feed($row['inflected']))->toSurface()."</td></tr>";
			}
		}

		return $output."</table></div>";

	}

	public function requestSingleInflection($mode_id, $heading_id, $row_id){
		@$row = $this->_compiledParadigms[$mode_id][$heading_id]['rows']['row_'.$row_id];
		if($row != null)
			return $this->inflectRow($row, $this->_compiledParadigms[$mode_id][$heading_id]['heading']);
		else
			return $this->_lemma->read('native');
	}

	public function render(){
		$output = "<div class='inflector'>";
		foreach($this->_modes as $mode)
			$output .= $this->buildMode($mode);
		return $output."</div>";
	}


}

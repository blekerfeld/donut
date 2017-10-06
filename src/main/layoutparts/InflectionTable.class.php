<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
	//	++		File: inflectionTable.class.php

// Used to build an inflection table

class pInflectionTable extends pLayoutPart {

	private $_output, $_lemma, $_inflCache;

	public function __construct($dMCache, $lemma, $twolc, $modeArray, $compiledParadigms){
		$this->_lemma = $lemma;

		$dMCache->setCondition(" WHERE lemma_id = ".$lemma->read('id'));

		foreach($dMCache->getObjects()->fetchAll() as $cachedInflection)
			$this->_inflCache[$cachedInflection['hash']] = $cachedInflection['inflected_form'];

		$dMCache->setCondition("");

		$mode = $compiledParadigms[$modeArray['id']];
		$columns = $mode['columns'];
		unset($mode['columns']);
		$mode_name = $modeArray['name'];

		$output = "<div class='inflections_mode_wrap'><table class='inflections'><tr class='title'><td colspan='".(!empty($columns) ? count($columns) + 1 : 2)."'>".$mode_name."</td></tr>";

		foreach($mode as $headingHolder){
			$heading = $headingHolder['heading'];
			$output .= "<tr class='heading'><td colspan='".(!empty($heading['columns']) ? count($heading['columns']) + 1 : 2)."'>".$heading['name']."</td></tr>";
			if(!empty($heading['columns'])){
				$output .= "<tr class='columns_row'><td></td>";
				foreach($heading['columns'] as $column)
					$output .= "<td class='named'>".$column['name']."</td>";
				$output .= "</tr>";
			}

			foreach($headingHolder['rows'] as $row){
				// Generating the surface form
				if(empty($row['columns'])){
					
					$surface = $twolc->feed($row['inflected'][0])->toSurface();
					// Outing the surface form
					$output .= "<tr><td class='row_name'>".$row['self']['name']."</td><td class='row_inflected'>".$surface."</td></tr>";
					$this->cacheInflection($dMCache, $surface, $row, $heading, $modeArray);
				}
				else{
					$output .= "<tr><td class='row_name'>".$row['self']['name']."</td>";
					foreach($row['columns'] as $column){
						
						$surface = $twolc->feed($row['inflected'][$column['id']][0])->toSurface();
						$output .= "<td class='row_inflected with_column'>".$surface."</td>";
					}
					$output .= "</tr>";
					$this->cacheInflection($dMCache, $surface, $row, $heading, $modeArray);
				}
			}
		}

		return $this->_output = $output."</table></div>";

	}

	public function cacheInflection($dM, $inflection, $row, $heading, $mode){
		$hash = $row['self']['id'].'_'.$heading['id'].'_'.$mode['id'];
		// Already cached
		if(isset($this->_inflCache[$hash]) AND $this->_inflCache[$hash] == $inflection)
			return false;
		elseif(isset($this->_inflCache[$hash]))
			$dM->complexQuery("DELETE FROM lemmatization WHERE lemma_id = ".$this->_lemma->read('id') . " AND hash = '".$hash."'");
		if($inflection != $this->_lemma->read('native') AND $inflection != $this->_lemma->read('lexical_form'))
			return $dM->complexQuery("INSERT INTO lemmatization VALUES(NULL, ".p::Quote($inflection).", '".$hash."', '".$this->_lemma->read('id')."');");
		return false;
	}

	public function __toString(){
		return $this->_output;
	}

}

<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
	//	++		File: irregularTable.class.php

// Used to build an inflection table

class pIrregularTable extends pTemplatePiece {

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
					// Generating the surface form
					if(!$row['inflected'][1])
						$form = $twolc->feed($row['inflected'][0])->toSurface();
					else
						$form = $twolc->feed($row['inflected'][0])->toSurface().'* ';

					if($row['inflected'][1])
						$surface = $twolc->feed($row['inflected'][0])->toSurface();
					else
						$surface = '';
					
					// Outing the surface form
										
					$dataID = $modeArray['id'].'-'.$heading['id']."-".$row['self']['id'];

					$output .= "<tr><td class='row_name'>".$row['self']['name']."</td><td class='row_inflected'><span class='small native tooltip' ></span><input id='input$dataID' data-id='".$dataID."' placeholder='".$form."' class='irreg btInput nWord small normal-font padding-1' value='".$surface."' /></td></tr>";
			
				}
				else{
					$output .= "<tr><td class='row_name'>".$row['self']['name']."</td>";
					
					foreach($row['columns'] as $column){
						$dataID = $modeArray['id'].'-'.$heading['id']."-".$row['self']['id']."-".$column['id'];
						// Generating the surface form
						if(!$row['inflected'][$column['id']][1])
							$form = $twolc->feed($row['inflected'][$column['id']][0])->toSurface();
						else
							$form = $twolc->feed($row['inflected'][$column['id']][0])->toSurface().'* ';

						if($row['inflected'][$column['id']][1])
							$surface = $twolc->feed($row['inflected'][$column['id']][0])->toSurface();
						else
							$surface = '';

						$output .= "<td class='row_inflected with_column'><input id='input$dataID' data-id='".$dataID."' placeholder='".$form."' class='irreg btInput nWord small normal-font padding-1' value='".$surface."' /></td>";
					}
					$output .= "</tr>";
					
				}
			}
		}

		return $this->_output = $output."</table></div>";

	}


	public function __toString(){
		return $this->_output;
	}

}

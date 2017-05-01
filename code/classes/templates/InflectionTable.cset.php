<?php
	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++		File: inflectionTable.cset.php

// Used to build an inflection table

class pInflectionTable extends pTemplatePiece {

	private $_output;

	public function __construct($twolc, $modeArray, $compiledParadigms){


		$mode = $compiledParadigms[$modeArray['id']];
		$mode_name = $modeArray['name'];

		$output = "<div class='inflections_mode_wrap'><table class='inflections'><tr class='title'><td colspan='2'>".$mode_name."</td></tr>";

		foreach($mode as $headingHolder){
			$heading = $headingHolder['heading'];
			$output .= "<tr class='heading'><td colspan='2'>".$heading['name']."</td></tr>";
			foreach($headingHolder['rows'] as $row){
				$output .= "<tr><td class='row_name'>".$row['self']['name']."</td><td class='row_inflected'>".($twolc->feed($row['inflected']))->toSurface()."</td></tr>";
			}
		}


		return $this->_output = $output."</table></div>";

	}

	public function __toString(){
		return $this->_output;
	}

}

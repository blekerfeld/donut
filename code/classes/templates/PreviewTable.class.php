<?php
// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
	//	++		File: inflectionTable.class.php

// Used to build an inflection table

class pPreviewTable extends pTemplatePiece {

	private $_output, $_lemma, $_inflCache;

	public function __construct($name, $headings, $rows, $columns){
		
		if($headings[0] == '')
			$this->_headings['-1'] = array(
				'id' => '-1',
				'name' => '',
				'short_name' => '',
			);

		$output = "<div class='inflections_mode_wrap'><table class='preview inflections'><tr class='title'><td colspan='".(!empty($columns) ? count($columns) + 1 : 2)."'>".($name == '' ? (new pIcon('fa-warning'))." Unnamed table" : $name)."</td></tr>";

		foreach($headings as $heading){
			$output .= "<tr class='heading'><td colspan='".(!empty($columns) ? count($columns) + 1 : 2)."'>".$heading . "</td></tr>";
			if(!empty($columns)){
				$output .= "<tr class='columns_row'><td></td>";
				foreach($columns as $column)
					$output .= "<td class='named'>".$column."</td>";
				$output .= "</tr>";
			}

			if(isset($rows[0]) AND $rows[0] == ''){
				$output .= "<tr><td>...</td><td>No rows yet</td></tr>";
				unset($rows[0]);
			}

			if(!empty($rows))
				foreach($rows as $row){
					$output .= "<tr><td class='row_name'>".$row."</td>";
					foreach($columns as $column)
						$output .= "<td class='row_inflected with_column'>...</td>";
					$output .= "</tr>";
				}
		}

		return $this->_output = $output."</table></div>";

	}

	

	public function __toString(){
		return $this->_output;
	}

}

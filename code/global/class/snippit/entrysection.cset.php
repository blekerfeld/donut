<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pEntrySection extends pSnippit{

	private $_title, $_content, $_forceNoContent, $_informationElements = array();

	public function __construct($title, $content, $extra = true,  $forceNoContent = false){
		$this->_title = $title;
		if($extra)
			$this->_extraClass = 'extra';
		else
			$this->_extraClass = 'first';
		$this->_forceNoContent = $forceNoContent;
	}

	public function addInformationElement($information, $class ='', $link = null){
		$output = '<span class="'.$class.'">';
		if($link != null)
			$output .= '<a href="$link">';

		$output .= $information;
		if($link != null)
			$output .= '</a>';

		$output.= "</span>";

		return $this->_informationElements[] = $output;
	}

	public function __toString(){
		$output = "<span class='pSectionTitle $this->_extraClass'>$this->_title</span>";

		foreach($this->_informationElements as $informationElement)
			$output .= $informationElement;

		if(!$this->_forceNoContent)
			$output .= "<div class='pSectionWrapper'>$this->_content</div>";

		return $output;
	}
	
}
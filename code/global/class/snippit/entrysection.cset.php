<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pEntrySection extends pSnippit{

	private $_title, $_content, $_forceNoContent, $_informationElements = array(), $_icon;

	public function __construct($title, $content, $icon = null, $extra = true,  $forceNoContent = false, $sub = false){
		$this->_title = $title;
		$this->_icon = $icon;
		if($extra)
			$this->_extraClass = 'extra';
		else
			$this->_extraClass = 'first';

		if($sub)
			$this->_extraClass .= ' sub';

		$this->_forceNoContent = $forceNoContent;
		$this->_content = $content;
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
		$output = "<span class='pSectionTitle $this->_extraClass'>";

		if($this->_icon != null)
			$output .= new pIcon($this->_icon, 10)." ";

		$output .= $this->_title."<br />";

		foreach($this->_informationElements as $informationElement)
			$output .= "<em class='info'><span class='tooltip'>".$informationElement."</span></em> ";

		$output .= "</span>";

		if(!$this->_forceNoContent)
			$output .= "<div class='pSectionWrapper'>$this->_content</div>";
		else
			$output .= $this->_content;

		return $output;
	}
	
}
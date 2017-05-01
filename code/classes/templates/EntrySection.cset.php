<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: entrysection.cset.php

class pEntrySection extends pTemplatePiece{

	private $_title, $_content, $_forceNoContent, $_informationElements = array(), $_icon, $_closable;

	public function __construct($title, $content, $icon = null, $extra = true,  $forceNoContent = false, $sub = false, $closable = false){
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
		$this->_closable = $closable;
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

		$id = spl_object_hash($this);

		$output = "<div ".($this->_closable ? 'class="closable pointer" onClick="$(\'.title_'.$id.'\').toggleClass(\'closed\');$(\'.content_'.$id.'\').slideToggle(\'fast\');$(\'.hideIcon_'.$id.'\').toggle();$(\'.showIcon_'.$id.'\').toggle();"' : '')."><span class='pSectionTitle title_$id $this->_extraClass  ".($this->_closable ? 'closed' : '')."'>";

		if($this->_icon != null)
			$output .= new pIcon($this->_icon, 10)." ";

		if($this->_title != '')
			$output .= $this->_title;

		if($this->_closable)
			$output .= ' <span class="showIcon_'.$id.'">'.(new pIcon('fa-chevron-down', 12)).'</span><span class="hideIcon_'.$id.' hide">'.(new pIcon('fa-chevron-up', 12)).'</span>';

		if($this->_title != '')
			$output .= "<br />";

		foreach($this->_informationElements as $informationElement)
			$output .= "<em class='info'><span class='tooltip'>".$informationElement."</span></em> ";

		$output .= "</span>";

		if(!$this->_forceNoContent)
			$output .= "<div class='pSectionWrapper  ".($this->_closable ? 'hide content_'.$id : '')."'>$this->_content</div>";
		else
			$output .= $this->_content;

		$output .= "</div>";

		return $output;
	}
	
}
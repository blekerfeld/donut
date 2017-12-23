<?php
// Donut 0.11-dev - Thomas de Roo - Licensed under MIT
// file: entrysection.class.php

class pEntrySection extends pLayoutPart{

	private $_title, $_content, $_forceNoContent, $_informationElements = array(), $_icon, $_closable, $_iconsize;

	public function __construct($title, $content, $icon = null, $extra = true,  $forceNoContent = false, $sub = false, $closable = false, $_iconsize = 10){
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
		$this->_iconsize = $_iconsize;
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

		$id = sha1(spl_object_hash($this).rand(0,5));

		$output = "<div ".($this->_closable ? 'class="closable"' : '')."><span class='pSectionTitle title_$id $this->_extraClass  ".($this->_closable ? 'closed pointer' : '')."' ".($this->_closable ? 'onClick="$(\'.title_'.$id.'\').toggleClass(\'closed\');$(\'.content_'.$id.'\').slideToggle(\'fast\');$(\'.showIcon_'.$id.'\').toggle();$(\'.hideIcon_'.$id.'\').toggle();"' : '').">";

		if($this->_icon != null)
			$output .= new pIcon($this->_icon, 14)." ";

		if($this->_title != '')
			$output .= $this->_title;

		if($this->_closable)
			$output .= ' <span class="showIcon_'.$id.'">'.(new pIcon('chevron-down')).'</span>';

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
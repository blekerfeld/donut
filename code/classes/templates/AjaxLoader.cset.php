<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: AjaxLoader.cset.php

class pAjaxLoader extends pTemplatePiece{

	private $_querystring, $_refresh, $_loadOnClick, $_clickButton, $_wrapperclass, $_forceNoAjaxLink, $_allowClose;

	public function __construct($querystring, $refresh = false, $loadOnClick = false, $clickButton = null, $wrapperclass = '', $forceNoAjaxLink = false, $allow_close = false){
		$this->_querystring = $querystring;
		$this->_refresh = $refresh;
		$this-> _loadOnClick = $loadOnClick;
		$this->_clickButton = $clickButton;
		$this->_wrapperclass = $wrapperclass;
		$this->_forceNoAjaxLink = $forceNoAjaxLink;
		$this->_allowClose = $allow_close;
	}

	

	public function __toString(){

		$id = spl_object_hash($this);
		$output = (($this->_loadOnClick == false AND is_array($this->_clickButton)) ? '' : "<a class='ssignore click$id ".$this->_clickButton[0]."'>".$this->_clickButton[1]."</a><br />")."<div id='$id' class='$this->_wrapperclass ".(($this->_loadOnClick == true) ? 'hide ' : '')."'>".(new pIcon('fa-spinner fa-spin', 10))." ".LOADING."</div>".($this->_refresh ? "
			<a class='xsmall ajaxRefresh refresh$id' href='javascript:void(0);'>".(new pIcon('fa-refresh'))." Refresh</a>
		" : "");
		// Throwing this object's script into a session
		pRegister::session($id, $this->script($id));
		$output .= "<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$id)."'></script>";

		return $output;
	}

	protected function script($id){
		return 
			($this->_refresh ? "
			$('.refresh$id').click(function(){
				$('#$id').slideUp().load('".$this->_querystring.(($this->_forceNoAjaxLink == false) ? '/ajax' : "")."').slideDown();
			});
				" : "")."

			$('#$id').load('".$this->_querystring.(($this->_forceNoAjaxLink == false) ? '/ajax' : '' )."');
			$('.click$id').click(function(){
				$('#$id').slideDown();
			})"

			

			;
	}
	
}
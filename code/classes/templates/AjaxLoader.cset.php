<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: AjaxLoader.cset.php

class pAjaxLoader extends pTemplatePiece{

	private $_querystring, $_refresh;

	public function __construct($querystring, $refresh = false){
		$this->_querystring = $querystring;
		$this->_refresh = $refresh;
	}

	

	public function __toString(){

		$id = spl_object_hash($this);
		$output = "<div id='$id'>".(new pIcon('fa-spinner fa-spin', 10))." ".LOADING."</div>".($this->_refresh ? "
			<a class='xsmall ajaxRefresh refresh$id' href='javascript:void(0);'>".(new pIcon('fa-refresh'))." Refresh</a>
		" : "");
		// Throwing this object's script into a session
		pAdress::session($id, $this->script($id));
		$output .= "<script type='text/javascript' src='".p::Url('pol://library/assets/js/key.js.php?key='.$id)."'></script>";

		return $output;
	}

	protected function script($id){
		return 
			($this->_refresh ? "
			$('.refresh$id').click(function(){
				$('#$id').slideUp().load('".$this->_querystring."/ajax').slideDown();
			});
				" : "")	
			."

			$('#$id').load('".$this->_querystring."/ajax');
			";
	}
	
}

<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: HomeTemplate.cset.php

class pRegisterTemplate extends pSimpleTemplate{

	protected $_user;

	public function __construct(){
		
	}

	public function renderAll(){

	if(pUser::noGuest())
		return p::Url('?home', true);
		

	}

}
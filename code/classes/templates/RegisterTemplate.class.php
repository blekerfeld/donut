
<?php

// 	Donut: dictionary toolkit 
// 	version 0.1
// 	Thomas de Roo - MIT License
//	++	File: HomeTemplate.class.php

class pRegisterTemplate extends pSimpleTemplate{

	protected $_user;

	public function __construct(){
		
	}

	public function renderAll(){

	if(pUser::noGuest())
		return p::Url('?home', true);
		

	}

}

<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      HomeTemplate.class.php

class pRegisterTemplate extends pSimpleTemplate{

	protected $_user;

	public function __construct(){
		
	}

	public function renderAll(){

	if(pUser::noGuest())
		return p::Url('?home', true);
		

	}

}
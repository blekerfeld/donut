<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.cset.php

class pDocsStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){

		global $donut;

		if(isset(pAdress::arg()['section']))
			$this->_section = pAdress::arg()['section'];
		else
			$this->_section = $this->_default_section;

		$this->_template = new pLoginTemplate();

		pMainTemplate::setTitle($this->_page_title);

	}

	protected function logInAjax(){
		if(pUser::checkCre(pAdress::post()['username'], pAdress::post()['password'])){
			(new pUser)->login(pAdress::post()['username']);
			p::Out("<script>window.location = '".p::Url('?home')."';</script>");
		}
		else
			p::Out($this->_template->errorMessage());
	}

	protected function logOut(){
		(new pUser)->logOut();	
		return p::Url('?auth/login', true);
	}

	public function render(){

		if($this->_section == 'logout')
			return $this->logOut();

		if(isset(pAdress::arg()['ajax']))
			return $this->logInAjax();

		if(pUser::noGuest())
			return p::Url('?home', true);

		p::Out("<div class='home-margin'>".$this->_template->loginForm()."</div>");

	}

}
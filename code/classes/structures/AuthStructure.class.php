<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: admin.structure.class.php

class pAuthStructure extends pStructure{
	
	private $_ajax, $_section, $_template;

	public function compile(){

		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		$this->_template = new pLoginTemplate();

		pMainTemplate::setTitle($this->_page_title);

	}

	protected function logInAjax(){

		// Show a warning if things are empty
		if(isset(pRegister::post()['username'], pRegister::post()['password']) AND empty(pRegister::post()['username']) OR empty(pRegister::post()['password']))
			return p::Out($this->_template->warning());
		// Show some succes feedback, plus a redirect
		if(pUser::checkCre(pRegister::post()['username'], pRegister::post()['password'])){
			(new pUser)->logIn(pRegister::post()['username']);
			return p::Out($this->_template->succes()."<script>window.location = '".p::Url('?home')."';</script>");
		}
		// Show an error message
		else
			return p::Out($this->_template->errorMessage());
	}

	protected function logOut(){
		// Make an instance of the logged in user, log it out
		(new pUser)->logOut();	
		// Back to the login page
		return p::Url('?auth', true);
	}

	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!pUser::checkPermission($this->_permission))
			return p::Out("<div class='btCard minimal admin'>".pMainTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

		if($this->_section == 'profile'){
			p::Out("<div class='home-margin'>");
				$template = new pProfileTemplate;
				$template->renderAll();
			p::Out("</div>");
			return false;
		}

		if($this->_section == 'register'){
			p::Out("<div class='home-margin'>");
				$template = new pRegisterTemplate;
				$template->renderAll();
			p::Out("</div>");
			return false;
		}

		if($this->_section == 'logout')
			return $this->logOut();

		if(isset(pRegister::arg()['ajax']))
			return $this->logInAjax();

		if(pUser::noGuest())
			return p::Url('?home', true);

		p::Out("<div class='home-margin'>".$this->_template->loginForm()."</div>");

	}

}
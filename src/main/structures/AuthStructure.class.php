<?php
// Donut 0.12-dev - Emma de Roo - Licensed under MIT
// file: admin.structure.class.php

class pAuthStructure extends pStructure{
	
	private $_ajax, $_section, $_view;

	public function compile(){

		if(isset(pRegister::arg()['section']))
			$this->_section = pRegister::arg()['section'];
		else
			$this->_section = $this->_default_section;

		$this->_view = new pLoginView();

		pTemplate::setTitle($this->_page_title);

	}

	protected function registerAjax(){
		$error = false;
		if(isset(pRegister::post()['username'], pRegister::post()['password']) AND empty(pRegister::post()['username']) OR empty(pRegister::post()['password']) OR empty(pRegister::post()['password2']) OR empty(pRegister::post()['email'])){
			p::Out($this->_view->warningEmpty());
			$error = true;
		}
		
		if(pRegister::post()['password'] != pRegister::post()['password2']){
			p::Out($this->_view->warningPassword());
			$error = true;
		}	
		if(strlen(pRegister::post()['password']) < 8){
			p::Out($this->_view->warningPassword2());
			$error = true;
		}
		if(!(new pUser)->mailUnique(pRegister::post()['email'])){
			p::Out($this->_view->warningMail());
			$error = true;
		}
		if(!(new pUser)->usernameUnique(pRegister::post()['username'])){
			p::Out($this->_view->warningUsername());
			$error = true;
		}
		if($error == false){
			$data = pRegister::post();
			$userID = (new pDataModel('users'))->prepareForInsert(array((isset($data['fullname']) ? $data['fullname'] : ''), $data['username'], p::Hash($data['password']), date("Y-m-d H:i:s"), CONFIG_REGISTER_DEFAULT_ROLE, '', '0', '0', $data['email'], '0'))->insert();
			if(CONFIG_ENABLE_ACTIVATION_MAIL){	
				$user = new pUser($userID);
				(new pActivationMail(array($user->spew())))->send();
				p::Out($this->_view->succes());
			}else{
				if($CONFIG_REGISTER_ADMIN_ACTIVATION)
					p::Out($this->_view->succesActivate());
				else{
					(new pUser)->instantActivate($userID);
					p::Out($this->_view->succesActivated());
				}
			}
			p::Out("<script type='text/javascript'>$('.dotsc').delay(200).slideUp();$('.ajaxMessage').delay(20).slideDown();</script>");
		}else{
			p::Out("<script type='text/javascript'>$('.dotsc').delay(200).slideUp();$('.ajaxMessage').delay(20).slideDown();</script>");
			return;
		}
		
	}

	protected function logInAjax(){

		// Show a warning if things are empty
		if(isset(pRegister::post()['username'], pRegister::post()['password']) AND empty(pRegister::post()['username']) OR empty(pRegister::post()['password']))
			return p::Out($this->_view->warning());
		// Show some succes feedback, plus a redirect
		$doUserCheck = (new pUser)->checkCre(pRegister::post()['username'], pRegister::post()['password']);
		if($doUserCheck->rowCount() == 1){
			if($doUserCheck->fetchAll()[0]['activated'] != 0){
				if(!((new pUser)->logIn(pRegister::post()['username'])))
					p::Out("<span class='ajaxBan hide'>".pTemplate::NoticeBox('fa-warning', LOGIN_ERROR_BANNED, 'danger-notice')."</span><script>$('.loaddots').delay(1000).slideUp();$('.ajaxBan').delay(1000).slideDown();</script>");
				else
					return p::Out($this->_view->succes()."<script>window.location = '".p::Url('?home')."';</script>");
			}
			else{
				p::Out("<span class='ajaxBan hide'>".$this->_view->errorMessageNotActivated()."</span><script>$('.loaddots').delay(1000).slideUp();$('.ajaxBan').delay(1000).slideDown();</script>");
			}
			
		}
		// Show an error message
		else
			return p::Out($this->_view->errorMessage());
	}

	protected function logOut($header = true){
		// Make an instance of the logged in user, log it out
		(new pUser)->logOut();	
		// Back to the login page
		if($header)
			return p::Url('?home', true);
	}

	public function render(){

		// Since no parser is used, the permission check needs to be done here
		if(!(new pUser)->checkPermission($this->_permission))
			return p::Out("<div class='btCard minimal admin'>".pTemplate::NoticeBox('fa-info-circle fa-12', DA_PERMISSION_ERROR, 'danger-notice')."</div>");

		if($this->_section == 'profile'){
			p::Out("<div class='home-margin'>");
				$view = new pProfileView;
				$view->renderAll();
			p::Out("</div>");
			return false;
		}

		if($this->_section == 'banned'){
			return $this->logOut(false);
		}

		if($this->_section == 'activate' and isset(pRegister::arg()['token'])){
			if((new pUser)->activate(pRegister::arg()['token'], $_SERVER['REMOTE_ADDR']) != false)
				p::Out(pTemplate::NoticeBox('fa-check', AUTH_ACTIVATE_SUCCES, 'succes-notice'));
			else	
				p::Out(pTemplate::NoticeBox('fa-warning', AUTH_ACTIVATE_ERROR, 'danger-notice'));	
		}

		if($this->_section == 'register'){

			$this->_view = new pRegisterView;

			// Redirect if we can't register
			if(CONFIG_ENABLE_REGISTER == 0)
				return p::Url('?home', true);

			if((new pUser)->noGuest())
				return p::Url('?home', true);

			if(isset(pRegister::arg()['ajax']))
				return $this->registerAjax();

			p::Out("<div class='home-margin'>");
				
			$this->_view->renderAll();
	
			p::Out("</div>");
			return false;
		}

		

		if($this->_section == 'logout')
			return $this->logOut();

		if(isset(pRegister::arg()['ajax']))
			return $this->logInAjax();

		if((new pUser)->noGuest())
			return p::Url('?home', true);

		p::Out($this->_view->loginForm());

	}

}
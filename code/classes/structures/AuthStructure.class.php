<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      admin.structure.class.php

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

	protected function registerAjax(){
		$error = false;
		if(isset(pRegister::post()['username'], pRegister::post()['password']) AND empty(pRegister::post()['username']) OR empty(pRegister::post()['password']) OR empty(pRegister::post()['password2']) OR empty(pRegister::post()['email'])){
			p::Out($this->_template->warningEmpty());
			$error = true;
		}
		
		if(pRegister::post()['password'] != pRegister::post()['password2']){
			p::Out($this->_template->warningPassword());
			$error = true;
		}	
		if(strlen(pRegister::post()['password']) < 8){
			p::Out($this->_template->warningPassword2());
			$error = true;
		}
		if(!pUser::mailUnique(pRegister::post()['email'])){
			p::Out($this->_template->warningMail());
			$error = true;
		}
		if($error == false){
			$data = pRegister::post();
			$userID = (new pDataModel('users'))->prepareForInsert(array((isset($data['fullname']) ? $data['fullname'] : ''), $data['username'], p::Hash($data['password']), date("Y-m-d H:i:s"), CONFIG_REGISTER_DEFAULT_ROLE, '', '0', '0', $data['email'], '0'))->insert();
			if(CONFIG_ENABLE_ACTIVATION_MAIL){	
				$user = new pUser($userID);
				(new pActivationMail(array($user->spew())))->send();
				p::Out($this->_template->succes());
			}else{
				if($CONFIG_REGISTER_ADMIN_ACTIVATION)
					p::Out($this->_template->succesActivate());
				else{
					pUser::instantActivate($userID);
					p::Out($this->_template->succesActivated());
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
			return p::Out($this->_template->warning());
		// Show some succes feedback, plus a redirect
		$doUserCheck = pUser::checkCre(pRegister::post()['username'], pRegister::post()['password']);
		if($doUserCheck->rowCount() == 1){
			if($doUserCheck->fetchAll()[0]['activated'] != 0){
				if(!((new pUser)->logIn(pRegister::post()['username'])))
					p::Out("<span class='ajaxBan hide'>".pMainTemplate::NoticeBox('fa-warning', LOGIN_ERROR_BANNED, 'danger-notice')."</span><script>$('.loaddots').delay(1000).slideUp();$('.ajaxBan').delay(1000).slideDown();</script>");
				else
					return p::Out($this->_template->succes()."<script>window.location = '".p::Url('?home')."';</script>");
			}
			else{
				p::Out("<span class='ajaxBan hide'>".$this->_template->errorMessageNotActivated()."</span><script>$('.loaddots').delay(1000).slideUp();$('.ajaxBan').delay(1000).slideDown();</script>");
			}
			
		}
		// Show an error message
		else
			return p::Out($this->_template->errorMessage());
	}

	protected function logOut($header = true){
		// Make an instance of the logged in user, log it out
		(new pUser)->logOut();	
		// Back to the login page
		if($header)
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

		if($this->_section == 'banned'){
			return $this->logOut(false);
		}

		if($this->_section == 'activate' and isset(pRegister::arg()['token'])){
			if(pUser::activate(pRegister::arg()['token'], $_SERVER['REMOTE_ADDR']) != false)
				p::Out(pMainTemplate::NoticeBox('fa-check', AUTH_ACTIVATE_SUCCES, 'succes-notice'));
			else	
				p::Out(pMainTemplate::NoticeBox('fa-warning', AUTH_ACTIVATE_ERROR, 'danger-notice'));	
		}

		if($this->_section == 'register'){

			$this->_template = new pRegisterTemplate;

			// Redirect if we can't register
			if(CONFIG_ENABLE_REGISTER == 0)
				return p::Url('?home', true);

			if(pUser::noGuest())
				return p::Url('?home', true);

			if(isset(pRegister::arg()['ajax']))
				return $this->registerAjax();

			p::Out("<div class='home-margin'>");
				
			$this->_template->renderAll();
	
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
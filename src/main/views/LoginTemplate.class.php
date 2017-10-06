<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      ExampleView.class.php

//$structure, $icon, $surface, $table, $itemsperpage, $dfs, $actions, $actionbar, $paginated, $section, $app = 'dictionary-admin')

class pLoginView extends pView{

	public function loginForm(){
		$output = '';
		$output .= "<div class='btCard admin ".(isset(pRegister::arg()['ajaxLoad']) ? 'no-padding' : '')."' style=''>
		".(!isset(pRegister::arg()['ajaxLoad']) ? "<div class='btTitle'>".(new pIcon('account-key'))." ".LOGIN_TITLE."</div>" : "")."
		<div class='saving hide loaddots'>".pTemplate::loadDots()."</div>
		<div class='btForm'>
		<div class='ajaxChecking'></div>
		<div class='btSource'><span class='btLanguage'>".LOGIN_USERNAME." <span class='xsmall darkred'>*</span></span><br />
			<span class='btNative'><input class='btInput nWord medium  normal-font username' /></span></div>
		<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD."<span class='xsmall darkred'>*</span></span><br />
			<span class='btNative'><input class='btInput nWord medium  normal-font password' type='password'/></span></div>
		</div>
		<div class='btButtonBar'>
			".(CONFIG_ENABLE_REGISTER ? "<a href='".p::Url('?auth/register')."' class='btAction medium blue no-float not-smooth'>".(new pIcon('account-edit', 12))." ".LOGIN_REGISTER_SHORT."</a>" : '')."
			<a class='btAction medium green not-smooth login-button'>".(new pIcon('fa-sign-in', 12))." ".LOGIN_TITLE_SHORT."</a><br /><br id='cl' />
		</div>
		</div>
		";

		$output.= "<script type='text/javascript'>
		$('.saving').hide();
		$(window).keydown(function(e) {
			    		switch (e.keyCode) {
			       		 case 13:
			       		 	if($('.password').val() != ''){
			       				 $('.login-button').click();
			       		 	}
			   			 }
			   		 return; 
					});
				$('.login-button').click(function(){
					$('.saving').slideDown();
					$('.ajaxMessage').slideUp();
					$('.ajaxChecking').delay(1000).load('".p::Url("?auth/login/ajax")."', {
						'username': $('.username').val(),
						'password': $('.password').val(),
					});
				});
			</script>";
		return $output;
	}

	public function succes(){
		return ;
	}

	public function errorMessage(){
		return pTemplate::NoticeBox('fa-warning fa-12', LOGIN_ERROR, 'danger-notice ajaxMessage')."<script type='text/javascript'>$('.saving').delay(1000).slideUp();$('.ajaxMessage').delay(2000).slideDown();</script>";
	}

	public function errorMessageNotActivated(){
		return pTemplate::NoticeBox('fa-warning fa-12', LOGIN_ERROR_ACTIVATED, 'danger-notice ajaxMessage')."<script type='text/javascript'>$('.saving').delay(1000).slideUp();$('.ajaxMessage').delay(2000).slideDown();</script>";
	}


	public function warning(){
		return pTemplate::NoticeBox('fa-info-circle fa-12', SAVED_EMPTY, 'warning-notice ajaxMessage')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}




}
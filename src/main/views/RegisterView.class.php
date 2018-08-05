<?php
// Donut 0.12-dev - Thomas de Roo - Licensed under MIT
// file: HomeView.class.php

class pRegisterView extends pSimpleView{

	protected $_user;

	public function __construct(){
		
	}

	public function renderAll(){

		// Let's make a nice form! :D 
		p::Out("<div class='btCard admin' style='width: 800px;'>
					<div class='btRegister_inner'>
						<div class='btTitle'>".(new pIcon('account-edit'))." ".AUTH_REGISTER_TITLE."</div>
						<div class='btForm'>
						<div class='dotsc hide'>".pTemplate::loadDots()."</div>	
						<div class='ajaxLoad'></div>			
						<div class='btSource'><span class='btLanguage'>".LOGIN_USERNAME.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font username' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font password' type='password' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD_REPEAT.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font password2' type='password' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_MAIL.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord medium  normal-font email' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_FULLNAME.": </span><br />
						<span class='btNative'><input class='btInput nWord medium normal-font fullname' /></span></div>
						</div>
						<div class='btButtonBar'>
						<a  class='btAction medium green no-float not-smooth register-button'>".(new pIcon('account-edit', 12))." ".LOGIN_REGISTER_SHORT."</a> ".STR_OR." <a href='".p::Url('?auth/login')."'>".LOGIN_TITLE_IMP."</a>
			
		</div>
					</div>
		</div><script type='text/javascript'>
		$('.saving').hide();
		$('.register-button').click(function(){
			$('.dotsc').slideDown();
			$('.ajaxMessage').slideUp();
			$('.ajaxLoad').delay(1000).load('".p::Url("?auth/register/ajax")."', {
					'username': $('.username').val(),
					'password': $('.password').val(),
					'password2': $('.password2').val(),
					'email': $('.email').val(),
					'fullname': $('.fullname').val(),
				});
			});
		</script>");
	}

	public function succes(){
		return pTemplate::NoticeBox('fa-check fa-12', AUTH_REGISTER_ACTIVATE1, 'succes-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningEmpty(){
		return pTemplate::NoticeBox('fa-info-circle fa-12', AUTH_REGISTER_EMPTY, 'warning-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningPassword(){
		return pTemplate::NoticeBox('fa-warning fa-12', AUTH_REGISTER_PASSWORD, 'danger-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningUsername(){
		return pTemplate::NoticeBox('fa-warning fa-12', AUTH_REGISTER_USERNAME, 'danger-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningPassword2(){
		return pTemplate::NoticeBox('fa-warning fa-12', AUTH_REGISTER_PASSWORD2, 'danger-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningMail(){
		return pTemplate::NoticeBox('fa-warning fa-12', AUTH_REGISTER_MAIL, 'danger-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}

}
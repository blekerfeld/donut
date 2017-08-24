
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

		// Let's make a nice form! :D 
		p::Out("<div class='btCard admin' style='width: 800px;'>
					<div class='btRegister_inner'>
						<div class='btTitle'>".(new pIcon('account-edit'))." ".AUTH_REGISTER_TITLE."</div>
						<div class='dotsc hide'>".pMainTemplate::loadDots()."</div>
						<div class='ajaxLoad'></div>						
						<div class='btSource'><span class='btLanguage'>".LOGIN_USERNAME.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord small normal-font username' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord small normal-font password' type='password' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_PASSWORD_REPEAT.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord small normal-font password2' type='password' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_MAIL.": <span class='xsmall darkred'>*</span></span><br />
						<span class='btNative'><input class='btInput nWord small normal-font email' /></span></div>
						<div class='btSource'><span class='btLanguage'>".LOGIN_FULLNAME.": </span><br />
						<span class='btNative'><input class='btInput nWord small normal-font name' /></span></div><br />
						<div class='btButtonBar'>
						<a  class='btAction medium green no-float not-smooth register-button'>".(new pIcon('account-edit', 12))." ".LOGIN_REGISTER_SHORT."</a>
			
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
		return pMainTemplate::NoticeBox('fa-check fa-12', AUTH_REGISTER_ACTIVATE1, 'succes-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningEmpty(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', AUTH_REGISTER_EMPTY, 'warning-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningPassword(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', AUTH_REGISTER_PASSWORD, 'warning-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningPassword2(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', AUTH_REGISTER_PASSWORD2, 'warning-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}
	public function warningMail(){
		return pMainTemplate::NoticeBox('fa-info-circle fa-12', AUTH_REGISTER_MAIL, 'warning-notice ajaxMessage hide')."<script type='text/javascript'>$('.saving').slideUp();$('.ajaxMessage').slideDown();</script>";
	}

}
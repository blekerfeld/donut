<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under MIT
	File: index.home.php
*/


	if(isset($_REQUEST['ajax'])){
		if($_REQUEST['password'] == "" or $_REQUEST['username'] == "")
		{
			echo '<div class="notice hide danger-notice" id="empty"><i class="fa fa-warning"></i> '.LOGIN_FIELDS.'</div>';
			echo "<script>$('#busy').fadeOut();$('#empty').show().pulsate({color: $('#empty').css('color'), repeat: 2, glow: false, speed: 500});$('.tbllogin').delay(500).slideDown(1500);</script>";
		}
		else
		{
			$id = pUsernameToID($_REQUEST['username']);
			if(pMemberExists($id) and pPasswordCheck($id, $_REQUEST['password']))
			{
				echo '<div class="notice hide succes-notice" id="empty"><i class="fa fa-check"></i> '.LOGIN_SUCCESS.'</div>';
				echo "<script>$('#busy').fadeOut();$('#empty').delay(401).slideDown();</script>
				<script>	
						setTimeout(function() {
									window.location = '".pUrl('')."';
								}, 1500);
				</script>";
				$arr = array($id, pHash($_REQUEST['password'], pRegDate($id)), $_REQUEST['username']);
				setcookie('pKeepLogged', serialize($arr), time()+5*52*7*24*3600);
				$_SESSION['pUser'] = $id;

			}
			else
			{
				echo '<div class="notice hide danger-notice" id="empty"><i class="fa fa-warning"></i> '.LOGIN_WRONG.'</div>';
				echo "<script>$('#busy').fadeOut();$('#empty').show().pulsate({color: $('#empty').css('color'), repeat: 2, glow: false, speed: 500});$('.tbllogin').delay(500).slideDown(1500);</script>";
			}
		}
	}


	if(pLogged() and !isset($_REQUEST['logout']) and !(isset($_GET['ajax']) and !(isset($_GET['ajax_pOut']))))
	{
		pUrl('', true);
	}
	elseif(pUser::noGuest() and isset($_REQUEST['logout']))
	{
		pUser::logOut();	
		pUrl('?login', true);
	}

	if(!isset($_REQUEST['ajax'])){

		pOut('<span class="title_header">'.LOGIN_TITLE.'</span><br /><br />', true);

		 pOut('<div class="title" style="margin: 1px auto;width: 75%;"><div class="icon-box throw"><i class="fa fa-sign-in"></i></div> '.LOGIN_TITLE_SHORT.'</div><br />
	       <div style="width: 75%;margin: 0 auto;"><div class="notice hide" id="busy"><i class="fa fa-spinner fa-spin"></i> '.LOGIN_CHECKING.'</div></div>
	       <div style="width: 75%;margin: 0 auto;"><div class="loginload"></div></div>
	       <table style="width: 75%;" class="login tbllogin">
	                          <tbody><tr id="usernamefadeout" style="display: table-row;">
	                            <td>
	                               <i class="fa-12 fa-user"></i> '.LOGIN_USERNAME.': 
	                            </td>
	                            </tr><tr>
	                            <td>
	                              <input type="text" id="username" name="username" value="">
	                            </td>
	                          </tr>
	                          <tr>
	                            <td>
	                              <i class="fa-12 fa-lock"></i> '.LOGIN_PASSWORD.': 
	                            </td>
	                           </tr>
	                           <tr>
	                            <td>
	                              <input type="password" id="password" name="password">
	                            </td>
	                          </tr>
	                          <tr>
	                            <td></td>
	                            <td>
	                              <a class="button fetch float-right" id="loginstart" href="javascript:void(0);"><i class="fa-12 fa-sign-in"></i> '.LOGIN_PROCEED.'</a>
	                            </td>
	                          </tr>
	                        </tbody></table>');
		pOut("<script>
				$(window).keydown(function(e) {
				    switch (e.keyCode) {
				        case 13:
				        $('#loginstart').click();
				    }
				    return; 
				});
				$('#loginstart').click(function(){
					$('#busy').fadeIn();
					$('#empty').hide();
					$('.tbllogin').hide();
					$('.loginload').load('".pUrl('?login&ajax')."', {'username': $('#username').val(),  'password': $('#password').val()})
				});
			</script>");
}


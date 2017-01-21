<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: index.home.php
*/


	if(isset($_REQUEST['ajax'])){
		if($_REQUEST['password'] == "" or $_REQUEST['username'] == "")
		{
			echo '<div class="notice hide danger-notice" id="empty"><i class="fa fa-warning"></i> '.LOGIN_FIELDS.'</div>';
			echo "<script>$('#busy').fadeOut();$('#empty').show().delay(400).effect('bounce', {duration: 1000});$('.tbllogin').delay(500).effect('slide')</script>";
		}
		else
		{
			$id = polUsernameToID($_REQUEST['username']);
			if(polMemberExists($id) and polPasswordCheck($id, $_REQUEST['password']))
			{
				echo '<div class="notice hide succes-notice" id="empty"><i class="fa fa-check"></i> '.LOGIN_SUCCESS.'</div>';
				echo "<script>$('#busy').fadeOut();$('#empty').delay(401).effect('slide');</script>
				<script>	
						setTimeout(function() {
									window.location = '".pUrl('')."';
								}, 3000);
				</script>";
				$arr = array($id, polHash($_REQUEST['password'], polRegDate($id)), $_REQUEST['username']);
				setcookie('lgnkeep', serialize($arr), time()+5*52*7*24*3600);
				$_SESSION['pol_user'] = $id;

			}
			else
			{
				echo '<div class="notice hide danger-notice" id="empty"><i class="fa fa-warning"></i> '.LOGIN_WRONG.'</div>';
				echo "<script>$('#busy').fadeOut();$('#empty').show().delay(200).effect('bounce', {duration: 1000});$('.tbllogin').delay(500).effect('slide')</script>";
			}
		}
	}


	if(logged() and !isset($_REQUEST['logout']) and !(isset($_GET['ajax']) and !(isset($_GET['ajax_pOut']))))
	{
		pUrl('', true);
	}
	elseif(logged() and isset($_REQUEST['logout']))
	{
		if(isset($_SESSION['pol_user']))
		{
			# Now logout
			unset($_SESSION['pol_user']);
			if(isset($_COOKIE['lgnkeep']))
			{
				setcookie('lgnkeep', '', time() - 60000);
			}
			if(isset($_GET['ajax']) OR isset($_GET['ajax_pOut']))
				echo "<script>window.location = '".pUrl('?login')."';
					window.history.pushState('', '', '".pUrl('?login')."');
					<script>";
			else
				pUrl('?login', true);
		}
		else
		{
			# Not even logged in, log in first
			fUrl('?login', true);
		}
	}

	pOut('<span class="title_header">'.LOGIN_TITLE.'</span><br /><br />', true);

	 pOut('<div class="title" style="margin: 1px auto;width: 75%;"><div class="icon-box throw"><i class="fa fa-sign-in"></i></div> '.LOGIN_TITLE_SHORT.'</div><br />
       <div style="width: 75%;margin: 0 auto;"><div class="notice hide" id="busy"><i class="fa fa-spinner fa-spin"></i> '.LOGIN_CHECKING.'</div></div>
       <div style="width: 75%;margin: 0 auto;"><div class="ajaxload"></div></div>
       <table style="width: 75%;" class="login tbllogin">
                          <tbody><tr id="usernamefadeout" style="display: table-row;">
                            <td style="width: 90px;">
                               <i class="fa-12 fa-user"></i> '.LOGIN_USERNAME.': 
                            </td>
                            <td>
                              <input type="text" id="username" name="username" value="">
                            </td>
                          </tr>
                          <tr>
                            <td style="width: 90px;">
                              <i class="fa-12 fa-lock"></i> '.LOGIN_PASSWORD.': 
                            </td>
                            <td>
                              <input type="password" id="password" name="password">
                            </td>
                          </tr>
                          <tr>
                            <td></td>
                            <td>
                              <a class="button fetch floatright" id="loginstart" href="javascript:void(0);"><i class="fa-12 fa-sign-in"></i> '.LOGIN_PROCEED.'</a>
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
				$('.tbllogin').effect('drop');
				$('.ajaxload').load('".pUrl('?login&ajax')."', {'username': $('#username').val(),  'password': $('#password').val()})
			});
		</script>");

 ?>
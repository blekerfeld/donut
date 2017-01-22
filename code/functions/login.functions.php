<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: login.functions.php
*/

	function pLogged()
	{
		return isset($_SESSION['pUser']);
	}


	function pCheckAdmin($id = 0){
		return pLogged();
	}

	function pMemberExists($id)
	{
		global $donut;
		$result = $donut['db']->query("SELECT * FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}

	function pPasswordCheck($userid, $password)
	{
		global $donut;
		$password = polHash($password);
		$result = $donut['db']->query("SELECT * FROM users WHERE id = '$userid' and password = '$password' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}
	


	function pUsernameToID($username)
	{
		global $donut;
		$result = $donut['db']->query("SELECT id FROM users WHERE username = '$username' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$user = $result->fetchObject();
			return $user->id;
		}
		else
			return false;
	}

	function pUserName($id)
	{
		global $donut;
		$result = $donut['db']->query("SELECT username FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$user = $result->fetchObject();
			return $user->username;
		}
		else
			return false;
	}

	function pEditorLanguage($id)
	{
		global $donut;
		$result = $donut['db']->query("SELECT editor_lang FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$user = $result->fetchObject();
			return $user->editor_lang;
		}
		else
			return false;
	}

	function polRegDate($id)
	{
		return NULL;
	}


	function pLogonRestore()
	{
		global $donut;
		if(isset($_COOKIE['pKeepLogged']))
		{
			try
			{
				$arr = unserialize($_COOKIE['pKeepLogged']);
				$result = $donut['db']->query("SELECT * FROM users WHERE username = '{$arr[2]}' and password = '{$arr[1]}' and id = {$arr[0]} LIMIT 1;");
				if($result->rowCount() == 1)
				{
					$_SESSION['pUser'] = $arr[0];
				}
				else
				{
					unset($_SESSION['pUser']);
					setcookie('pKeepLogged', '', time() - 3600);
				}
			}
			catch(Exception $e)
			{
				// Coming log feature.
			}
		}
	}

 ?>
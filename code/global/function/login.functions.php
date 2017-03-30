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

	function pUser(){
		return $_SESSION['pUser'];
	}

	function pCheckAdmin($id = 0){
		return pLogged();
	}


	function pCheckRole($id, $minus = 3){
		$role = pGetUserRole($id);
		return ($role == (3 - $minus) OR $role < (3 - $minus));
	}


	function pMemberExists($id)
	{
		global $donut;
		$result = pQuery("SELECT * FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}

	function pPasswordCheck($userid, $password)
	{
		global $donut;
		$password = pHash($password);
		$result = pQuery("SELECT * FROM users WHERE id = '$userid' and password = '$password' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}
	


	function pUsernameToID($username)
	{
		global $donut;
		$result = pQuery("SELECT id FROM users WHERE username = '$username' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$user = $result->fetchObject();
			return $user->id;
		}
		else
			return false;
	}

	function pGetUser($id = 0){
		return pQuery("SELECT * FROM users WHERE id = ".(($id == 0) ? pUser() : $id)." LIMIT 1")->fetchObject();
	}

	function pUserName($id)
	{
		$result = pQuery("SELECT username FROM users WHERE id = '$id' LIMIT 1;");
		return $result->fetchObject()->username;
	}

	function pEditorLanguage($id)
	{
		$result = pQuery("SELECT editor_lang FROM users WHERE id = '$id' LIMIT 1;");
		return $result->fetchObject()->editor_lang;
	}

	function pGetUserRole($id)
	{
		$result = pQuery("SELECT role FROM users WHERE id = '$id' LIMIT 1;");
		return $result->fetchObject()->role;
	}


	function pRegDate($id)
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
				$result = pQuery("SELECT * FROM users WHERE username = '{$arr[2]}' and password = '{$arr[1]}' and id = {$arr[0]} LIMIT 1;");
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
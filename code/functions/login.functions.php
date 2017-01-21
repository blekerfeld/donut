<?php
/* 
	Donut
	Dictionary Toolkit
	Version a.1
	Written by Thomas de Roo
	Licensed under GNUv3
	File: login.functions.php
*/

	function logged()
	{
		return isset($_SESSION['pol_user']);
	}

	// @ AlIAS
	function pLogged(){
		return logged();
	}

	function checkAdmin($id = 0){
	/*
		if(file_exists('../pbb-config.php'))
			require_once('../pbb-config.php');
		elseif(file_exists('pbb-config.php'))
			require_once('pbb-config.php');
		elseif(file_exists('forum/pbb-config.php'))
			require_once('forum/pbb-config.php');
		global $db;
		$q = "SELECT * FROM par_users WHERE id = $id";
		$row = mysql_fetch_row(mysql_query($q));
		$am = $row[9];
		if($am == "admin"){
			return true;
		}
		else
		{
			return false;
		}*/
		return logged();
	}

	function polMemberExists($id)
	{
		global $pol;
		$result = $pol['db']->query("SELECT * FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}

	function polPasswordCheck($userid, $password)
	{
		global $pol;
		$password = polHash($password);
		$result = $pol['db']->query("SELECT * FROM users WHERE id = '$userid' and password = '$password' LIMIT 1;");
		if($result->rowCount() == 1)
			return true;
		else
			return false;
	}
	


	function polUsernameToID($username)
	{
		global $pol;
		$result = $pol['db']->query("SELECT id FROM users WHERE username = '$username' LIMIT 1;");
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
		global $pol;
		$result = $pol['db']->query("SELECT username FROM users WHERE id = '$id' LIMIT 1;");
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
		global $pol;
		$result = $pol['db']->query("SELECT editor_lang FROM users WHERE id = '$id' LIMIT 1;");
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
		/*global $pol;
		$result = $pol['db']->query("SELECT registerdate FROM users WHERE id = '$id' LIMIT 1;");
		if($result->rowCount() == 1)
		{
			$user = $result->fetchObject();
			return $user->registerdate;
		}
		else
			return false;*/
	}

	function polLogonRestore()
	{
		global $pol;
		if(isset($_COOKIE['lgnkeep']))
		{
			try
			{
				$arr = unserialize($_COOKIE['lgnkeep']);
				$result = $pol['db']->query("SELECT * FROM users WHERE username = '{$arr[2]}' and password = '{$arr[1]}' and id = {$arr[0]} LIMIT 1;");
				if($result->rowCount() == 1)
				{
					$_SESSION['pol_user'] = $arr[0];
				}
				else
				{
					unset($_SESSION['pol_user']);
					setcookie('lgnkeep', '', time() - 3600);
				}
			}
			catch(Exception $e)
			{
				// Coming log feature.
			}
		}
	}

 ?>
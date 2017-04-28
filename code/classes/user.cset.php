<?php

	// 	Donut 				🍩 
	//	Dictionary Toolkit
	// 		Version a.1
	//		Written by Thomas de Roo
	//		Licensed under MIT

	//	++	File: user.cset.php

class pUser{

	public static $id, $user, $dataObject, $static = true;


	// This would work only with 'new pUser'
	public function __toString(){
		return self::$id;
	}

	public function __construct($id = NULL){
		self::$dataObject = new pDataObject('users');

		// Overwrite the loaded user
		if(isset($id)){
			self::$dataObject->getSingleObject($id);
			return self::load(self::$dataObject->data()->fetchAll()[0]);
		}
		
	}

	public function __destruct(){
		self::restore();
	}

	private function load($data){
		self::$id = $data['id'];
		self::$user = $data; 
		if(!isset($_SESSION['pUserData']))
			$_SESSION['pUserData'] = serialize($data);
	}

	public function checkPermission($minus){
		return (self::$user['role'] == (4 + $minus) OR self::$user['role']< (4 + $minus));
	}

	public static function logIn($id){
		$_SESSION['pUser'] = $id;
		self::$dataObject->getSingleObject($id);
	}

	public static function logOut(){
		unset($_SESSION['pUser']);
		return setcookie('pKeepLogged', '', time() - 3600);
	}

	// This will return false if we are logged in as a guest
	public static function noGuest(){
		return (self::$id != 0);
	}

	public static function restore(){


		// Creating a connection to the user table
		if(self::$dataObject == null)
			self::$dataObject = new pDataObject('users');


		if(isset($_SESSION['pUser']) AND self::$id != $_SESSION['pUser']){
			self::$dataObject->getSingleObject($_SESSION['pUser']);
			return self::load(self::$dataObject->data()->fetchAll()[0]);
		}

		if(isset($_COOKIE['pKeepLogged']))
			{
				try
				{
					$userInfo = unserialize($_COOKIE['pKeepLogged']);

					// Creating the dataobject, for checking if the cookie-obtained info is still valid.
					
					self::$dataObject->setCondition("WHERE username = '{$userInfo[2]}' and password = '{$userInfo[1]}' and id = {$userInfo[0]}");
					self::$dataObject->getSingleObject($userInfo[0]);

					if(self::$dataObject->data()->rowCount() == 1){
						if(!isset($_SESSION['pUser'])){
							self::load(self::$dataObject->data()->fetchAll()[0]);
							return $_SESSION['pUser'] = $userInfo[0];
						}
						 return true;

					}

					// The data is not found that way, that means we have to loggout
						self::logOut();

				}
				catch(Exception $e)
				{
						// Coming log feature.
				}
			}

			// If nothing happend then we are a guest and we have to log in to the guest account
			self::$dataObject->getSingleObject(0);
			return self::load(self::$dataObject->data()->fetchAll()[0]);

	}


	// This will read out the given field
	public function read($key){
		if(array_key_exists($key, self::$user))
			return self::$user[$key];
		return false;
	}

	/*
		The setting functions
	*/ 

	public function changeEditorLang($lang_id){
		return self::$dataObject->changeField(self::$id, (new pDataField('editor_lang')), $lang_id, self::$user['editor_lang']);
	}

	// This function also contains information on how the username needs to look
	public function changeUserName($newUserName){

		// No Short usernames please
		if(strlen($newUserName) < 4)
			return false;

		// First we need to check if this username does not already exist;
		self::$dataObject->setCondition("WHERE username = '{$newUserName}'");
		if(self::$dataObject->getObjects()->rowCount() != 0)
			return false;

		// Now it is time to change the shit out of it
		return self::$dataObject->changeField(self::$id, (new pDataField('username')), $newUserName, self::$user['username']);

	}

	private function checkStrengthPassword($password){
		return (strlen($password) < 8 OR preg_match("#[0-9]+#", $password) OR preg_match("#[a-zA-Z]+#", $password));
	}

	public function changePassword($password){

		// No unsafe passwords please
		if(!self::checkStrengthPassword($password))
			return false;

		// Now it is time to change the shit out of it
		return self::$dataObject->changeField(self::$id, (new pDataField('password')), pHash($password));

	}

	public function giveRole($minus){
		return self::$dataObject->changeField(self::$id, (new pDataField('role')), 4 + $minus);
	}
	

}
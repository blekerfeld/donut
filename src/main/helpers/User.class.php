<?php
// Donut: open source dictionary toolkit
// version    0.11-dev
// author     Thomas de Roo
// license    MIT
// file:      user.class.php

class pUser{

	public static $id, $user, $dataModel, $static = true;


	// This would work only with 'new pUser'
	public function __toString(){
		return self::$id;
	}

	public function __construct($id = NULL){
		self::$dataModel = new pDataModel('users');

		// Overwrite the loaded user
		if(isset($id)){
			self::$dataModel->getSingleObject($id);
			if(array_key_exists(0, self::$dataModel->data()->fetchAll()))
				return self::load(self::$dataModel->data()->fetchAll()[0]);
			else
				throw new Exception("Error: user could not be loaded", 1);
		}else{
			self::restore();
		}
		
	}

	public static function link(){
		return "<a href='".p::Url('?auth/profile/').self::$id."'>".(self::read('longname') != '' ? self::read('longname') : self::read('username'))."</a>";
	}

	public function __destruct(){
		self::restore();
	}

	private static function load($data){
		self::$id = $data['id'];
		self::$user = $data; 
		if(!isset($_SESSION['pUserData']))
			$_SESSION['pUserData'] = serialize($data);
	}

	public function checkPermission($minus){
		if(isset(self::$user))
			return (self::$user['role'] == (4 + $minus) OR self::$user['role']< (4 + $minus));
		else
			return ((4 + $minus) == 4 OR (4 + $minus) < 4);
	}

	// This function assumes the user in question exists, it returns false only if the user is banned.
	public static function logIn($id){
		if(!is_numeric($id)){
			self::$dataModel->setCondition(" WHERE username = '$id' ");
			self::$dataModel->getObjects();
			$id = self::$dataModel->data()->fetchAll()[0]['id'];
			self::$dataModel->setCondition('');
		}
		$_SESSION['pUser'] = $id;
		self::$dataModel->setCondition('');
		self::$dataModel->getSingleObject($id);
		self::load(self::$dataModel->data()->fetchAll()[0]);
		$arr = array($id, self::read('password'), self::read('username'));
		setcookie('pKeepLogged', serialize($arr), time()+5*52*7*24*3600, '/'.CONFIG_FOLDER);
		if(self::read('might_be_banned') == 1 AND self::checkBanned()){
			self::logOut();
			return false;
		}
		return true;
	}

	public static function logOut(){
		unset($_SESSION['pUser']);
		unset($_SESSION['pUserData']);
		setcookie('pKeepLogged', $_COOKIE['pKeepLogged'], time()-3600, '/'.CONFIG_FOLDER);
		self::logIn(0);
	}

	// This will return false if we are logged in as a guest
	public static function noGuest(){
		return (self::$id != 0);
	}

	public static function restore(){

		// Creating a connection to the user table
		if(self::$dataModel == null)
			self::$dataModel = new pDataModel('users');


		if(!isset($_COOKIE['pKeepLogged']))
			if(isset($_SESSION['pUser'])){
				self::$dataModel->getSingleObject($_SESSION['pUser']);
				return self::load(self::$dataModel->data()->fetchAll()[0]);
			}

		if(isset($_COOKIE['pKeepLogged']))
			{
				try
				{
					$userInfo = unserialize($_COOKIE['pKeepLogged']);
	
					// Creating the datamodel, for checking if the cookie-obtained info is still valid.
					
					self::$dataModel->setCondition("WHERE username = '{$userInfo[2]}' and password = '{$userInfo[1]}' and id = {$userInfo[0]}");
					self::$dataModel->getSingleObject($userInfo[0]);


					if(self::$dataModel->data()->rowCount() == 1){
						self::load(self::$dataModel->data()->fetchAll()[0]);
						if(self::read('might_be_banned') == 1 AND self::checkBanned()){
							self::showBanWarning();
							return self::logOut();
						}
						return $_SESSION['pUser'] = $userInfo[0];
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
			self::$dataModel->setCondition("");
			self::$dataModel->getSingleObject(0);
			return self::load(self::$dataModel->data()->fetchAll()[0]);

	}


	// This will read out the given field
	public function read($key){
		if(array_key_exists($key, self::$user))
			return self::$user[$key];
		return false;
	}

	public function spew(){
		return self::$user;
	}

	// This will read out the given field
	public function avatar(){
		if(self::read('avatar') != '')
			return p::Url(self::read('avatar'));
		elseif(self::read('id')  == 0)
			return p::Url("pol://library/staticimages/guest.png");
		else
			return p::Url("pol://library/staticimages/default_avatar.png");
		return false;
	}

	/*
		The setting functions
	*/ 

	// This function also contains information on how the username needs to look
	public function changeUserName($newUserName){

		// No Short usernames please
		if(strlen($newUserName) < 4)
			return false;

		// First we need to check if this username does not already exist;
		self::$dataModel->setCondition("WHERE username = '{$newUserName}'");
		if(self::$dataModel->getObjects()->rowCount() != 0)
			return false;

		// Now it is time to change the shit out of it
		return self::$dataModel->changeField(self::$id, (new pDataField('username')), $newUserName, self::$user['username']);

	}

	public static function activate($token, $ip){
		$getToken = (new pDataModel('user_activation'))->setCondition(" WHERE token = ".p::Quote($token)." AND ipadress = ".p::Quote($ip)." AND untill > NOW() ")->setLimit("1")->getObjects();
		if($getToken->rowCount() == 0)
			return false;
		else
			self::$dataModel->complexQuery("UPDATE users SET activated = 1 WHERE id = ".$getToken->fetchAll()[0]['user_id']."; DELETE FROM user_activation WHERE user_id = ".$getToken->fetchAll()[0]['user_id'].";");
		return true;
	}

	public function instantActivate($id){
		self::$dataModel->complexQuery("UPDATE users SET activated = 1 WHERE id = $id");
	}

	public function checkStrengthPassword($password){
		return ( (strlen($password) < 8) OR (preg_match("#[0-9]+#", $password)) OR (preg_match("#[a-zA-Z]+#", $password)));
	}

	public function changePassword($password){

		// No unsafe passwords please
		if(!self::checkStrengthPassword($password))
			return false;

		// Now it is time to change the shit out of it
		return self::$dataModel->changeField(self::$id, (new pDataField('password')), p::Hash($password));

	}

	public function giveRole($minus){
		return self::$dataModel->changeField(self::$id, (new pDataField('role')), 4 + $minus);
	}

	public function checkBanned(){
		return (new pDataModel('bans'))->setCondition(" WHERE user_id = ".self::$id." AND untill > NOW() ")->getObjects()->rowCount() == 1;
	}

	public function mailUnique($mail){
		return (new pDataModel('users'))->setCondition(" WHERE email = ".p::Quote($mail)." ")->getObjects()->rowCount() == 0;
	}

	public function usernameUnique($username){
		return (new pDataModel('users'))->setCondition(" WHERE username = ".p::Quote($username)." ")->getObjects()->rowCount() == 0;
	}

	public function checkCre($username, $password){
		self::$dataModel->setCondition(" WHERE username = '".$username."' AND password = '".p::Hash($password)."'");
		return self::$dataModel->getObjects();
	}
	
	
}


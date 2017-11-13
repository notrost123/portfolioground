<?php

class User{
	private $_db, 
			$_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;


	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);

				if($this->find($user)){
					$this->_isLoggedIn = true;
				} else {
					//process Logout
				}
			}
		} else {
			$this->find($user);
		}
		
	} //construct

	public function create($fields = array()){

		if(!$this->_db->insert('Users', $fields)){
			throw new Exception('There was a problem on creating an account.'. var_dump($this->_db->getErrorInfo()));
		}





	} //create

	public function find($user = null){
	
		if($user){
			$field = (is_numeric($user)) ? 'Autokey' : 'UserName';
			$data = $this->_db->select('Users', array($field, '=', $user));

			if($data->count()){
				$this->_data = $data->first();
				// echo $this->_data->UserName. " ---- moew<br>"; 

				/* $this->_data = $data->fetchArray(); 
				foreach ($this->_data as $objRes) {
					echo $objRes->UserName. "<br>";
				} */
				return true;
			}

			
		}
		return false;
	}

	public function login($username = null, $password = null, $remember = false){
		//class Input has already trim() function on getting the value from ID


	if(!$username && !$password && $this->exists()){
		Session::put($this->_sessionName, $this->data()->Autokey);
		return true;
	} else {
		$user = $this->find($username);

		if ($user){
			if($this->data()->Password === Hash::make($password, $this->data()->Salt)){
				Session::put($this->_sessionName, $this->data()->Autokey);

				if($remember){

					$hash =  Hash::unique(); 
					$hashCheck = $this->_db->select('usersession', array('Userkey', '=', $this->data()->Autokey));

					/*echo $hashCheck->first()->hash; 
					exit;*/

					if(!$hashCheck->count()){
						$results = $this->_db->insert('usersession', array(
							'Userkey' => $this->data()->Autokey,
							'hash' => $hash
						));
						
					} else {

						$hash = $hashCheck->first()->hash;
					}
					/*echo "$this->_cookieName ||| $hash ||| ".Config::get('remember/cookie_expiry');
					exit;*/

					$Cookiereturn = Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));	

					

				}
				return true;
			}
		}
	}

		return false;
	}


	public function update($fields = array(), $Autokey= null){
		if(!$Autokey && $this->isLoggedIn()){
			$Autokey = $this->data()->Autokey;
		}
		if(!$this->_db->update('Users', $Autokey, $fields)){
			throw new Exception('There was a problem updating information.');
		} else {
			$this->find($this->data()->Autokey); 

		}
	}

	public function hasPermission($key){
		$group = $this->_db->select('usertype', array(
			'Autokey', '=', $this->data()->UserTypekey ));
		// print_r($group->first());

		if($group->count()){
			 $permissions = json_decode($group->first()->Permissions, true);
			// print_r($permissions);
			 if($permissions[$key] == true){
			 	return true;
			 } 
		}

		return false;

	}

	public function exists(){
		if(isset($this->_data)){
			return true;
		} else {
			return false;
		}
	}

	public function data() {
		return $this->_data;
	}



	public function isLoggedIn(){
		return $this->_isLoggedIn;

	}

	public function logout(){

		$this->_db->delete('usersession', array(
				'Userkey', '=', $this->data()->Autokey 
			));

		Session::delete($this->_sessionName);
		echo $this->_cookieName; 
		Cookie::delete($this->_cookieName);

		
	}



} //class user


?>
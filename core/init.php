<?php
	session_start();
	/*
		It is a good practice to initialize data for mysql, session, etc in Array form, and create a class that will automatically fetch it by calling every index inside a loop (see Config.php)
	*/

	$GLOBALS['config'] = array(
		'mysql' => array(
			'host' => '127.0.0.1',
			'username' => 'root',
			'password' => '',
			'db' => 'MacksDB'
		),
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800 
		),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token'
		),
		
		'screenName' => 'screenName'
	);

	spl_autoload_register(function($class) {
		require_once 'classes/'. $class .'.php';
	}); 

	require_once 'functions/sanitize.php';

	if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists( Config::get('session/session_name'))){
	
		$hash = Cookie::get(Config::get('remember/cookie_name'));
		$hashCheck = DB::getInstance()->select('usersession', array(
					'hash', '=', $hash
					));
		if($hashCheck->count()){
			$user = new User($hashCheck->first()->Userkey);
			$login = $user->login();
			if($login){
				Redirect::to('/');
			}
			
		}

	}

?>
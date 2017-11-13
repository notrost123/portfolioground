<?php
require_once "core/init.php";



if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validation();
		$validate->check( $_POST, array(
			'UserName' => array(
				'require' => true, 
				'min' => 2,
				'max' => 20,
				'unique' => 'Users' //For me no need, kasi dapat pati sa database naka UNIQUE KEY siya :P 
			), 
			'password' => array(
				'require' => true, 
				'min' => 6
			),
			'password_again' => array(
				'require' => true, 
				'matches' => 'password'
			),
			'lastname' => array(
				'require' => true, 
				'min' => 2,
				'max' => 50
			),
			'firstname' => array(
				'require' => true, 
				'min' => 2,
				'max' => 50
			),
			'midname' => array(
				'require' => true, 
				'min' => 2,
				'max' => 50
			)

		));

		if($validate->passed()){
			$user = new User();
			$salt = Hash::salt(32);
			
			try {
				$user->create(array(
					'LastName' => Input::get('lastname'), 
					'FirstName' => Input::get('firstname'), 
					'MidName' => Input::get('midname'), 
					'Password' => Hash::make(Input::get('password'), $salt), 
					'UserName' => Input::get('UserName'),
					'salt' => $salt,
					'CreateDate' => date("Y-m-d H:i:s")
				));
			} catch(Exception $e){
				die($e->getMessage());
			}

			Session::flash('Success', 'You registered successfully! You are automatically logged in on the account <br>');

			$login = $user->login(Input::get('UserName'), Input::get('password'));
			
			if($login){
				Redirect::to('/');
			}
			
		} else {
			echo "<pre> ";
			print_r($validate->errors());

			echo "</pre>";
		}

	} else {

	}

} 

?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action  = "" method = "POST" > 
		<div class = "field" >
			<label for = "lastname">Last Name</label>
			<input type = "text" name = "lastname" id= "lastname" value = "<?php echo escape(Input::get('lastname')); ?>" autocomplete="off" >
		</div>
		<div class = "field" >
			<label for = "firstname">First Name</label>
			<input type = "text" name = "firstname" id= "firstname" value = "<?php echo escape(Input::get('firstname')); ?>" autocomplete="off" >
		</div>
		<div class = "field" >
			<label for = "midname">Middle Name</label>
			<input type = "text" name = "midname" id= "midname" value = "<?php echo escape(Input::get('midname')); ?>" autocomplete="off" >
		</div>
		<div class = "field" >
			<label for = "UserName">Username</label>
			<input type = "text" name = "UserName" id= "UserName" value = "<?php echo escape(Input::get('UserName')); ?>" autocomplete="off" >
		</div>
		<div = class = "field">
			<label for = "password">Password</label>
			<input type = "password" name = "password" id= "password" value = "" autocomplete="off" >
		</div>

		<div = class = "field">
			<label for = "password_again">Enter your password again</label>
			<input type = "password" name = "password_again" id= "password_again" value = "" autocomplete="off" >
		</div>
		
		<input type="submit" name="" value = "Register">
		<input type = "hidden"  name = "token" id = "token" value = "<?php echo Token::generate();?>">
	</form>
</body>
</html>
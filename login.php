<?php
require_once 'core/init.php';

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validation();
		$validate->check($_POST,array(
			'username' => array( 'require' => true ), 
			'password' => array( 'require' => true )
		));
		
		if($validate->passed()){
			$user = new User();
			
			$remember =( Input::get('remember') === 'on') ? true : false;
		

			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			
			if($login){
				Redirect::to('/');
			} else {
				echo "Loging in unsuccessful ";

			}

		} else {
			foreach($validate->errors() as $error){
				echo $error. "<br>";
			}
		}
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<form action= "" method = "POST">
		<div class = "field">
			<label for = "username">Username</label>
			<input type="text" name="username" id = "username" autocomplete="off">
		</div>

		<div class = "field">
			<label for = "password">Password</label>
			<input type="password" name="password" id = "password" autocomplete="off" >
		</div>
		<div class = "field">
			<label for = "remember" >
				<input type="checkbox" name="remember" id = "remember"> Remember me 
			</label>
			
		</div>

		<input type="hidden" name="token" value ="<?php echo Token::generate(); ?>">
		<input type="submit" value = "Log in">
	</form>

</body>
</html>
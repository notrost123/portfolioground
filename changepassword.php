<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()){
	Redirect::to('index.php');
}


if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate = new Validation();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				Config::getScreen() => 'Current Password',
				'require' => true, 
				'min' => 6
			),
			'password_new' => array(
				Config::getScreen() => 'New password',
				'require' => true, 
				'min' => 6
			),
			'password_new_again' => array(
				Config::getScreen() => 'Retype new password',
				'require' => true, 
				'min' => 6,
				'matches' => 'password_new'
			)
		));

		if($validation->passed()){
			//change password

			if(Hash::make(Input::get('password_current'), $user->data()->Salt) !== $user->data()->Password) {
				echo "Your current password is wrong<br>";

			} else {
				$salt = Hash::salt(32);
				$user->update(array(
					'Password' => Hash::make(Input::get('password_new'), $salt),
					'Salt' => $salt
					));
				

				Session::flash('home', 'Your details have been updated.');
				if(Session::exists('home')){
					echo Session::flash('home');
				}

			}
		
		} else {
			echo "<pre> ";
			print_r($validation->errors());

			echo "</pre>";
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
	<form method = "POST" action = "">
		<div = class = "field">
				<label for = "password_current">Current Password</label>
				<input type = "password" name = "password_current" id= "password_current" value = "" autocomplete="off" >
			</div>

			<div = class = "field">
				<label for = "password_new">Enter your password new</label>
				<input type = "password" name = "password_new" id= "password_new" value = "" autocomplete="off" >
			</div>

			<div = class = "field">
				<label for = "password_new_again">Enter your password new again</label>
				<input type = "password" name = "password_new_again" id= "password_new_again" value = "" autocomplete="off" >
			</div>
			
			<input type="submit" name="" value = "Submit">
			<a href= "/">
			<input type="button" name = "back" id="back" value = "Back to home Page" ></a>
			<input type = "hidden"  name = "token" id = "token" value = "<?php echo Token::generate();?>">
	</form>
</body>
</html>
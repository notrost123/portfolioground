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
			'lastname' => array(
				Config::get('screenName') => 'Last Name',
				'require' => true, 
				'min' => 2,
				'max' => 50
			),
			'firstname' => array(
				Config::get('screenName') => 'First Name',
				'require' => true, 
				'min' => 2,
				'max' => 50
			),
			'midname' => array(
				Config::get('screenName') => 'Middle Name',
				'require' => true, 
				'min' => 2,
				'max' => 50
			)
		));

		if($validation->passed()){
			try {
				$user->update(array(
					'firstname' => Input::get('firstname'),
					'lastname' => Input::get('lastname'),
					'midname' => Input::get('midname')
					));
				Session::flash('home', 'Your details have been updated.');
				if(Session::exists('home')){
					echo Session::flash('home');
				}


			} catch(Exception $e){
				die($e->getMessage());
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
		<div class = "field" >
			<label for = "lastname">Last Name</label>
			<input type = "text" name = "lastname" id= "lastname" value = "<?php echo escape($user->data()->LastName); ?>" autocomplete="off" >
		</div>
		<div class = "field" >
			<label for = "firstname">First Name</label>
			<input type = "text" name = "firstname" id= "firstname" value = "<?php echo escape($user->data()->FirstName); ?>" autocomplete="off" >
		</div>
		<div class = "field" >
			<label for = "midname">Middle Name</label>
			<input type = "text" name = "midname" id= "midname" value = "<?php echo escape($user->data()->MidName); ?>" autocomplete="off" >
		</div>
		<input type="submit" name="Update">
		<input type="hidden" name="token" id = "back" value = "<?php echo Token::generate();?>">
		<a href= "/">
		<input type="button" name = "back" id="back" value = "Back to home Page" ></a>

	</form>
</body>
</html>
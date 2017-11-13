<?php
	
	require_once 'core/init.php';
	
	$txtHTMLFileInclude = "login/LoginPage.php";

	$user = DB::getInstance()->query("select * from Users"); 



	if($user->count()){
	//	echo "No user <br>";
	} else {
	//	echo "User Found <br>" ;
	}

	$user2 = DB::getInstance()->select('Users', array('UserName', '=', 'bmackay' ));

if($user2->count()){
	//	echo "No user2 <br>";
	} else {
	//	echo "User Found2 <br>";
	}
echo "<pre>";
	foreach($user2->fetchArray() as $arrResult){
	//	echo $arrResult->Autokey;

	}
 
/*

	$user2=DB::getInstance()->insert("Users", array(
		'UserName' => 'aniro',
		'Password'=> '123456',
		'LastName'=> 'Niro',
		'FirstName'=> 'Adonis',
		'MidName'=> '',
		'Gender'=> 'M'
 
	));

*/

	//$user2 = DB::getInstance()->update('Users', 1, array("LastName" => "Niro"));
	//var_dump($user2);

	//include("login/LoginPage.php");

	if(Session::exists('Success')){
		echo Session::flash('Success');
	}

	//echo Session::get(Config::get('session/session_name')); //
	print_r($_SESSION);

	$user = new User();
// echo "<pre>";
// 	var_dump($user->data());
// 	echo "</pre>";
	if($user->hasPermission('admin')){
		echo "<p>You are an admin. <br></p>";
	}

	if($user->isLoggedIn()){
		echo "<a href = \"profile.php?user=".escape($user->data()->UserName)."\" >". escape($user->data()->UserName) . "</a> is logged in";
		echo "<ul>"
			."<li><a href = \"update.php\">Update Info</a></li>" 
			."<li><a href = \"changepassword.php\">Change Password</a></li>" 
			."<li><a href = \"logout.php\" >Log Out</a></li>"
			."</ul>";

	} else {
		echo "<p>click <a href = \"register.php\" >Register</a> or <a href = \"login.php\" >Log in</a> </p>";
	}
	

?>


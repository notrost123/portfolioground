<?php 
require_once 'core/init.php';

$user = new User();

$user->logout();
echo "<pre>";
var_dump($_COOKIE);
var_dump($_SESSION);

Redirect::to('/');

?>
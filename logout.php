<?php
if(isset($_SESSION['username']))
{
	unset($_SESSION['username'], $_SESSION['userid'], $_SESSION['loggedin']);
	setcookie('username', '', time()-100);
	setcookie('password', '', time()-100);
	session_destroy();
	header('Location: index.php'); 
}
?>
<?php
	session_start();
	unset($_SESSION['username'], $_SESSION['userid'], $_SESSION['loggedin']);
	setcookie('username', '', time()-100);
	setcookie('password', '', time()-100);
	session_destroy();
	//echo 'we are here';
	header('Location: index.php'); 
?>
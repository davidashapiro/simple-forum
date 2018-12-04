<?php
//This page let initialize the forum by checking for example if the user is logged
session_start();
header('Content-type: text/html;charset=UTF-8');
if(!isset($_SESSION['username']) and isset($_COOKIE['username'], $_COOKIE['password']))
{
	try {
		$cnn = $db->query('select password,id from users where username="'.$_COOKIE['username'].'"');
		$dn_cnn = $cnn->fetch();
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	if(sha1($dn_cnn['password'])==$_COOKIE['password'] and $cnn->rowCount() > 0)
	{
		$_SESSION['username'] = $_COOKIE['username'];
		$_SESSION['userid'] = $dn_cnn['id'];
	}
}
?>
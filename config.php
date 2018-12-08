<?php
/******************************************************
------------------Required Configuration---------------
Please edit the following variables so the forum can
work correctly.
******************************************************/


//database credentials
define('DBHOST','localhost');
define('DBUSER','davidsh2_forum');
define('DBPASS','Dima61949');
define('DBNAME','davidsh2_phpb393');
//We log to the DataBase
try {
	$db = new PDO("mysql:host=".DBHOST.";port=3306;dbname=".DBNAME, DBUSER, DBPASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo $e->getMessage();
}

//set timezone
date_default_timezone_set('America/Chicago');

//Username of the Administrator
$admin='dim';

/******************************************************
-----------------Optional Configuration----------------
******************************************************/

//Forum Home Page
$url_home = 'index.php';

//Design Name
$design = 'default';


/******************************************************
----------------------Initialization-------------------
******************************************************/
include('init.php');
?>
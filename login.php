<?php
include('config.php');
if (isset($_GET['page'])) {
	$page = $_GET['page'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include 'header0.php'; ?>
        <title>Login</title>
    </head>
    <body id="forum_body">
    	<script type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
<?php
	$ousername = '';
	if(isset($_POST['username'], $_POST['password']))
	{
		$page = $_POST['page'];
		if(get_magic_quotes_gpc())
		{
			$ousername = stripslashes($_POST['username']);
			$username = stripslashes($_POST['username']);
			$password = stripslashes($_POST['password']);
		}
		else
		{
			$username = $_POST['username'];
			$password = $_POST['password'];
		}
		try 
		{
			$stmt = $db->prepare('select password,id from users where username=:username');
			$stmt->execute(array(':username' => $username));
			$dn = $stmt->fetch();
			$rows = $stmt->rowCount();
		
			if($dn['password']==sha1($password) and $rows > 0)
			{
				$form = false;
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['userid'] = $dn['id'];
				$_SESSION['loggedin'] = true;
		    	$_SESSION['memberID'] = $dn['id'];
		    	
				if(isset($_POST['memorize']) and $_POST['memorize']=='yes')
				{
					$one_year = time()+(60*60*24*365);
					setcookie('username', $_POST['username'], $one_year);
					setcookie('password', sha1($password), $one_year);
				}
				if ($page == 'blog') {
					header('location: /simple-blog/admin/index.php');
				} else {
					header('location: /simple-forum/index.php');
				}
			}
			else
			{
				$form = true;
				$message = 'The username or password are not valid.';
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	else
	{
		$form = true;
	}
	if($form)
	{
		if(isset($message))
		{
			echo '<div class="box_error">'.$message.'</div>';
		}
?>
			<div class="content">
    			<div class="box_login">
					<form id="forum_form" action="login.php" method="post">
						<input type="hidden" name="page" value="<?php echo $page ?>" />
						<input type="hidden" name="user_level" value="0" />
						<label for="username">Username</label>
						<input type="text" name="username" id="username" /><br />
						<label for="password">Password</label>
						<input type="password" name="password" id="password" /><br />
			        	<label for="memorize">Remember</label>
			        	<input type="checkbox" name="memorize" id="memorize" value="yes" />
			        	<div class="center">
				        	<input type="submit" value="Login" /> 
				        	<input type="button" onclick="javascript:document.location='signup.php';" value="Sign Up" />
			        	</div>
			    	</form>
				</div>
			</div>
<?php
	}
?>
		</span>
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
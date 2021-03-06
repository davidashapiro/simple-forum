<?php
	include('config.php');
	$ousername = '';
	if(isset($_POST['username'], $_POST['password']))
	{
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
			$stmt = $db->prepare('select password,id,user_level from users where username=:username');
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
		    	$_SESSION['user_level'] = $dn['user_level'];
		    	
				if(isset($_POST['memorize']) and $_POST['memorize']=='yes')
				{
					$one_year = time()+(60*60*24*365);
					setcookie('username', $_POST['username'], $one_year);
					setcookie('password', sha1($password), $one_year);
				}
				if (isset($_SESSION['page']))
				{
					if ($_SESSION['page'] == 'blog')
					{
						header('location: /simple-blog/admin/index.php');
					} 
					elseif ($_SESSION['page'] == 'forum') 
					{
						header('location: /simple-forum/index.php');
					}
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
		if(isset($_GET['page']))
		{
			$page = stripcslashes($_GET['page']);
			$_SESSION['page'] = $page;
			if($page != 'blog' and $page != 'forum')
			{
				unset($_SESSION['page']);
			}
		}
		$form = true;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<title>Login</title>
        <?php include '../profile/header0.php';
        if (isset($_SESSION['page']) and $_SESSION['page'] == 'forum')
        {
			$topmenu = 5;
		}
		elseif (isset($_SESSION['page']) and $_SESSION['page'] == 'blog') 
		{
			$topmenu = 4;
		}
		$rightmenu = 0;
        echo '<link href="'.$design.'/style.css" rel="stylesheet" title="Style" />';
        ?>
    </head>
    <body id="forum_body">
    	<?php 
		include '../profile/header1.php';
		include '../profile/topmenu.php';
		include '../profile/header2.php';
		include '../profile/header3.php';
		?>
		<span>
	
<?php
	if($form)
	{
		if(isset($message))
		{
			echo '<div class="message">'.$message.'</div>';
		}
?>
			<div class="content">
    			<div class="box_login">
					<form id="forum_form" action="login.php" method="post">
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
		<?php 
			include '../profile/footer.php';
			include '../profile/counter.php'; 
		?>
	</body>
</html>
<?php
//This page let users sign up
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<title>Sign Up</title>
        <?php include '../profile/header0.php';
		$topmenu = 5;
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
if(isset($_POST['username'], $_POST['password'], $_POST['passverif'], $_POST['email'], $_POST['avatar']) and $_POST['username']!='')
{
	if(get_magic_quotes_gpc())
	{
		$_POST['username'] = stripslashes($_POST['username']);
		$_POST['password'] = stripslashes($_POST['password']);
		$_POST['passverif'] = stripslashes($_POST['passverif']);
		$_POST['email'] = stripslashes($_POST['email']);
		$_POST['avatar'] = stripslashes($_POST['avatar']);
	}
	if($_POST['password']==$_POST['passverif'])
	{
		if(strlen($_POST['password'])>=6)
		{
			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
			{
				$username = $_POST['username'];
				$password = sha1($_POST['password']);
				$email = $_POST['email'];
				$avatar = $_POST['avatar'];
				try 
				{
					$stmt = $db->query('select id from users where username="'.$username.'"');
					$dn = $stmt->rowCount();
					if($dn==0)
					{
						$stmt = $db->query('select id from users');
						$dn2 = $stmt->rowCount();
						$id = $dn2+1;
						
						$stmt = $db->query('insert into users(id, username, password, email, avatar, signup_date) values ('.$id.', "'.$username.'", "'.$password.'", "'.$email.'", "'.$avatar.'", "'.time().'")');
						if(isset($stmt))
						{
							$form = false;
							header('location: login.php');
						}
						else
						{
							$form = true;
							$message = 'An error occurred while signing you up.';
						}
					}
					else
					{
						$form = true;
						$message = 'Another user already use this username.';
					}
				} 
				catch (PDOException $e) 
				{
					echo $e->getMessage();
				}
			}
			else
			{
				$form = true;
				$message = 'The email you typed is not valid.';
			}
		}
		else
		{
			$form = true;
			$message = 'Your password must have a minimum of 6 characters.';
		}
	}
	else
	{
		$form = true;
		$message = 'The passwords you entered are not identical.';
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
		echo '<div class="message">'.$message.'</div>';
	}
?>
			<div class="content">
				<?php
				include 'showtoprightbox.php';
				shownotloggedintoprightbox(); ?>
			</div>
			<div class="box_login">
			    <form id="forum_form" action="signup.php" method="post">
			        Please fill this form to sign up:<br />
			        <div class="center">
			            <label for="username">Username</label>
			            <input type="text" name="username" value="<?php if(isset($_POST['username'])){echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			            <label for="password">Password<span class="small">(6 characters min.)</span></label>
			            <input type="password" name="password" /><br />
			            <label for="passverif">Password<span class="small">(verification)</span></label>
			            <input type="password" name="passverif" /><br />
			            <label for="email">Email</label>
			            <input type="text" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			            <label for="avatar">Avatar<span class="small">(optional)</span></label>
			            <input type="text" name="avatar" value="<?php if(isset($_POST['avatar'])){echo htmlentities($_POST['avatar'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
			            <input type="submit" value="Sign Up" />
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
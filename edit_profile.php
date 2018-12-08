<?php
//This page let an user edit his profile
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include 'header0.php'; ?>
        <title>Edit your profile</title>
    </head>
    <body id="forum_body" >
    	<script type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
			<div class="content">
				<?php
				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;&gt;&nbsp;Delete the category';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} 
				if(isset($_POST['username'], $_POST['password'], $_POST['passverif'], $_POST['email'], $_POST['avatar']))
				{
					if(get_magic_quotes_gpc())
					{
						$_POST['username'] = stripslashes($_POST['username']);
						$_POST['password'] = stripslashes($_POST['password']);
						$_POST['passverif'] = stripslashes($_POST['passverif']);
						$_POST['email'] = stripslashes($_POST['email']);
						$_POST['avatar'] = stripslashes($_POST['avatar']);
						$_POST['user_level'] = intval($_POST['user_level']);
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
								$user_level = $_POST['user_level'];
								$stmt = $db->query('select count(*) as nb from users where username="'.$username.'"');
								$dn = $stmt->fetch();
								if($dn['nb']==0 or $_POST['username']==$_SESSION['username'])
								{
									if($db->query('update users set username="'.$username.'", password="'.$password.'", email="'.$email.'", avatar="'.$avatar.'" where id="'.$_SESSION['userid'].'"'))
									{
										$form = false;
										unset($_SESSION['username'], $_SESSION['userid']);
										header('location: login.php');
									}
									else
									{
										$form = true;
										$message = 'An error occured while editing your profile.';
									}
								}
								else
								{
									$form = true;
									$message = 'Another user already uses this username.';
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
						echo '<strong>'.$message.'</strong>';
					}
					if(isset($_POST['username'],$_POST['password'],$_POST['email']))
					{
						$username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
						if($_POST['password']==$_POST['passverif'])
						{
							$password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');
						}
						else
						{
							$password = '';
						}
						$email = htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');
						$avatar = htmlentities($_POST['avatar'], ENT_QUOTES, 'UTF-8');
					}
					else
					{
						$stmt = $db->query('select username,email,avatar from users where username="'.$_SESSION['username'].'"');
						$dnn = $stmt->fetch();
						$username = htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8');
						$password = '';
						$email = htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8');
						$avatar = htmlentities($dnn['avatar'], ENT_QUOTES, 'UTF-8');
					}
					?>
					<div class="box_login">
				    <form id="forum_form" action="edit_profile.php" method="post">
				        You can edit your informations:<br />
				        <div class="center">
				        	<input type="hidden" name="user_level" value="<?php echo $user_level; ?>" />
				            <label for="username">Username</label>
				            <input type="text" name="username" id="username" value="<?php echo $username; ?>" /><br />
				            <label for="password">Password<span class="small">(6 chars min.)</span></label>
				            <input type="password" name="password" id="password" value="<?php echo $password; ?>"/><br />
				            <label for="passverif">Password<span class="small">(verification)</span></label>
				            <input type="password" name="passverif" id="passverif" value="<?php echo $password; ?>" /><br />
				            <label for="email">Email</label>
				            <input type="text" name="email" id="email" value="<?php echo $email; ?>" /><br />
				            <label for="avatar">Avatar<span class="small">(optional)</span></label>
				            <input type="text" name="avatar" id="avatar" value="<?php echo $avatar; ?>" /><br />
				            <input type="submit" value="Submit" />
				        </div>
				    </form>
				    </div>
				<?php } ?>
			</div>
		</span>
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
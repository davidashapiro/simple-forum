<?php
//This page display the profile of the user
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Profile of the user</title>
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
        	<div class="content">
				<?php
				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>'.'&gt; <a id="forum_a" href="users.php">List of all users</a> &gt; Profile of the user';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>
				
				<?php
				if(isset($_GET['id']))
				{
					try {
						$id = intval($_GET['id']);
						$dn = $db->prepare('select username, email, avatar, signup_date from users where id=:id');
						$dn->execute(array(':id' => $id));
						if($dn->rowCount() > 0)
						{
							$dnn = $dn->fetch();
				?>
							This is the profile of "<?php echo htmlentities($dnn['username']); ?>" :
							<?php
							if($_SESSION['userid']==$id)
							{
							?>
								<br /><div class="center">
								<a id="forum_a" href="edit_profile.php" class="button">
									Edit my profile</a></div>
							<?php
							}
							?>
							<table id="forum_table" style="width:90%;">
								<tr>
	    							<td>
	    								<?php
										if($dnn['avatar']!='')
										{
											echo '<img src="'.htmlentities($dnn['avatar'], ENT_QUOTES, 'UTF-8').'" alt="Avatar" style="max-width:100px;max-height:100px;" />';
										}
										else
										{
											echo 'This user has no avatar.';
										}
										?>
									</td>
	    							<td class="left">
	    								<h1><?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?></h1>
	    								Email: <a id="forum_a" href="<?php echo htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8'); ?></a><br />
	        							This user joined the website on <?php echo date('m/d/Y',$dnn['signup_date']); ?>
	        						</td>
								</tr>
							</table>
							<?php
							if(isset($_SESSION['username']) and $_SESSION['username']!=$dnn['username'])
							{
							?>
								<br /><a id="forum_a" href="new_pm.php?recip=<?php echo urlencode($dnn['username']); ?>" class="big">Send new pm to <?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?></a>
				<?php
							}
						}
						else
						{
							echo 'This user doesn\'t exist.';
						}
					}
					catch (PDOException $e) {
						echo $e->getMessage();
					}
				}
				else
				{
					echo 'The ID of this user is not defined.';
				}
				?>
			</div>
		</span>
		<?php 
			include '../profile/footer.php';
			include '../profile/counter.php'; 
		?>
	</body>
</html>
<?php
//This page let an administrator edit a category
include('config.php');
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	$stmt = $db->query('select count(id) as nb1, name, description from categories where id="'.$id.'" group by id');
	$dn1 = $stmt->fetch();
	if($dn1['nb1']>0)
	{
		if(isset($_SESSION['user_level']) and $_SESSION['user_level']==$admin)
		{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Edit a category - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    	<?php include '../profile/header0.php';
    	include '../profile/header01.php';
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'&nbsp;&gt;&nbsp;Edit the category';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} 
				if(isset($_POST['name'], $_POST['description']) and $_POST['name']!='')
				{
					$name = $_POST['name'];
					$description = $_POST['description'];
					if(get_magic_quotes_gpc())
					{
						$name = stripslashes($name);
						$description = stripslashes($description);
					}
					if($db->query('update categories set name="'.$name.'", description="'.$description.'" where id="'.$id.'"'))
					{
						header('location: index.php');
						exit();
					}
					else
					{
						echo 'An error occured while editing the category.';
					}
				}
				else
				{
				?>
					<form id="forum_form" action="edit_category.php?id=<?php echo $id; ?>" method="post">
						<label for="name">Name</label>
						<input type="text" name="name" id="name" value="<?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?>" /><br />
						<label for="description">Description</label>(html enabled)<br />
    					<textarea name="description" id="description" cols="70" rows="6">
    						<?php echo htmlentities($dn1['description'], ENT_QUOTES, 'UTF-8'); ?>
    					</textarea><br />
    					<input type="submit" value="Edit" />
					</form>
				<?php
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
<?php
}
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a id="forum_a" href="login.php">Login</a> - <a id="forum_a" href="signup.php">Sign Up</a></h2>';
}
}
else
{
	echo '<h2>The category you want to edit doesn\'t exist..</h2>';
}
}
else
{
	echo '<h2>The ID of the category you want to edit is not defined.</h2>';
}
?>
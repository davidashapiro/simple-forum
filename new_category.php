<?php
//This page let create a new category
include('config.php');
if(isset($_SESSION['user_level']) and $_SESSION['user_level']==$admin)
{
	if(isset($_POST['name'], $_POST['description']) and $_POST['name']!='')
	{
		$name = $_POST['name'];
		$description = $_POST['description'];
		if(get_magic_quotes_gpc())
		{
			$name = stripslashes($name);
			$description = stripslashes($description);
		}
		$name = $name;
		$description = $description;
		if($db->query('insert into categories (id, name, description, position) select ifnull(max(id), 0)+1, "'.$name.'", "'.$description.'", count(id)+1 from categories'))
		{
			header('location: index.php');
		}
		else
		{
			echo 'An error occured while creating the category.';
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>New Category - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;New Category';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				}
				if(!isset($_POST['name'], $_POST['description']))
				{
				?>
				<form id="forum_form" action="new_category.php" method="post">
					<label for="name">Name</label>
					<input type="text" name="name" id="name" /><br />
					<label for="description">Description</label>(html enabled)<br />
				    <textarea name="description" id="description" cols="70" rows="6"></textarea><br />
				    <input type="submit" value="Create" />
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
?>
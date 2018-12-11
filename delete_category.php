<?php
//This page let delete a category
include('config.php');
if(isset($_GET['id']))
{
$id = intval($_GET['id']);
$stmt = $db->query('select count(id) as nb1, name, position from categories where id="'.$id.'" group by id');
$dn1 = $stmt->fetch();
if($dn1['nb1']>0)
{
	if(isset($_SESSION['user_level']) and $_SESSION['user_level']==$admin)
	{
		if(isset($_POST['confirm']))
		{
			if($db->query('delete from categories where id="'.$id.'"') and $db->query('delete from topics where parent="'.$id.'"') and $db->query('update categories set position=position-1 where position>"'.$dn1['position'].'"'))
			{
				header('location: index.php');
			}
			else
			{
				echo 'An error occured while deleting the category and it topics.';
			}
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Delete a category - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'&nbsp;&gt;&nbsp;Delete the category';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} 
				
				if(!isset($_POST['confirm']))
				{
				?>
					<form id="forum_form" action="delete_category.php?id=<?php echo $id; ?>" method="post">
						Are you sure you want to delete this category and all it topics?
    					<input type="hidden" name="confirm" value="true" />
						<input type="submit" value="Yes" /> 
    					<input type="button" value="No" onclick="javascript:history.go(-1);" />
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
	echo '<h2>The category you want to delete doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of the category you want to delete is not defined.</h2>';
}
?>
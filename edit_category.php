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
		if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
		{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.css" />
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.2/dist/jquery.fancybox.min.js"></script>
    	<link href='/profile/css/styles.css' rel='stylesheet' type='text/css'>
    	<script src="/profile/scripts/scrolltop.js" type="text/javascript"></script>
    	<script language='Javascript' type='text/javascript'>
			var topmenu = 5;
			var rightmenu = 0;
		</script>
		<script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
		<script>
          tinymce.init({
              selector: "textarea",
              plugins: [
                  "advlist autolink lists link image charmap print preview anchor",
                  "searchreplace visualblocks code fullscreen",
                  "insertdatetime media table contextmenu paste"
              ],
              toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
          });
		</script>
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Edit a category - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
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
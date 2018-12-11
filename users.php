<?php
//This page displays a list of all registered members
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>List of all the users</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>'.'&nbsp;&gt; <a id="forum_a" href="users.php">List of all users</a>';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>
				This is the list of all the users:
				<table id="forum_table" >
				    <tr>
				    	<th>ID</th>
				    	<th>Username</th>
				    	<th>Email</th>
				    </tr>
				<?php
				$stmt = $db->query('select id, username, email from users');
				while($dnn = $stmt->fetch())
				{
				?>
					<tr>
				    	<td><?php echo $dnn['id']; ?></td>
				    	<td><a id="forum_a" href="profile.php?id=<?php echo $dnn['id']; ?>"><?php echo htmlentities($dnn['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    	<td><a id="forum_a" href="mailto:<?php echo htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlentities($dnn['email'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    </tr>
				<?php
				}
				?>
				</table>
			</div>
		</span>
		<?php 
			include '../profile/footer.php';
			include '../profile/counter.php'; 
		?>
	</body>
</html>
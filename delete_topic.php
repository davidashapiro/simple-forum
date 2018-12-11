<?php
//This page let delete a topic
include('config.php');
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	if(isset($_SESSION['username']))
	{
		try {
			$stmt = $db->query('select count(t.id) as nb1, t.title, t.parent, c.name from topics as t, categories as c where t.id="'.$id.'" and t.id2=1 and c.id=t.parent group by t.id');
			$dn1 = $stmt->fetch();
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		if($dn1['nb1']>0)
		{
			if($_SESSION['user_level']==$admin or $_SESSION['user_level'] == $moderator)
			{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Delete a topic - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$dn1['parent'].'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a> &gt; <a id="forum_a" href="read_topic.php?id='.$id.'">'.htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8').'</a> &gt; Delete the topic';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} 
				if(isset($_POST['confirm']))
				{
					if($db->query('delete from topics where id="'.$id.'"'))
					{
						header('location: list_topics.php?parent='.$dn1['parent']);
					}
					else
					{
						echo 'An error occured while deleting the topic.';
					}
				}
				else
				{
				?>
					<form id="forum_form" action="delete_topic.php?id=<?php echo $id; ?>" method="post">
						Are you sure you want to delete this topic?
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
	echo '<h2>You don\'t have the right to delete this topic.</h2>';
}
}
else
{
	echo '<h2>The topic you want to delete doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a id="forum_a" href="login.php">Login</a> - <a id="forum_a" href="signup.php">Sign Up</a></h2>';
}
}
else
{
	echo '<h2>The ID of the topic you want to delete is not defined.</h2>';
}
?>
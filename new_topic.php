<?php
//This page let users create new topics
include('config.php');
if(isset($_GET['parent']))
{
	$id = intval($_GET['parent']);
	if(isset($_SESSION['username']))
	{
		$stmt = $db->query('select count(c.id) as nb1, c.name from categories as c where c.id="'.$id.'"');
		$dn1 = $stmt->fetch();
		if($dn1['nb1']>0)
		{
			if(isset($_POST['message'], $_POST['title']) and $_POST['message']!='' and $_POST['title']!='')
			{
				include('bbcode_function.php');
				$title = $_POST['title'];
				$message = $_POST['message'];
				if(get_magic_quotes_gpc())
				{
					$title = stripslashes($title);
					$message = stripslashes($message);
				}
				//$message = bbcode_to_html($message);
				if($db->query('insert into topics (parent, id, id2, title, message, authorid, timestamp, timestamp2) select "'.$id.'", ifnull(max(id), 0)+1, "1", "'.$title.'", "'.$message.'", "'.$_SESSION['userid'].'", "'.time().'", "'.time().'" from topics'))
				{
					header('location: list_topics.php?parent='.$id);
				}
				else
				{
					echo 'An error occurred while creating the topic.';
				}
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>New Topic - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$id.'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a> &gt; New Topic';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				}
				
				if(!isset($_POST['message'], $_POST['title']))
				{
				?>
					<form id="forum_form" action="new_topic.php?parent=<?php echo $id; ?>" method="post">
						<label for="title">Title</label>
						<input type="text" name="title" id="title" width="80" /><br />
					    <label for="message">Message</label><br />
					    <textarea name="message" id="message" cols="120" rows="6"></textarea><br />
					    <input type="submit" value="Send" />
					</form>
				<?php } ?>
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
			echo '<h2>The category you want to add a topic doesn\'t exist.</h2>';
		}
	}
}
else
{
	echo '<h2>The ID of the category you want to add a topic is not defined.</h2>';
}
?>
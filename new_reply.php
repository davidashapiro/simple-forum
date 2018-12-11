<?php
//This page let reply to a topic
include('config.php');
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	if(isset($_SESSION['username']))
	{
		$stmt = $db->query('select count(t.id) as nb1, t.title, t.parent, c.name from topics as t, categories as c where t.id="'.$id.'" and t.id2=1 and c.id=t.parent group by t.id');
		$dn1 = $stmt->fetch();
		if($dn1['nb1']>0)
		{
			if(isset($_POST['message']) and $_POST['message']!='')
			{
				include('bbcode_function.php');
				$message = $_POST['message'];
				if(get_magic_quotes_gpc())
				{
					$message = stripslashes($message);
				}
				//$message = bbcode_to_html($message);
				if($db->query('insert into topics (parent, id, id2, title, message, authorid, timestamp, timestamp2) select "'.$dn1['parent'].'", "'.$id.'", max(id2)+1, "", "'.$message.'", "'.$_SESSION['userid'].'", "'.time().'", "'.time().'" from topics where id="'.$id.'"') and $db->query('update topics set timestamp2="'.time().'" where id="'.$id.'" and id2=1'))
				{
					header('location: read_topic.php?id='.$id);
				}
				else
				{
					echo 'An error occurred while sending the message.';
				}
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add a reply - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$dn1['parent'].'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;<a id="forum_a" href="read_topic.php?id='.$id. '">'.htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;Add a reply';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				}
				if(!isset($_POST['message']))
				{
				?>
					<form id="forum_form" action="new_reply.php?id=<?php echo $id; ?>" method="post">
				    	<label for="message">Message</label><br />
				    	<textarea name="message" id="message" cols="70" rows="6"></textarea><br />
				   		<input type="submit" value="Send" />
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
			echo '<h2>The topic you want to reply doesn\'t exist.</h2>';
		}
	}
}
else
{
	echo '<h2>The ID of the topic you want to reply is not defined.</h2>';
}
?>
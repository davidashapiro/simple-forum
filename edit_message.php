<?php
//This page let an user edit a message
include('config.php');
if(isset($_GET['id'], $_GET['id2']))
{
	$id = intval($_GET['id']);
	$id2 = intval($_GET['id2']);
	if(isset($_SESSION['username']))
	{
		$stmt = $db->query('select count(t.id) as nb1, t.authorid, t2.title, t.message, t.parent, c.name from topics as t, topics as t2, categories as c where t.id="'.$id.'" and t.id2="'.$id2.'" and t2.id="'.$id.'" and t2.id2=1 and c.id=t.parent group by t.id');
		$dn1 = $stmt->fetch();
		if($dn1['nb1']>0)
		{
			if($_SESSION['userid']==$dn1['authorid'] or $_SESSION['username']==$admin)
			{
				include('bbcode_function.php');
				if(isset($_POST['message']) and $_POST['message']!='')
				{
					if($id2==1)
					{
						if($_SESSION['username']==$admin and isset($_POST['title']) and $_POST['title']!='')
						{
							$title = $_POST['title'];
							if(get_magic_quotes_gpc())
							{
								$title = stripslashes($title);
							}
							$title = $dn1['title'];
						}
						else
						{
							$title = $dn1['title'];
						}
					}
					else
					{
						$title = '';
					}
					$message = $_POST['message'];
					if(get_magic_quotes_gpc())
					{
						$message = stripslashes($message);
					}
					//$message = bbcode_to_html($message);
					if($db->query('update topics set title="'.$title.'", message="'.$message.'" where id="'.$id.'" and id2="'.$id2.'"'))
					{
						header('location: read_topic.php?id='.$id);
					}
					else
					{
						echo 'An error occurred while editing the message.';
					}
				}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Edit a reply - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$dn1['parent'].'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;<a id="forum_a" href="read_topic.php?id='.$id. '">'.htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;Edit a reply';
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
					<form id="forum_form" action="edit_message.php?id=<?php echo $id; ?>&id2=<?php echo $id2; ?>" method="post">
						<?php if($_SESSION['username']==$admin and $id2==1) { ?>
							<label for="title">Title</label>
							<input type="text" name="title" id="title" value="<?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?>" />
						<?php } ?>
						<label for="message">Message</label><br />
						<textarea name="message" id="message" cols="100" rows="6"><?php echo html_to_bbcode($dn1['message']); ?></textarea><br />
    					<input type="submit" value="Submit" />
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
				echo '<h2>You don\'t have the right to edit this message.</h2>';
			}
		}
		else
		{
			echo '<h2>The message you want to edit doesn\'t exist..</h2>';
		}
	}
}
else
{
	echo '<h2>The ID of the message you want to edit is not defined.</h2>';
}
?>
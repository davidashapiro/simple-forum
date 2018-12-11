<?php
	//This page let create a new personnal message
	include('config.php');
	$form = true;
	$otitle = '';
	$orecip = '';
	$omessage = '';
	if(isset($_POST['title'], $_POST['recip'], $_POST['message']))
	{
		$otitle = $_POST['title'];
		$orecip = $_POST['recip'];
		$omessage = $_POST['message'];
		if(get_magic_quotes_gpc())
		{
			$otitle = stripslashes($otitle);
			$orecip = stripslashes($orecip);
			$omessage = stripslashes($omessage);
		}
		if($otitle != '' and $orecip != '' and $omessage != '')
		{
			$title = $otitle;
			$recip = $orecip;
			$message = nl2br(htmlentities($omessage, ENT_QUOTES, 'UTF-8'));
			$stmt = $db->query('select count(id) as recip, id as recipid, (select count(*) from pm) as npm from users where username="'.$recip.'"');
			$dn1 = $stmt->fetch();
			if($dn1['recip']==1)
			{
				if($dn1['recipid']!=$_SESSION['userid'])
				{
					$id = $dn1['npm']+1;
					if($db->query('insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "1", "'.$title.'", "'.$_SESSION['userid'].'", "'.$dn1['recipid'].'", "'.$message.'", "'.time().'", "yes", "no")'))
					{
						header('location: list_pm.php');
						$form = false;
					}
					else
					{
						$error = 'An error occurred while sending the PM.';
					}
				}
				else
				{
					$error = 'You cannot send a PM to yourself.';
				}
			}
			else
			{
				$error = 'The recipient of your PM doesn\'t exist.';
			}
		}
		else
		{
			$error = 'A field is not filled.';
		}
	}
	elseif(isset($_GET['recip']))
	{
		$orecip = $_GET['recip'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>New PM</title>
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
<?php
if(isset($_SESSION['username']))
{
	if($form)
	{
		if(isset($error))
		{
			echo '<div class="message">'.$error.'</div>';
		}
?>
			<div class="content">
				<?php
				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_pm.php">List of your PMs</a>&nbsp;&gt;&nbsp;New PM';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>
				<h1>New Personal Message</h1>
			    <form id="forum_form" action="new_pm.php" method="post">
					Please fill this form to send a PM:<br />
			        <label for="title">Title</label>
			        <input type="text" value="<?php echo htmlentities($otitle, ENT_QUOTES, 'UTF-8'); ?>" id="title" name="title" /><br />
			        <label for="recip">Recipient<span class="small">(Username)</span></label>
			        <input type="text" value="<?php echo htmlentities($orecip, ENT_QUOTES, 'UTF-8'); ?>" id="recip" name="recip" /><br />
			        <label for="message">Message</label>
			        <textarea cols="40" rows="5" id="message" name="message">
			        	<?php echo htmlentities($omessage, ENT_QUOTES, 'UTF-8'); ?>
			        </textarea><br />
			        <input class="center" type="submit" value="Send" />
			    </form>
			</div>
<?php
	}
}
?>
		</span>
		<?php 
			include '../profile/footer.php';
			include '../profile/counter.php'; 
		?>
	</body>
</html>
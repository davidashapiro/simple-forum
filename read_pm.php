<?php
//This page display a personnal message
	include('config.php');
	if(isset($_GET['id']))
	{
		$id = intval($_GET['id']);
		$req1 = $db->query('select title, user1, user2 from pm where id="'.$id.'" and id2="1"');
		$dn1 = $req1->fetch();
		if($req1->rowCount() == 1)
		{
			if($dn1['user1']==$_SESSION['userid'] or $dn1['user2']==$_SESSION['userid'])
			{
				if($dn1['user1']==$_SESSION['userid'])
				{
					$db->query('update pm set user1read="yes" where id="'.$id.'" and id2="1"');
					$user_partic = 2;
				}
				else
				{
					$db->query('update pm set user2read="yes" where id="'.$id.'" and id2="1"');
					$user_partic = 1;
				}
				$req2 = $db->query('select pm.timestamp, pm.message, users.id as userid, users.username, users.avatar from pm, users where pm.id="'.$id.'" and users.id=pm.user1 order by pm.id2');
				if(isset($_POST['message']) and $_POST['message']!='')
				{
					$message = $_POST['message'];
					if(get_magic_quotes_gpc())
					{
						$message = stripslashes($message);
					}
					//$message = nl2br(htmlentities($message, ENT_QUOTES, 'UTF-8'));
					$message = strip_tags($message);
					$string = htmlentities($message, null, 'utf-8');
					$message = str_replace("&nbsp;", " ", $string);
					$message = html_entity_decode($message);
					if($db->query('insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "'.(intval($req2->rowCount())+1).'", "", "'.$_SESSION['userid'].'", "", "'.$message.'", "'.time().'", "", "")') and $db->query('update pm set user'.$user_partic.'read="yes" where id="'.$id.'" and id2="1"'))
					{
						header('location: read_pm.php?id='.$id);
					}
					else
					{
					?>
						<div class="box_error">An error occurred while sending the reply.<br />
						<a id="forum_a" href="read_pm.php?id=<?php echo $id; ?>">Go to the PM</a></div>
					<?php
					}
				}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Read a PM</title>
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
				if(!isset($_POST['message']))
				{
				?>
				<div class="content">
				<?php
				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_pm.php">List of your PMs</a>&nbsp;&gt;&nbsp;Read PM';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>

				<h1><?php echo $dn1['title']; ?></h1>
				<table id="forum_table" class="messages_table">
					<tr>
    					<th class="author">User</th>
        				<th>Message</th>
					</tr>
					<?php
					while($dn2 = $req2->fetch())
					{
					?>
					<tr>
    					<td class="author center">
    						<?php
							if($dn2['avatar']!='')
							{
								echo '<img src="'.htmlentities($dn2['avatar']).'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
							}
							?>
							<br />
							<a id="forum_a" href="profile.php?id=<?php echo $dn2['userid']; ?>">
							<?php echo $dn2['username']; ?></a>
						</td>
    					<td class="left">
    						<div class="date">Date sent: <?php echo date('m/d/Y H:i:s' ,	$dn2['timestamp']); ?>
    						</div>
    						<?php echo $dn2['message']; ?>
    					</td>
    				</tr>
					<?php } ?>
				</table><br />
				<h2>Reply</h2>
				<div class="center">
    				<form id="forum_form" action="read_pm.php?id=<?php echo $id; ?>" method="post">
    					<label for="message" class="center">Message</label><br />
        				<textarea cols="40" rows="5" name="message" id="message"></textarea><br />
        				<input type="submit" value="Send" />
    				</form>
				</div>
			</div>
<?php
				}
			}
			else
			{
				echo '<div class="message">You don\'t have the right to access this page.</div>';
			}
		}
		else
		{
			echo '<div class="message">This message doesn\'t exist.</div>';
		}
	}
	else
	{
		echo '<div class="message">The ID of this message is not defined.</div>';
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
<?php
//This page display a personnal message
include('config.php');
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
        <title>Read a PM</title>
    </head>
    <body id="forum_body" >
    	<script type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
<?php
if(isset($_SESSION['username']))
{
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
					$message = nl2br(htmlentities($message, ENT_QUOTES, 'UTF-8'));
					if($db->query('insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "'.(intval($req2->rowCount())+1).'", "", "'.$_SESSION['userid'].'", "", "'.$message.'", "'.time().'", "", "")') and $db->query('update pm set user'.$user_partic.'read="yes" where id="'.$id.'" and id2="1"'))
					{
					?>
						<div class="message">Your reply has successfully been sent.<br />
						<a id="forum_a" href="read_pm.php?id=<?php echo $id; ?>">Go to the PM</a></div>
					<?php
					}
					else
					{
					?>
						<div class="message">An error occurred while sending the reply.<br />
						<a id="forum_a" href="read_pm.php?id=<?php echo $id; ?>">Go to the PM</a></div>
					<?php
					}
				}
			else
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
    						<div class="date">Date sent: <?php echo date('Y/m/d H:i:s' ,					$dn2['timestamp']); ?>
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
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
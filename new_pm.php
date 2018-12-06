<?php
//This page let create a new personnal message
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
        <title>New PM</title>
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
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
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
        <title>Add a reply - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
		<script type="text/javascript" src="functions.js"></script>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$dn1['parent'].'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;<a id="forum_a" href="read_topic.php?id='.$id. '">'.htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8').'</a>&nbsp;&gt;&nbsp;Add a reply';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>

				<?php
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
				else
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
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
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
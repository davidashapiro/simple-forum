<?php
//This page display a topic
include('config.php');
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	$stmt = $db->query('select count(t.id) as nb1, t.title, t.parent, count(t2.id) as nb2, c.name from topics as t, topics as t2, categories as c where t.id="'.$id.'" and t.id2=1 and t2.id="'.$id.'" and c.id=t.parent group by t.id');
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
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$dn1['parent'].'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a> &gt; Read the topic';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>

				<h1><?php echo $dn1['title']; ?></h1>
				<?php if(isset($_SESSION['username'])) { ?>
					<a id="forum_a" href="new_reply.php?id=<?php echo $id; ?>" class="button">Reply</a>
				<?php
				}
				$dn2 = $db->query('select t.id2, t.authorid, t.message, t.timestamp, u.username as author, u.avatar from topics as t, users as u where t.id="'.$id.'" and u.id=t.authorid order by t.timestamp asc');
				?>
				<table id="forum_table" class="messages_table">
					<tr>
    					<th class="author">Author</th>
    					<th>Message</th>
					</tr>
					<?php
					while($dnn2 = $dn2->fetch())
					{
					?>
					<tr>
    					<td class="author center">
    						<?php
							if($dnn2['avatar']!='')
							{
								echo '<img src="'.htmlentities($dnn2['avatar']).'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
							}
							?>
							<br />
							<a id="forum_a" href="profile.php?id=<?php echo $dnn2['authorid']; ?>">
								<?php echo $dnn2['author']; ?></a>
						</td>
    					<td class="left">
				    		
				    			<div class="date">Date sent: <?php echo date('m/d/Y H:i:s' ,$dnn2['timestamp']); ?>
				    			</div>
				    			<div class="clean"></div>
    							<?php echo $dnn2['message']; ?>
    						<?php if(isset($_SESSION['username']) and ($_SESSION['username']==$dnn2['author'] or $_SESSION['username']==$admin)){ ?>
				    			<div class="edit" style="float:right; ">
				    				<a id="forum_a" href="edit_message.php?id=<?php echo $id; ?>&id2=<?php echo $dnn2['id2']; ?>">
				    				<img src="<?php echo $design; ?>/images/edit.png" alt="Edit" /></a>
				    			</div>
				    		<?php } ?>
    					</td>
    				</tr>
					<?php } ?>
				</table>
				<?php if(isset($_SESSION['username'])) { ?>
					<a id="forum_a" href="new_reply.php?id=<?php echo $id; ?>" class="button">Reply</a>
				<?php } ?>
			</div>
		</span>
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
<?php
	}
	else
	{
		echo '<h2>This topic doesn\'t exist.</h2>';
	}
}
else
{
	echo '<h2>The ID of this topic is not defined.</h2>';
}
?>
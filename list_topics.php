<?php
//This page let display the list of topics of a category
include('config.php');
if(isset($_GET['parent']))
{
	$id = intval($_GET['parent']);
	$stmt = $db->query('select count(c.id) as nb1, c.name,count(t.id) as topics from categories as c left join topics as t on t.parent="'.$id.'" where c.id="'.$id.'" group by c.id');
	$dn1 = $stmt->fetch();
	if($dn1['nb1']>0)
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include 'header0.php'; ?>
        <title><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    </head>
    <body id="forum_body">
    	<script type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
        	<div class="content">
        		<?php
				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;<a id="forum_a" href="list_topics.php?parent='.$id.'">'.htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8').'</a>';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
					//echo 'logged in is set';
				}
				else {
					shownotloggedintoprightbox();
					//echo 'loggedin is not set';
				} ?>
				<?php if(isset($_SESSION['username'])) { ?>
					<a id="forum_a" href="new_topic.php?parent=<?php echo $id; ?>" class="button">New Topic</a>
				<?php
				}
				$dn2 = $db->query('select t.id, t.title, t.authorid, u.username as author, count(r.id) as replies from topics as t left join topics as r on r.parent="'.$id.'" and r.id=t.id and r.id2!=1  left join users as u on u.id=t.authorid where t.parent="'.$id.'" and t.id2=1 group by t.id order by t.timestamp2 desc');
				if($dn2->rowCount() > 0) { ?>
					<table id="forum_table" class="topics_table">
						<tr>
    						<th class="forum_tops">Topic</th>
    						<th class="forum_auth">Author</th>
    						<th class="forum_nrep">Replies</th>
							<?php if(isset($_SESSION['username']) and $_SESSION['username']==$admin) { ?>
    							<th class="forum_act">Action</th>
							<?php } ?>
						</tr>
						<?php while($dnn2 = $dn2->fetch()) { ?>
						<tr>
    						<td class="forum_tops">
    							<a id="forum_a" href="read_topic.php?id=<?php echo $dnn2['id']; ?>">
    								<?php echo htmlentities($dnn2['title'], ENT_QUOTES, 'UTF-8'); ?></a>
    						</td>
    						<td>
    							<a id="forum_a" href="profile.php?id=<?php echo $dnn2['authorid']; ?>">
    							<?php echo htmlentities($dnn2['author'], ENT_QUOTES, 'UTF-8'); ?></a>
    						</td>
    						<td><?php echo $dnn2['replies']; ?></td>
							<?php if(isset($_SESSION['username']) and $_SESSION['username']==$admin) { ?>
    							<td>
    								<a id="forum_a" href="delete_topic.php?id=<?php echo $dnn2['id']; ?>">
    									<img src="<?php echo $design; ?>/images/delete.png" alt="Delete" /></a>
    							</td>
							<?php } ?>
						</tr>
						<?php } ?>
					</table>
				<?php } else { ?>
					<div class="message">This category has no topic.</div>
				<?php } if(isset($_SESSION['username'])) { ?>
					<a id="forum_a" href="new_topic.php?parent=<?php echo $id; ?>" class="button">New Topic</a>
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
		echo '<h2>This category doesn\'t exist.</h2>';
	}
}
else
{
	echo '<h2>The ID of the category you want to visit is not defined.</h2>';
}
?>
<?php
//This page let display the list of personnal message of an user
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Personal Messages</title>
		<?php include '../profile/header0.php';
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
				if(isset($_SESSION['username']))
				{
					$req1 = $db->query('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['userid'].'" and m1.user1read="no" and users.id=m1.user2) or (m1.user2="'.$_SESSION['userid'].'" and m1.user2read="no" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');
					$req2 = $db->query('select m1.id, m1.title, m1.timestamp, count(m2.id) as reps, users.id as userid, users.username from pm as m1, pm as m2,users where ((m1.user1="'.$_SESSION['userid'].'" and m1.user1read="yes" and users.id=m1.user2) or (m1.user2="'.$_SESSION['userid'].'" and m1.user2read="yes" and users.id=m1.user1)) and m1.id2="1" and m2.id=m1.id group by m1.id order by m1.id desc');

				include 'showtoprightbox.php';
				$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>&nbsp;'.'&gt;&nbsp;List of your personal messages';
				if (isset($_SESSION['loggedin']))
				{
					showtopleftbox($breadcrumbs);
					showtoprightbox($db);
				}
				else {
					shownotloggedintoprightbox();
				} ?>
				This is the list of your personal messages:<br />
				<a id="forum_a" href="new_pm.php" class="button">New Personal Message</a><br />
				<h3>Unread messages(<?php echo intval($req1->rowCount()); ?>):</h3>
				<table id="forum_table" class="list_pm">
					<tr>
				    	<th class="title_cell">Title</th>
				        <th>Nb. Replies</th>
				        <th>Participant</th>
				        <th>Date Sent</th>
				    </tr>
				<?php
				while($dn1 = $req1->fetch())
				{
				?>
					<tr>
				    	<td class="left"><a id="forum_a" href="read_pm.php?id=<?php echo $dn1['id']; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    	<td><?php echo $dn1['reps']-1; ?></td>
				    	<td><a id="forum_a" href="profile.php?id=<?php echo $dn1['userid']; ?>"><?php echo htmlentities($dn1['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    	<td><?php echo date('m/d/Y H:i:s' ,$dn1['timestamp']); ?></td>
				    </tr>
				<?php
				}
				if(intval($req1->rowCount())==0)
				{
				?>
					<tr>
				    	<td colspan="4" class="center">You have no unread messages.</td>
				    </tr>
				<?php
				}
				?>
				</table>
				<br />
				<h3>Read messages(<?php echo intval($req2->rowCount()); ?>):</h3>
				<table id="forum_table" class="list_pm">
					<tr>
				    	<th class="title_cell">Title</th>
				        <th>Nb. Rreplies</th>
				        <th>Participant</th>
				        <th>Date Sent</th>
				    </tr>
				<?php
				while($dn2 = $req2->fetch())
				{
				?>
					<tr>
				    	<td class="left"><a id="forum_a" href="read_pm.php?id=<?php echo $dn2['id']; ?>"><?php echo htmlentities($dn2['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    	<td><?php echo $dn2['reps']-1; ?></td>
				    	<td><a id="forum_a" href="profile.php?id=<?php echo $dn2['userid']; ?>"><?php echo htmlentities($dn2['username'], ENT_QUOTES, 'UTF-8'); ?></a></td>
				    	<td><?php echo date('m/d/Y H:i:s' ,$dn2['timestamp']); ?></td>
				    </tr>
				<?php
				}
				if(intval($req2->rowCount())==0)
				{
				?>
					<tr>
				    	<td colspan="4" class="center">You have no read messages.</td>
				    </tr>
				<?php
				}
				?>
				</table>
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
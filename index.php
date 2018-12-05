<?php
//This page displays the list of the forum's categories
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
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Forum</title>
    </head>
    <body id="forum_body">
    	<script type='text/javascript' src='/profile/scripts/header_part1.js'></script>
		<script type='text/javascript' src='/profile/scripts/topmenu.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part2.js'></script>
		<script type='text/javascript' src='/profile/scripts/header_part3.js'></script>
		<span>
	        <div class="content">
			<?php
			if(isset($_SESSION['username']))
			{
				try {
					$stmt = $db->prepare('select count(*) as nb_new_pm from pm where ((user1=:user1 and user1read="no") or (user2=:user2 and user2read="no")) and id2="1"');
					$stmt->execute(array(':user1' => $_SESSION['userid'],
										':user2' => $_SESSION['userid']));
					$nb_new_pm = $stmt->fetch();
				}
				catch (PDOException $e) {
					echo $e->getMessage();
				}
				$nb_new_pm = $nb_new_pm['nb_new_pm'];
			?>
				<div class="box">
					<div class="box_left">
				    	<a id="forum_a" href="<?php echo $url_home; ?>">Forum Index</a>
				    </div>
					<div class="box_right">
				    	<a id="forum_a" href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - 
				    	<a id="forum_a" href="profile.php?id=<?php echo $_SESSION['userid']; ?>">
				    	<?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> 
				    	(<a id="forum_a" href="login.php">Logout</a>)
				    </div>
					<div class="clean"></div>
				</div>
				<?php
			}
			else
			{
			?>
				<div class="box">
					<div class="box_left">
				    	<a id="forum_a" href="<?php echo $url_home; ?>">Forum Index</a>
				    </div>
					<div class="box_right">
				    	<a id="forum_a" href="signup.php">Sign Up</a> - 
				    	<a id="forum_a" href="login.php">Login</a>
				    </div>
					<div class="clean"></div>
				</div>
			<?php
			}
			if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
			{
			?>
				<a id="forum_a" href="new_category.php" class="button">New Category</a>
			<?php
			}
			?>
			<table id="forum_table" class="categories_table">
				<tr>
    				<th class="forum_cat">Category</th>
    				<th class="forum_ntop">Topics</th>
    				<th class="forum_nrep">Replies</th>
			<?php
			if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
			{
			?>
			    	<th class="forum_act">Action</th>
			<?php
			}
			?>
				</tr>
				<?php
				try {
					$dn1 = $db->prepare('select c.id, c.name, c.description, c.position, (select count(t.id) from topics as t where t.parent=c.id and t.id2=1) as topics, (select count(t2.id) from topics as t2 where t2.parent=c.id and t2.id2!=1) as replies from categories as c group by c.id order by c.position asc');
					$dn1->execute();
					$nb_cats = $dn1->rowCount();
					while($dnn1 = $dn1->fetch())
					{
				?>
				<tr>
    				<td class="forum_cat left">
    					<a id="forum_a" href="list_topics.php?parent=<?php echo $dnn1['id']; ?>" class="title"><?php echo htmlentities($dnn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
        				<div class="description left"><?php echo $dnn1['description']; ?></div></td>
    				<td><?php echo $dnn1['topics']; ?></td>
    				<td><?php echo $dnn1['replies']; ?></td>
					<?php
					if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
					{
					?>
    					<td><a id="forum_a" href="delete_category.php?id=<?php echo $dnn1['id']; ?>">
    						<img src="<?php echo $design; ?>/images/delete.png" alt="Delete" /></a>
						<?php if($dnn1['position']>1){ ?>
							<a id="forum_a" href="move_category.php?action=up&id=<?php echo $dnn1['id']; ?>">
							<img src="<?php echo $design; ?>/images/up.png" alt="Move Up" /></a>
						<?php } ?>
						<?php if($dnn1['position']<$nb_cats){ ?>
							<a id="forum_a" href="move_category.php?action=down&id=<?php echo $dnn1['id']; ?>">
							<img src="<?php echo $design; ?>/images/down.png" alt="Move Down" /></a>
						<?php } ?>
						<a id="forum_a" href="edit_category.php?id=<?php echo $dnn1['id']; ?>">
						<img src="<?php echo $design; ?>/images/edit.png" alt="Edit" /></a>
					</td>
					<?php } ?>
				</tr>
					<?php } ?>
				<?php } catch (PDOException $e) {
					echo $e->getMessage();
				} ?>
			</table>
			<?php
			if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
			{
			?>
				<a id="forum_a" href="new_category.php" class="button">New Category</a>
			<?php
			}
			if(!isset($_SESSION['username']))
			{
			?>
				<div class="box_login">
					<form id="forum_form" action="login.php" method="post">
						<label for="username">Username</label>
						<input type="text" name="username" id="username" /><br />
						<label for="password">Password</label>
						<input type="password" name="password" id="password" /><br />
			        	<label for="memorize">Remember</label>
			        	<input type="checkbox" name="memorize" id="memorize" value="yes" />
			        	<div class="center">
				        	<input type="submit" value="Login" /> 
				        	<input type="button" onclick="javascript:document.location='signup.php';" value="Sign Up" />
			        	</div>
			    	</form>
				</div>
			<?php
			}
			?>
			</div>
		</span>
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
<?php
//This page displays the list of the forum's categories
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php include 'header0.php'; ?>
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
			include 'showtoprightbox.php';
			$breadcrumbs = '<a id="forum_a" href="index.php">Forum Index</a>';
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
			<?php
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
			
			<?php
			}
			?>
			</div>
		</span>
		<script type='text/javascript' src='/profile/scripts/footer.js'></script>
	</body>
</html>
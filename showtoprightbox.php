<?php
$nb_new_pm = 0;
function getpms($db) {
	if(isset($_SESSION['username']) && ($_SESSION['loggedin'] == true))
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
		return $nb_new_pm['nb_new_pm'];
	}
}
function showtopleftbox($breadcrumbs) {
?>
	<div class="box">
		<div class="box_left">
			<?php echo $breadcrumbs; ?>
	    </div>
<?php } 
function showtoprightbox($db) {
?>
		<div class="box_right">
	    	<a id="forum_a" href="list_pm.php">Your messages(<?php echo getpms($db); ?>)</a> - 
	    	<a id="forum_a" href="profile.php?id=<?php echo $_SESSION['userid']; ?>">
	    	<?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> 
	    	(<a id="forum_a" href="logout.php">Logout</a>)
	    </div>
		<div class="clean"></div>
	</div>
<?php } ?>
<?php
function shownotloggedintoprightbox() {
?>
	<div class="box">
		<div class="box_left">
	    	<a id="forum_a" href="index.php">Forum Index</a>
	    </div>
		<div class="box_right">
	    	<a id="forum_a" href="signup.php">Sign Up</a> - 
	    	<a id="forum_a" href="login.php?page=forum">Login</a>
	    </div>
		<div class="clean"></div>
	</div>
<?php } ?>
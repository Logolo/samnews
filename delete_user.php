<?php include('config.php');

// query user information
$user_result = samq("users","id,login,perm_mod,perm_admin",NULL,"login = '" . esc($_REQUEST['user']) . "'");

if (count($user_result) > 0) {
	// prevent unauthorized or mods editing mods/admins
	if( isset($_SESSION['access']) && (($_SESSION['access'] == 2 && $user_result[0]['perm_mod'] != 1 && $user_result[0]['perm_admin'] != 1) || $_SESSION['access'] == 3)) {
	
		// handle form submit
		if(isset($_POST['delete'])) {
			// change users votes
			samq_u("vote_comment",array("userid"),array(5),"userid = " . $user_result[0]['id']);
			samq_u("vote_post",array("userid"),array(5),"userid = " . $user_result[0]['id']);

			// change users comments
			samq_u("comment",array("author"),array(5),"author = " . $user_result[0]['id']);

			// change users posts
			samq_u("post",array("author"),array(5),"author = " . $user_result[0]['id']);
	
			// delete user record
			samq_d("users","id = " . $user_result[0]['id']);

			$success = "user has been deleted";
		}
		
		include('head.php');
		?>
		
		<br />
		
		<div class="content">
		<span class="page_title">delete user</span><br />
		
		<?php // echo success message
		if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "'>done</a>"; } else { ?>
			<!--  jQuery timed button -->
			<script type="text/javascript">
			$(function() {
				$('#delete').timedDisable();
			});
			</script>
			<br />
			<div class="error">are you sure you want to delete "<?php echo $user_result[0]['login']; ?>"?</div>
			<br />
			<form method="post" action="<?php echo SITE_URL; ?>/delete/u/<?php echo $user_result[0]['login']; ?>">
					<input type="button" value="cancel" onClick="location.href='<?php echo SITE_URL . "/u/" . $user_result[0]['login']; ?>'" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="delete" id="delete" value="DELETE" />
			</form>
				<br /><br />
		<?php } ?>
		</div>
		
		<br /><br />
	
	<?php
	include('foot.php');
	
	} else {
		header("Location: " . SITE_URL);
	}
} else { ?>
	Invalid user
<?php } ?>
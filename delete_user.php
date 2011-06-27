<?php /*====================================================================================
		SamNews [http://samjlevy.com/samnews], open-source PHP social news application
    	sam j levy [http://samjlevy.com]

    	This program is free software: you can redistribute it and/or modify it under the
    	terms of the GNU General Public License as published by the Free Software
    	Foundation, either version 3 of the License, or (at your option) any later
    	version.

    	This program is distributed in the hope that it will be useful, but WITHOUT ANY
    	WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
    	PARTICULAR PURPOSE.  See the GNU General Public License for more details.

    	You should have received a copy of the GNU General Public License along with this
    	program.  If not, see <http://www.gnu.org/licenses/>.
      ====================================================================================*/

include('config.php');

// query user information
$user_result = samq("users","id,login,perm_mod,perm_admin",NULL,"login = '" . esc($_GET['user']) . "'");

if (count($user_result) > 0) {

    // query ID of [delete]
    $delete_result = samq("users","id",NULL,"login = '[deleted]'");

	// prevent unauthorized or mods editing mods/admins
	if( isset($_SESSION['access']) && (($_SESSION['access'] == 2 && $user_result[0]['perm_mod'] != 1 && $user_result[0]['perm_admin'] != 1) || $_SESSION['access'] == 3) && $user_result[0]['login'] != "[deleted]" && $user_result[0]['login'] != $_SESSION['user']) {
	
		// handle form submit
		if(isset($_POST['delete'])) {
			// change users votes
			samq_u("vote_comment",array("userid"),array($delete_result[0]['id']),"userid = " . $user_result[0]['id']);
			samq_u("vote_post",array("userid"),array($delete_result[0]['id']),"userid = " . $user_result[0]['id']);

			// change users comments
			samq_u("comment",array("author"),array($delete_result[0]['id']),"author = " . $user_result[0]['id']);

			// change users posts
			samq_u("post",array("author"),array($delete_result[0]['id']),"author = " . $user_result[0]['id']);
	
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
		if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/ulist'>done</a>"; } else { ?>
			<!--  jQuery timed button -->
			<script type="text/javascript">
			jQuery(function() {
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
		die();
	}
} else { ?>
	Invalid user
<?php } ?>
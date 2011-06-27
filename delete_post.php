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

// query post information
$post_result = samq("post","id,title,slug,author",NULL,"id = " . esc($_GET['post']));

if (count($post_result) > 0) {
	// prevent unauthorized or mods editing mods/admins
	if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) {
	
		// handle form submit
		if(isset($_POST['delete'])) {
			// delete votes
			samq_d("vote_post","post = " . esc($_GET['post']));
			
			// subtract from comment counts
				// find and update totals for users who have related comments
				foreach(samq("users","users.id","INNER JOIN comment ON users.id = comment.author","comment.post = " . esc($_GET['post'])) as $e) {
					samq_c("UPDATE users SET comment_count = comment_count - 1 WHERE id = " . $e['id']);
				}
			
			// delete comment votes
			samq_c("DELETE vote_comment FROM vote_comment INNER JOIN comment ON comment.id = vote_comment.comment WHERE comment.post = " . esc($_GET['post']));
			
			// subtract from author's post count
			samq_c("UPDATE users INNER JOIN post ON users.id = post.author SET post_count = post_count - 1 WHERE post.id = " . esc($_GET['post']));

			// delete comments
			samq_d("comment","post = " . esc($_GET['post']));
			
			// delete post
			samq_d("post","id = " . esc($_GET['post']));
			
			$success = "post has been deleted";
		}
		
		include('head.php');
		?>
		
		<br />

		<div class="content">
		<span class="page_title">delete post</span><br />
		
		<?php // echo success message
		if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "'>done</a>"; } else { ?>
			<!--  jQuery timed button -->
			<script type="text/javascript">
			jQuery(function() {
				$('#delete').timedDisable();
			});
			</script>
			<br />
			<div class="error">are you sure you want to delete "<?php echo $post_result[0]['title']; ?>"?</div>
			<br />
			<form method="post" action="<?php echo SITE_URL; ?>/delete/p/<?php echo $post_result[0]['id']; ?>">
					<input type="button" value="cancel" onClick="location.href='<?php echo SITE_URL . "/v/" . $post_result[0]['slug']; ?>'" />
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
	Invalid post
<?php } ?>
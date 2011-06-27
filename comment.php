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

// check to make sure user is logged in
if(isset($_SESSION['user'],$_POST['post_id'],$_POST['post_slug'],$_POST['comment_input'])) {

	if(($_SESSION['access'] == 2 || $_SESSION['access'] == 3) && isset($_POST['edit'])) {
		// set comment id
		$comment_id = $_POST['edit'];
		
		if (isset($_POST['delete'])) {
			// count and subtract deletions from post's comment_count
			$comment_subtract = samq("comment","count(id) AS comment_subtract",NULL,"thread = " . esc($comment_id) . " OR id = " . esc($comment_id));

			// update post's comment count
			samq_c("UPDATE post SET comment_count = comment_count - " . ($comment_subtract[0]['comment_subtract']) . " WHERE id = " . esc($_POST['post_id']));
			
			// subtract from comment counts
				// find and update totals for users who have related comments
				foreach(samq("users","users.id","INNER JOIN comment ON users.id = comment.author","thread = " . esc($comment_id)) as $e) {
					samq_c("UPDATE users SET comment_count = comment_count - 1 WHERE id = " . $e['id']);
				}

			// subtract from this author's comment count
			samq_c("UPDATE users INNER JOIN comment ON users.id = comment.author SET comment_count = comment_count - 1 WHERE comment.id = " . esc($comment_id));
			
			// delete comment votes
			samq_d("vote_comment","comment = " . esc($comment_id));
			samq_c("DELETE vote_comment FROM vote_comment INNER JOIN comment ON comment.id = vote_comment.comment WHERE comment.thread = " . esc($comment_id));
			
			// delete comment replies
			samq_d("comment","thread = " . esc($comment_id));
			
			// delete comment
			samq_d("comment","id = " . esc($comment_id));
			
			// redirect post
			header("Location: " . SITE_URL . "/v/" . $_POST['post_slug']);
			die();
			
		} else {
			// update comment
			samq_u("comment",array("text"),array($_POST['comment_input']),"id = " . esc($comment_id));
			
			// redirect to comment
			header("Location: " . SITE_URL . "/v/" . $_POST['post_slug'] . "#comment" . $comment_id);
			die();
		}

	} else {

		// set post and thread id
		if(isset($_POST['comment_thread']) && trim($_POST['comment_thread']) != "") $thread_id = $_POST['comment_thread']; else $thread_id = NULL;
	
		// record users comment
		samq_i("comment",array("post","thread","text","author","score","ip","created"),array($_POST['post_id'],$thread_id,$_POST['comment_input'],$_SESSION['user_id'],1,gethostbyaddr($_SERVER['REMOTE_ADDR']),DATETIME_NOW));
	
		$ai_comment_id = mysql_insert_id();
	
		// update the post's comment count
		samq_c("UPDATE post SET comment_count = comment_count + 1 WHERE id = " . esc($_POST['post_id']));
	
		// update user's comment count
		samq_c("UPDATE users SET comment_count = comment_count + 1 WHERE id = " . esc($_SESSION['user_id']));
		
		// redirect to user's comment
		header("Location: " . SITE_URL . "/v/" . $_POST['post_slug'] . "#comment" . $ai_comment_id);
		die();
	}

} else {
	header("Location: " . SITE_URL);
	die();
}
?>
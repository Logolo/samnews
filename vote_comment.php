<?php include('config.php');

// check to make sure user is logged in
if(isset($_SESSION['user'],$_POST['id'])) {

	$comment_id = esc($_POST['id']);
	
	// check to make sure user isn't author of comment
	$author_check = samq("comment","author",NULL,"author = " . esc($_SESSION['user_id']) . " AND id = " . $comment_id);

	// check to make sure user hasn't already voted
	$vote_check = samq("vote_comment","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND comment = " . $comment_id);

	if(count($author_check) == 0 && count($vote_check) == 0) {

		// retrieve number of votes for this comment
		$current_count = samq("comment","score",NULL,"id = " . $comment_id);
		$current_count = $current_count[0]['score'];

		// record users vote
		samq_i("vote_comment",array("comment","userid","created"),array($comment_id,$_SESSION['user_id'],DATETIME_NOW));

		// update the vote
		if(samq_c("UPDATE comment SET score = score + 1 WHERE id = " . $comment_id)) {
			
			// voting done
			echo $current_count + 1;
		}
		else {
			echo "Voting failed";
		}
	}
} else {
	header("Location: " . SITE_URL);	
}
?>
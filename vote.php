<?php include('config.php');

// check to make sure user is logged in
if(isset($_SESSION['user'],$_POST['id'])) {

	$post_id = esc($_POST['id']);
	
	// check to make sure user isn't author of post
	$author_check = samq("post","author",NULL,"author = " . esc($_SESSION['user_id']) . " AND id = " . $post_id);

	// check to make sure user hasn't already voted
	$vote_check = samq("vote_post","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND post = " . $post_id);

	if(count($author_check) == 0 && count($vote_check) == 0) {

		// retrieve number of votes for this post
		$current_count = samq("post","score",NULL,"id = " . $post_id);
		$current_count = $current_count[0]['score'];
		
		// update authors voted count
		samq_c("UPDATE users INNER JOIN post ON users.id = author SET voted_count = voted_count + 1 WHERE post.id = " . $post_id);

		// record users vote
		samq_i("vote_post",array("post","userid","created"),array($post_id,$_SESSION['user_id'],DATETIME_NOW));

		// update user's vote count
		samq_c("UPDATE users SET vote_count = vote_count + 1 WHERE id = " . esc($_SESSION['user_id']));

		// update the vote
		if(samq_c("UPDATE post SET score = score + 1 WHERE id = " . $post_id)) {
			
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
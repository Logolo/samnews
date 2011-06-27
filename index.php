<?php include('config.php');
include(INCLUDES_PATH . "neat_trim.php");

// find limit numbers
if(!isset($_REQUEST['p'])) {
	$current_page = 1;
	$start = 0;
} else {
	$current_page = $_REQUEST['p'];
	$start = ($current_page - 1) * INDEX_DISPLAY;
}
$end = INDEX_DISPLAY;

if(isset($_REQUEST['mode'])) {

	if($_REQUEST['mode'] == "all-time") {
	// all-time
	
		// count records
		$index_count_result = samq_c("SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id ORDER BY score DESC, post.created DESC",1);

		// get records
		$index = samq_c("SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id ORDER BY score DESC, post.created DESC	LIMIT " . $start . ", " . $end,1);
		
		// set page head
		$page_head = "all-time";

	} elseif($_REQUEST['mode'] == "new") {
	// new
		// count records
		$index_count_result = samq_c("SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id ORDER BY post.created DESC",1);

		// get records
		$index = samq_c("SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id ORDER BY post.created DESC LIMIT " . $start . ", " . $end,1);
		
		// set page head
		$page_head = "newest";
	} elseif($_REQUEST['mode'] == "submit" && isset($_REQUEST['user'])) {
	// user submissions
		// count records
		$index_count_result = samq_c("SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id WHERE login = '" . esc($_REQUEST['user']) . "' ORDER BY post.created DESC",1);

		// get records
		$index = samq_c("SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id WHERE login = '" . esc($_REQUEST['user']) . "'" . "ORDER BY post.created DESC LIMIT " . $start . ", " . $end,1);
		
		// set page head
		$page_head = trim($_REQUEST['user']) . "'s submissions";
	} elseif($_REQUEST['mode'] == "vote" && isset($_REQUEST['user'])) {
	// user voted
		// query user_id
		$vote_userid = samq("users","id",NULL,"login = '" . esc($_REQUEST['user']) . "'");

		// count records
		$index_count_result = samq_c("SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id INNER JOIN vote_post ON post.id = vote_post.post WHERE vote_post.userid = " . $vote_userid[0]['id'] . " ORDER BY post.created DESC",1);

		// get records
		$index = samq_c("SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id INNER JOIN vote_post ON post.id = vote_post.post WHERE vote_post.userid = " . $vote_userid[0]['id'] . " ORDER BY post.created DESC LIMIT " . $start . ", " . $end,1);

		// set page head
		$page_head = trim($_REQUEST['user']) . "'s voted";
	}
} else {
	// regular
		// count records
		$index_count_result = samq_c("SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id WHERE post.created >= DATE_SUB(NOW(),INTERVAL 5 day) ORDER BY score DESC, post.created DESC",1);
	
		// get records
		$index = samq_c("SELECT post.id, title, users.id AS author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id WHERE post.created >= DATE_SUB(NOW(),INTERVAL " . INDEX_INTERVAL . ") ORDER BY score DESC, post.created DESC LIMIT " . $start . ", " . $end,1);
}

include('head.php');

if(count($index) > 0) {

	// calculate total number of pages
	$index_count = $index_count_result[0]['index_count'];
	$pages = intval($index_count/INDEX_DISPLAY);
	if($index_count % INDEX_DISPLAY) $pages++; // add page if remainder

	if(isset($page_head)) { ?>
    <br /><div class="content">
    <span class="page_title"><?php echo $page_head; ?></span><br />
    </div><?php } ?>

<!--sphider_noindex-->

    <table class="listing_table">
        <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
    
        <?php
		$i = $start + 1;
        foreach ($index as $e) {
			// check to see if user is logged in
			if(isset($_SESSION['user'])) {
				// see if user has submitted this entry
				if($_SESSION['user_id'] == $e['author_id']) {
					$vote_check = "vote";
				} elseif(count(samq("vote_post","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND post = " . $e['id'])) > 0) {
					$vote_check = "vote";
				} else {
					$vote_check = "novote";
				}
			} else {
				// user is not logged in, flag to remove hyperlink from vote arrow
				$vote_check = "guest";
			} ?>
            <tr class="listing_top_tr">
                <td class="listing_left_spacer1_td"></td>
                <td class="listing_count_td"><span class="listing_count"><?php echo $i; ?></span></td>
                <td class="listing_left_spacer2_td"></td>
                <td class="listing_votes_td"><span class="listing_votes_outer" id="listing_votes_outer<?php echo $e['id']; ?>"><a href="<?php echo SITE_URL; ?>/v/<?php echo $e['slug']; ?>"><span id="listing_votes_inner<?php echo $e['id']; ?>"><?php echo $e['post_score']; ?></span></a></span></td>
                <td class="listing_up_td">
                    <span<?php if($vote_check == "vote") echo " style='display:none;'"; ?> id="vote_arrow<?php echo $e['id']; ?>">
                        <?php if($vote_check != "guest") { ?><a href="#" class="vote_up" id="<?php echo $e['id']; ?>"><?php } ?>
                            <img src="<?php echo IMAGES_PATH; ?>up.gif" alt="vote up" />
                        <?php if($vote_check != "guest") { ?></a><?php } ?>
                    </span>
                    <span<?php if($vote_check != "vote" || $vote_check == "guest") echo " style='display:none;'"; ?> id="voted_arrow<?php echo $e['id']; ?>">
                        <img src="<?php echo IMAGES_PATH; ?>up_voted.gif" alt="voted up" />
                    </span>
                </td>
                <td><span class="listing_title"><a href="<?php echo htmlentities($e['url']); ?>" target="_blank"<?php if(isset($e['description'])) { ?> class="tooltip" title="<?php echo htmlspecialchars(neat_trim($e['description'],300), ENT_QUOTES); ?>"<?php } ?>><?php echo $e['title']; ?></a></span></td></tr>
                <tr><td colspan="5"></td><td><span class="listing_details">
                submitted <?php echo time_since(strtotime($e['created'])); ?> by <a href="<?php echo SITE_URL; ?>/u/<?php echo $e['login']; ?>"><?php echo $e['login']; ?></a>(<?php echo $e['user_score']; ?>) | <span class="listing_domain"><?php echo $e['domain']; ?></span> | <a href="<?php echo SITE_URL; ?>/v/<?php echo $e['slug']; ?>"><?php if($e['comment_count'] == 0) { echo "no comments"; } else { echo $e['comment_count'] . " comment" . (($e['comment_count'] > 1) ? "s" : ""); } ?></a></span></td></tr>

            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>         
        <?php $i++; } ?>
        
        <?php if($pages > 1) { ?>
        <tr><td colspan="5"></td><td><span class="listing_title">
			<?php // compute url
			if(!isset($_REQUEST['mode'])) { $url = SITE_URL; } elseif($_REQUEST['mode'] == "submit") { $url = SITE_URL . "/submissions/" . $_REQUEST['user']; } else { $url = SITE_URL . "/" . $_REQUEST['mode']; }

            // previous link
			if($current_page != 1) {
				echo "<a href='";
				if($current_page == 2) echo $url; else echo $url . "/p/" . ($current_page - 1);
				echo "'>prev</a>";
			}
			
			// echo divider if both prev and more are visible
			if($current_page != 1 && $current_page != $pages) {
			echo " | ";	
			}

			// next link
			if($current_page != $pages) {
				echo "<a href='" . $url . "/p/" . ($current_page + 1) . "'>more</a>";
			} ?>
			</span>
		</td></tr>
        <?php } ?>
        
        <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
    </table>
    
    <!--/sphider_noindex-->
<?php } else { ?>
	<div class="content"><br />no results</div><br /><br />
<?php }
include('foot.php'); ?>
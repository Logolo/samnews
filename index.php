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
include(INCLUDES_PATH . "neat_trim.php");

// compute url
$url = SITE_URL;
$feed_url = SITE_URL . "/rss";
if(!isset($_GET['mode']) && isset($_GET['cat'])) {
	$url .= "/cat/" . htmlentities($_GET['cat']);
	$feed_url .= "/cat/" . htmlentities($_GET['cat']);
}
if(isset($_GET['mode'])) {
	if($_GET['mode'] == "submit") {
		$url .= "/submissions/" . htmlentities($_GET['user']);
		$feed_url .= "/submissions/" . htmlentities($_GET['user']);
	}
	elseif($_GET['mode'] == "vote") {
		$url .= "/voted/" . htmlentities($_GET['user']);
		$feed_url .= "/voted/" . htmlentities($_GET['user']);
	}
	else {
		$url .= "/" . htmlentities($_GET['mode']);
		$feed_url .= "/" . htmlentities($_GET['mode']);
	}
	if(isset($_GET['cat'])) {
		$url .= "/cat/" . htmlentities($_GET['cat']);
		$feed_url .= "/cat/" . htmlentities($_GET['cat']);
	}
}

$first_half = "SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, 
				users.voted_count AS user_score, 
				post.score AS post_score, 
				post.created, 
				category.name AS cat_name
				FROM post 
				INNER JOIN users ON author = users.id 
				INNER JOIN category ON category = category.id";
$first_half_count = "SELECT count(post.id) AS index_count FROM post INNER JOIN users ON author = users.id INNER JOIN category ON category = category.id";

// find limit numbers
if(!isset($_GET['p'])) {
	$current_page = 1;
	$start = 0;
} else {
	$current_page = $_GET['p'];
	$start = ($current_page - 1) * INDEX_DISPLAY;
}
$end = INDEX_DISPLAY;

if(isset($_GET['mode'])) {

	if($_GET['mode'] == "all-time") {
	// all-time
	
		// count records
		$index_count_result = samq_c($first_half_count . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY score DESC, post.created DESC",1);

		// get records
		$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY score DESC, post.created DESC	LIMIT " . esc($start) . ", " . esc($end),1);
		
		// set page head
		$page_head = "all-time";

	} elseif($_GET['mode'] == "new") {
	// new
		// count records
		$index_count_result = samq_c($first_half_count . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC",1);

		// get records
		$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT " . esc($start) . ", " . esc($end),1);
		
		// set page head
		$page_head = "newest";
	} elseif($_GET['mode'] == "submit" && isset($_GET['user'])) {
	// user submissions
		// count records
		$index_count_result = samq_c($first_half_count . " WHERE login = '" . esc($_GET['user']) . "'" . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC",1);

		// get records
		$index = samq_c($first_half . " WHERE login = '" . esc($_GET['user']) . "'" . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT " . esc($start) . ", " . esc($end),1);
		
		// set page head
		$page_head = trim($_GET['user']) . "'s submissions";
	} elseif($_GET['mode'] == "vote" && isset($_GET['user'])) {
	// user voted
		// query user_id
		$vote_userid = samq("users","id",NULL,"login = '" . esc($_GET['user']) . "'");

		// count records
		$index_count_result = samq_c($first_half_count . " INNER JOIN vote_post ON post.id = vote_post.post WHERE vote_post.userid = " . $vote_userid[0]['id'] . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" . "'" : "") . " ORDER BY post.created DESC",1);

		// get records
		$index = samq_c($first_half . " INNER JOIN vote_post ON post.id = vote_post.post WHERE vote_post.userid = " . $vote_userid[0]['id'] . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT " . esc($start) . ", " . esc($end),1);

		// set page head
		$page_head = trim($_GET['user']) . "'s voted";
	}
} else {
	// regular
		// count records
		$index_count_result = samq_c($first_half_count . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY CASE WHEN post.created >= DATE_SUB(NOW(),INTERVAL " . INDEX_INTERVAL . ") THEN post.score END DESC, post.created DESC",1);
	
		// get records
		$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY CASE WHEN post.created >= DATE_SUB(NOW(),INTERVAL " . INDEX_INTERVAL . ") THEN post.score END DESC, post.created DESC LIMIT " . esc($start) . ", " . esc($end),1);
}

include('head.php');

if(isset($page_head) || isset($_GET['cat'])) {
	if(isset($_GET['cat'])) {
		if(!isset($page_head)) $page_head = "";
		$page_head .= " " . htmlentities($_GET['cat']);
	} ?>  
	<br /><div class="content">
	<span class="page_title"><?php echo $page_head; ?></span><br />
	</div>
<?php } ?>

<?php if(count($index) > 0) {

	// calculate total number of pages
	$index_count = $index_count_result[0]['index_count'];
	$pages = intval($index_count/INDEX_DISPLAY);
	if($index_count % INDEX_DISPLAY) $pages++; // add page if remainder ?>

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
                <td><span class="listing_title"><a href="<?php echo htmlentities($e['url']); ?>" target="_blank"<?php if(isset($e['description'])) { ?> class="tooltip" title="<?php echo htmlspecialchars(neat_trim($e['description'],300), ENT_QUOTES); ?>"<?php } ?>><?php echo $e['title']; ?></a></span><span class="listing_category"><?php echo $e['cat_name']; ?></span></td></tr>
                <tr><td colspan="5"></td><td><span class="listing_details">
                submitted <?php echo time_since(strtotime($e['created'])); ?> by <a href="<?php echo SITE_URL; ?>/u/<?php echo $e['login']; ?>"><?php echo $e['login']; ?></a>(<?php echo $e['user_score']; ?>) | <span class="listing_domain"><?php echo $e['domain']; ?></span> | <a href="<?php echo SITE_URL; ?>/v/<?php echo $e['slug']; ?>"><?php if($e['comment_count'] == 0) { echo "no comments"; } else { echo $e['comment_count'] . " comment" . (($e['comment_count'] > 1) ? "s" : ""); } ?></a></span></td></tr>

            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>         
        <?php $i++; } ?>
        
        <?php if($pages > 1) { ?>
        <tr><td colspan="5"></td><td><span class="listing_title">
			<?php
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
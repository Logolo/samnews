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

// find limit numbers
if(!isset($_GET['p'])) {
	$current_page = 1;
	$start = 0;
} else {
	$current_page = $_GET['p'];
	$start = ($current_page - 1) * INDEX_DISPLAY;
}
$end = INDEX_DISPLAY;

// query comments
if(isset($_GET['user'])) {
	$comment_count_result = samq("comment","COUNT(comment.id) AS comment_count","INNER JOIN users ON comment.author = users.id","users.login = '" . esc($_GET['user']) . "'","comment.created DESC");
	$comment = samq_c("SELECT comment.id,post,perm_mod,perm_admin,login,thread,text,comment.author,users.voted_count AS user_score,comment.score,comment.created,slug,title FROM comment INNER JOIN users ON author = users.id INNER JOIN post ON comment.post = post.id WHERE users.login = '" . esc($_GET['user']) . "' ORDER BY comment.created DESC LIMIT " . esc($start) . ", " . esc($end),1);
	
	// set page head
	$page_head = trim($_GET['user']) . "'s comments";
} else {
	$comment_count_result = samq("comment","COUNT(comment.id) AS comment_count","INNER JOIN users ON comment.author = users.id",NULL,"comment.created DESC");
	$comment = samq_c("SELECT comment.id,post,perm_mod,perm_admin,login,thread,text,comment.author,users.voted_count AS user_score,comment.score,comment.created,slug,title FROM comment INNER JOIN users ON author = users.id INNER JOIN post ON comment.post = post.id ORDER BY comment.created DESC LIMIT " . esc($start) . ", " . esc($end),1);

	// set page head
	$page_head = "comments";
}

include(INCLUDES_PATH . 'make_clickable.php');
include('head.php');

if(count($comment) > 0) {
	// calculate total number of pages
	$comment_count = $comment_count_result[0]['comment_count'];
	$pages = intval($comment_count/INDEX_DISPLAY);
	if($comment_count % INDEX_DISPLAY) $pages++; // add page if remainder
	?>
	
    <!--sphider_noindex-->
    
	<br /><div class="content">
	<span class="page_title"><?php echo $page_head; ?></span><br />
	<br /></div>

	<div class="comments" style="padding-left:20px;">	
	<?php foreach($comment as $e) {
		// check to see if user is logged in
		if(isset($_SESSION['user'])) {
			// see if user has submitted this entry
			if($_SESSION['user_id'] == $e['author']) {
				$vote_check = "vote";
			} elseif(count(samq("vote_comment","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND comment = " . $e['id'])) > 0) {
				$vote_check = "vote";	
			} else {
				$vote_check = "novote";	
			}
		} else {
			// user is not logged in, flag to remove hyperlink from vote arrow
			$vote_check = "guest";
		} ?>

		<table class="comment_table comment_outer" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
        		<tr class="comment_list_head_tr"><td colspan="4"><a href="<?php echo SITE_URL . "/v/" . $e['slug'] . "#comment" . $e['id']; ?>"><?php echo $e['title']; ?></a></td></tr>
				<tr class="comment_head_tr"><td class="comment_votes_td"><span class="comment_votes_outer" id="comment_votes_outer<?php echo $e['id']; ?>"><a href="<?php echo SITE_URL . "/v/" . $e['slug'] . "#comment" . $e['id']; ?>"><span id="comment_votes_inner<?php echo $e['id']; ?>"><?php echo $e['score']; ?></span></a></span></td>
				<td class="comment_up_td">
				<span<?php if($vote_check == "vote") echo " style='display:none;'"; ?> id="comment_vote_arrow<?php echo $e['id']; ?>">
					<?php if($vote_check != "guest") { ?><a href="#" class="vote_comment_up" id="<?php echo $e['id']; ?>"><?php } ?>
						<img src="<?php echo IMAGES_PATH; ?>up.gif" alt="vote up" />
					<?php if($vote_check != "guest") { ?></a><?php } ?>
				</span>
				<span<?php if($vote_check != "vote" || $vote_check == "guest") echo " style='display:none;'"; ?> id="comment_voted_arrow<?php echo $e['id']; ?>">
					<img src="<?php echo IMAGES_PATH; ?>up_voted.gif" alt="voted up" />
				</span>
			</td>
			<td><span class="comment_author"><a href="<?php echo SITE_URL; ?>/u/<?php echo $e['login']; ?>"><?php echo $e['login'] ?></a></span>(<?php echo $e['user_score']; ?>)<?php if($e['perm_mod'] == 1) echo "(<span class='letter_mod'>m</span>)"; if($e['perm_admin'] == 1) echo "(<span class='letter_admin'>a</span>)"; ?></td><td class="comment_date_td"><?php echo time_since(strtotime($e['created'])); ?></td></tr>
			<tr class="comment_text_outer_tr"><td colspan="4" class="comment_text_td"><div class="comment_text" style="float:left;" id="comment_text<?php echo $e['id']; ?>"><?php echo make_clickable(nl2br(htmlentities($e['text']))); ?></div></td></tr>
		</table>
		<br />
	<?php }
	
	if($pages > 1) { ?>
		<span class="listing_title">
		<?php
		// previous link
		if($current_page != 1) {
			echo "<a href='";
			if($current_page == 2) echo SITE_URL . "/u/" . trim($_GET['user']) . "/comments"; else echo SITE_URL . "/u/" . trim($_GET['user']) . "/comments/p/" . ($current_page - 1);
			echo "'>prev</a>";
		}
		
		// echo divider if both prev and more are visible
		if($current_page != 1 && $current_page != $pages) {
		echo " | ";	
		}
	
		// next link
		if($current_page != $pages) {
			echo "<a href='" . SITE_URL . "/u/" . trim($_GET['user']) . "/comments/p/" . ($current_page + 1) . "'>more</a>";
		} ?>
		</span>
	<?php } ?>
    <br /><br />
   	</div>
    
    <!--/sphider_noindex-->
<?php } else { ?>
	<div class="content"><br />no results</div><br /><br />
<?php }

include('foot.php'); ?>
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
include(INCLUDES_PATH . 'make_clickable.php');

$first_half = "SELECT post.id, title, users.id as author_id, slug, url, domain, description, post.comment_count, login, 
				users.voted_count AS user_score, 
				post.score AS post_score, 
				post.created, 
				category.name AS cat_name
				FROM post 
				INNER JOIN users ON author = users.id 
				INNER JOIN category ON category = category.id";

if(isset($_GET['mode'])) {
	if($_GET['mode'] == "all-time") {
		$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY score DESC, post.created DESC	LIMIT 0, " . RSS_DISPLAY,1);			
		$title = SITE_NAME . " - all-time";
	} elseif($_GET['mode'] == "new") {
		$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
		$title = SITE_NAME . " - newest";
	} elseif($_GET['mode'] == "submit" && isset($_GET['user'])) {
		$index = samq_c($first_half . " WHERE login = '" . esc($_GET['user']) . "'" . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
		$title = SITE_NAME . " - " . trim($_GET['user']) . "'s submissions";
	} elseif($_GET['mode'] == "vote" && isset($_GET['user'])) {
		$vote_userid = samq("users","id",NULL,"login = '" . esc($_GET['user']) . "'");	
		$index = samq_c($first_half . " INNER JOIN vote_post ON post.id = vote_post.post WHERE vote_post.userid = " . $vote_userid[0]['id'] . (isset($_GET['cat']) ? " AND category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
		$title = SITE_NAME . " - " . trim($_GET['user']) . "'s voted";
	}
} else {
	$index = samq_c($first_half . (isset($_GET['cat']) ? " WHERE category.name = '" . esc($_GET['cat']) . "'" : "") . " ORDER BY CASE WHEN post.created >= DATE_SUB(NOW(),INTERVAL " . INDEX_INTERVAL . ") THEN post.score END DESC, post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
	$title = SITE_NAME . ((isset($_GET['cat'])) ? " - " . trim($_GET['cat']) : "");
}

$feed_url = SITE_URL . "/rss";
if(!isset($_GET['mode']) && isset($_GET['cat'])) $feed_url .= "/cat/" . htmlentities($_GET['cat']);
if(isset($_GET['mode'])) {
	if($_GET['mode'] == "submit") $feed_url .= "/submissions/" . htmlentities($_GET['user']);
	elseif($_GET['mode'] == "vote") $feed_url .= "/voted/" . htmlentities($_GET['user']);
	else $feed_url .= "/" . htmlentities($_GET['mode']);
	if(isset($_GET['cat'])) $feed_url .= "/cat/" . htmlentities($_GET['cat']);
}

$rss_head = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?><rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\"><channel><atom:link href=\"" . $feed_url . "\" rel=\"self\" type=\"application/rss+xml\" />";
$rss_foot = "</channel></rss>";

$rss = $rss_head;
$rss.= "<title>" . $title . "</title><link>" . SITE_URL . "</link><description>" . SITE_SLOGAN . "</description><lastBuildDate>" . date("D, d M Y H:i:s") . " " . TIME_ZONE . "</lastBuildDate><language>en-us</language>";
foreach($index as $e) {
	$rss.= "<item><title>" . $e['title'] . "</title><link>" . SITE_URL . "/v/" . $e['slug'] . "</link><guid>" . SITE_URL . "/v/" . $e['slug'] . "</guid><pubDate>" . date("D, d M Y H:i:s",strtotime($e['created'])) . " " . TIME_ZONE . "</pubDate><description><![CDATA[ " . make_clickable(nl2br($e['description'])) . ((isset($e['description'])) ? "<br /><br />" : "") . "<a href='" . SITE_URL . "/cat/" . $e['cat_name'] . "'>" . $e['cat_name'] . "</a> | <a href='" . $e['url'] . "'>" . $e['domain'] . "</a> | <a href='" . SITE_URL . "/v/" . $e['slug'] . "#comment_start'>comments</a> ]]></description></item>";
}
$rss .= $rss_foot;

header("Content-Type: application/xml; charset=ISO-8859-1");

echo $rss;
?>
<?php include('../config.php');
include('../includes/make_clickable.php');

if(isset($_REQUEST['mode'],$_REQUEST['key']) && $_REQUEST['key'] == RSS_KEY) {

	if($_REQUEST['mode'] == "alltime") {
		// all-time
		$title = SITE_NAME . " ALL-TIME";
		$type = "all-time";
		$filename = "rss_alltime.xml";
		$index = samq_c("SELECT post.id, title, users.id AS author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id ORDER BY score DESC, post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
	
	} elseif($_REQUEST['mode'] == "new") {
		// newest
		$title = SITE_NAME . " NEWEST";
		$type = "new";
		$filename = "rss_new.xml";
		$index = samq_c("SELECT post.id, title, users.id AS author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id ORDER BY post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
	
	} elseif($_REQUEST['mode'] == "index") {
		// index
		$title = SITE_NAME;
		$type = "";
		$filename = "rss.xml";
		$index = samq_c("SELECT post.id, title, users.id AS author_id, slug, url, domain, description, post.comment_count, login, users.voted_count AS user_score, post.score AS post_score, post.created FROM post INNER JOIN users ON author = users.id WHERE post.created >= DATE_SUB(NOW(),INTERVAL " . INDEX_INTERVAL . ") ORDER BY score DESC, post.created DESC LIMIT 0, " . RSS_DISPLAY,1);
	}
	
	$rss_head = "<?xml version=\"1.0\" encoding=\"utf-8\"?><rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\"><channel><atom:link href=\"" . SITE_URL . "/rss/" . $type . "\" rel=\"self\" type=\"application/rss+xml\" />";
	$rss_foot = "</channel></rss>";
	
	$rss = $rss_head;
	$rss.= "<title>" . $title . "</title><link>" . SITE_URL . "</link><description>" . SITE_SLOGAN . "</description><lastBuildDate>" . date("D, d M Y H:i:s") . " " . TIME_ZONE . "</lastBuildDate><language>en-us</language>";
	foreach($index as $e) {
		$rss.= "<item><title>" . $e['title'] . "</title><link>" . SITE_URL . "/v/" . $e['slug'] . "</link><guid>" . SITE_URL . "/v/" . $e['slug'] . "</guid><pubDate>" . date("D, d M Y H:i:s",strtotime($e['created'])) . " " . TIME_ZONE . "</pubDate><description><![CDATA[ " . make_clickable(nl2br($e['description'])) . ((isset($e['description'])) ? "<br />" : "") . "[<a href='" . $e['url'] . "'>" . $e['domain'] . "</a>] [<a href='" . SITE_URL . "/v/" . $e['slug'] . "#comment_start'>comments</a>] ]]></description></item>";
	}
	$rss .= $rss_foot;
	
	// write rss
	$fh = fopen("../" . $filename, "w") or die("Can't write'");
	fwrite($fh, $rss);
	fclose($fh);

}
?>
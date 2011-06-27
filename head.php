<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php if(MYSELF == "view") { echo htmlentities($view[0]['description']); } else { echo SITE_DESCRIPTION; } ?>" />
<meta name="keywords" content="<?php echo SITE_KEYWORDS; ?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php echo SITE_URL ?>/style.css" />
<title><?php echo SITE_NAME; ?> | <?php if(MYSELF == "view") { echo $view[0]['title']; } else { echo SITE_SLOGAN; } ?></title>

<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.js"></script>

<?php if(MYSELF == "index" || MYSELF == "view" ) include(JQ_PATH . 'jquery.vote.php'); ?>

<?php if(MYSELF == "index") { ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.tooltip.js"></script>
<?php } ?>

<?php if(MYSELF == "delete_comment" || MYSELF == "delete_post" || MYSELF == "delete_user") { ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.timedbutton.js"></script>
<?php } ?>

<?php if(MYSELF == "view" || MYSELF == "comment_list" || MYSELF == "submit") {
include(JQ_PATH . 'jquery.votecomment.php'); ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.elastic.js"></script>
<?php } ?>

</head>

<body>
<a name="top"></a>

<?php if(isset($_SESSION['user'])) {
	// query count
	$this_user_count_result = samq("users","users.voted_count AS score",NULL,"id = " . $_SESSION['user_id']);
	$this_user_count = $this_user_count_result[0]['score'];
} ?>

<table class="main_table">
	<tr class="top_row"><td align="left"><span class="head_text"><a href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a></span></td><td align="right"><a href="<?php echo SITE_URL; ?>/submit">submit</a> | <a href="<?php echo SITE_URL; ?>/new">newest</a> | <a href="<?php echo SITE_URL; ?>/all-time">all-time</a> | <a href="<?php echo SITE_URL; ?>/comments">comments</a> | <?php if(isset($_SESSION['user'])) { echo "<a href='" . SITE_URL . "/u/" . $_SESSION['user'] . "'>" . $_SESSION['user'] . "(<strong>" . $this_user_count . "</strong>)</a> | <a href='" . SITE_URL . "/login/?out=1'>logout</a>"; } else { echo "<a href='" . SITE_URL . "/login'>login</a>"; } ?></td></tr>
	<?php if(!isset($_SESSION['user']) && MYSELF != "register") { ?> <tr class="notice"><td colspan="2"><strong><a href="<?php echo SITE_URL; ?>/register">Register</a></strong> to submit, discuss, and vote for links</td></tr><?php } ?>
    <tr><td colspan="2">
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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php if(MYSELF == "view") { echo htmlentities($view[0]['description']); } else { echo SITE_META_DESCRIPTION; } ?>" />
<meta name="keywords" content="<?php echo SITE_META_KEYWORDS; ?>" />
<link type="text/css" rel="stylesheet" media="all" href="<?php echo SITE_URL ?>/style.css" />
<title><?php echo SITE_NAME; ?> | <?php if(MYSELF == "view") { echo $view[0]['title']; } else { echo SITE_SLOGAN; } ?></title>

<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.js"></script>

<?php if(in_array(MYSELF,array("index","view"))) include(JQ_PATH . 'jquery.vote.php'); ?>

<?php if(MYSELF == "index") { ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.tooltip.js"></script>
<?php } ?>

<?php if(in_array(MYSELF,array("delete_category","delete_comment","delete_post","delete_user"))) { ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.timedbutton.js"></script>
<?php } ?>

<?php if(in_array(MYSELF,array("view","comment_list","submit"))) {
include(JQ_PATH . 'jquery.votecomment.php'); ?>
<script type="text/javascript" src="<?php echo SITE_URL . "/" . JQ_PATH; ?>jquery.elastic.js"></script>
<?php } ?>
</head>

<body>
<a name="top"></a>

<?php if(isset($_SESSION['user'])) {
	// query count
	$this_user_count_result = samq("users","users.voted_count AS score",NULL,"id = " . esc($_SESSION['user_id']));
	$this_user_count = $this_user_count_result[0]['score'];
}

// compute select url
$cat_url = SITE_URL;
if(isset($_GET['mode'])) {
	if($_GET['mode'] == "submit") $cat_url .= "/submissions/" . htmlentities($_GET['user']);
	elseif($_GET['mode'] == "vote") $url .= "/voted/" . htmlentities($_GET['user']);
	else $cat_url .= "/" . htmlentities($_GET['mode']);
} ?>

<table class="main_table">
	<tr class="cat_row">
        <td colspan="3">
            <table width="100%">
            	<td width="38%"><?php echo ((!isset($_GET['cat'])) ? "<strong>" : "") . "<a href='" . $cat_url . "'>all</a>" . ((!isset($_GET['cat'])) ? "</strong>" : ""); foreach(samq("category","*",NULL,NULL,"name") as $e) echo " - " . ((isset($_GET['cat']) && $_GET['cat'] == $e['name']) ? "<strong>" : "") . "<a href='" . $cat_url . "/cat/" . $e['name'] . "'>" . $e['name'] . "</a>" . ((isset($_GET['cat']) && $_GET['cat'] == $e['name']) ? "</strong>" : ""); ?></td>
                <td width="24%" align="center"><?php echo ((!isset($_GET['mode'])) ? "<strong>" : ""); ?><a href="<?php echo SITE_URL; if(isset($_GET['cat'])) echo "/cat/" . htmlentities($_GET['cat']); ?>">default</a><?php echo ((!isset($_GET['mode'])) ? "</strong>" : ""); ?> | <?php echo ((isset($_GET['mode']) && $_GET['mode'] == "new") ? "<strong>" : ""); ?><a href="<?php echo SITE_URL; ?>/new<?php if(isset($_GET['cat'])) echo "/cat/" . htmlentities($_GET['cat']); ?>">newest</a><?php echo ((isset($_GET['mode']) && $_GET['mode'] == "new") ? "</strong>" : ""); ?> | <?php echo ((isset($_GET['mode']) && $_GET['mode'] == "all-time") ? "<strong>" : ""); ?><a href="<?php echo SITE_URL; ?>/all-time<?php if(isset($_GET['cat'])) echo "/cat/" . htmlentities($_GET['cat']); ?>">all-time</a><?php echo ((isset($_GET['mode']) && $_GET['mode'] == "all-time") ? "</strong>" : ""); ?></td>
                <td width="38%" align="right"><a href="<?php echo SITE_URL; ?>/submit">submit</a> - <a href="<?php echo SITE_URL; ?>/comments">comments</a> - <a href="<?php echo SITE_URL; ?>/search">search</a> - <?php if(isset($_SESSION['user'])) { echo "<a href='" . SITE_URL . "/u/" . $_SESSION['user'] . "'>" . $_SESSION['user'] . "(<strong>" . $this_user_count . "</strong>)</a> - <a href='" . SITE_URL . "/login/?out=1'>logout</a>"; } else { echo "<a href='" . SITE_URL . "/login'>login</a>"; } ?></td>
            </table>
        </td>
    </tr>

    <tr class="top_row">
    	<td colspan="3"><div class="head_text"><a href="<?php echo SITE_URL; ?>"><?php echo SITE_NAME; ?></a><span class="super_text"><?php echo SITE_SLOGAN; ?></span></div></td>
	</tr>

    <tr><td colspan="3">
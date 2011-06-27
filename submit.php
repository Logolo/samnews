<?php include('config.php');

if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3) && isset($_REQUEST['post'])) {
	$mode = "edit";
	$post_result = samq("post","id,title,slug,url,description",NULL,"id = " . esc($_REQUEST['post']));
} else {
	$mode = "new";	
}

// handle form submit
if(isset($_SESSION['user']) && isset($_POST['title'])) {
	include(INCLUDES_PATH . 'slugify.php');

	$error = 0;
	$error_msg = array();

	// validate fields
	if(!isset($_POST['title']) || trim($_POST['title']) == "") { $error = 1; $error_msg[] = "title cannot be blank"; }

	if(!isset($_POST['url']) || trim($_POST['url']) == "") { $error = 1; $error_msg[] = "url cannot be blank"; }
	elseif(!filter_var(trim($_POST['url']), FILTER_VALIDATE_URL)) { $error = 1; $error_msg[] = "invalid url"; }
	elseif(strlen(trim($_POST['url'])) > 255) { $error = 1; $error_msg[] = "url cannot be longer than 255 characters"; }

	if(trim($_POST['description']) == "") $description = NULL; else $description = $_POST['description'];

	if($error == 0) {
		if($mode == "edit") {
			$slug = $_POST['slug'];
		} else {
			$slug = slugify(replace_schars(trim($_POST['title'])));
		}
		
		$domain = preg_match("/^(http:\/\/)?([^\/]+)/i", $_POST['url'], $domain_only);
		$domain = str_replace("www.","",$domain_only[2]);

		if($mode == "new") {
			// passed check, execute insert
			samq_i("post",array("title","slug","url","domain","author","description","score","ip","created"),array($_POST['title'],$slug,$_POST['url'],$domain,$_SESSION['user_id'],$description,1,get_host($_SERVER['REMOTE_ADDR']),DATETIME_NOW));
	
			// add one to user's score
			samq_c("UPDATE users SET post_count = post_count + 1 WHERE id = " . esc($_SESSION['user_id']));
		} elseif($mode == "edit") {
			// passed check, execute update
			samq_u("post",array("title","slug","url","domain","description"),array($_POST['title'],$slug,$_POST['url'],$domain,$description), "id = " . $post_result[0]['id']);
		}

		// redirect to slug
		header("Location: " . SITE_URL . "/v/" . $slug);
	}
}

include('head.php'); ?>

<script type="text/javascript">
	function textCounter(field, countfield, maxlimit) {
	if(field.value.length > maxlimit)
	field.value = field.value.substring(0, maxlimit);
	else countfield.value = maxlimit - field.value.length; }
</script>

<script type="text/javascript">
$(function() {
	<!-- jQuery autoresize comment textarea -->
	$("#description").elastic();
});
</script>

<br />

<div class="content">
<span class="page_title"><?php if($mode=="new") { echo "submit"; } elseif($mode=="edit") { echo "edit"; } ?></span><br />
<?php if(isset($_SESSION['user'])) {

	// echo error message
	if(isset($error) && $error == 1) {
		echo "<br /><div class='error'>";
		foreach ($error_msg as $e) echo $e . "<br />";
		echo "</div><br />";
	} ?>

    <form method="post" action="<?php echo SITE_URL ?><?php if($mode == "new") { echo "/submit"; } elseif($mode == "edit") { echo "/edit/p/" . $post_result[0]['id']; } ?>">
        <table class="form_table" width="500">
            <tr><td><strong>title</strong><br />
            <div style="padding-bottom:4px;"><input type="text" name="title" style="width:98%;" maxlength="120" onKeyDown="textCounter(this.form.title,this.form.remLen_1,120);" onKeyUp="textCounter(this.form.title,this.form.remLen_1,120);" value="<?php if(isset($_POST['title'])) { echo stripslashes(htmlentities(replace_schars(trim($_POST['title'])))); } elseif($mode == "edit") { echo stripslashes(htmlentities(trim($post_result[0]['title']))); } ?>" /></div>
            <input disabled type="text" name="remLen_1" size="3" maxlength="3" value="120"> characters left</td></td></tr>
        </table>
		<?php if($mode == "edit") { ?>
        <br />
        <table class="form_table" width="500">
            <tr><td><strong>slug</strong><br /><input type="text" name="slug" maxlength="255" style="width:98%;" value="<?php echo trim($post_result[0]['slug']); ?>" /></td></tr>
        </table>
        <?php } ?>
        <br />
        <table class="form_table" width="500">
            <tr><td><strong>url</strong><br /><input type="text" name="url" maxlength="600" style="width:98%;" value="<?php if(isset($_POST['url'])) { echo stripslashes(htmlentities(replace_schars(trim($_POST['url'])))); } elseif($mode == "edit") { echo stripslashes(htmlentities(trim($post_result[0]['url']))); } ?>" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="500">
            <tr><td><strong>description</strong><br />
            <div style="padding-bottom:4px;"><textarea name="description" id="description" style="width:98%;" rows="4"><?php if(isset($_POST['description'])) { echo stripslashes(htmlentities(replace_schars(trim($_POST['description'])))); } elseif($mode == "edit") { echo stripslashes(htmlentities(trim($post_result[0]['description']))); } ?></textarea></div>
            </tr>
        </table>
        <br />
        <input type="submit" name="submit" value="submit" />
    </form>
    <br />
    please make sure this link has not already been posted</a>
<?php } else { ?>
	<br />you must <a href="<?php echo SITE_URL; ?>/login">login</a> to submit an article | don't have an account? <a href="<?php echo SITE_URL; ?>/register">register here</a>
<?php } ?>
</div>
<br /><br />

<?php include('foot.php'); ?>
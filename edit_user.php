<?php include('config.php');

// query user information
$user_result = samq("users","id,login,email,about,perm_mod,perm_admin,post_count,comment_count,vote_count,cookie_key,forgot_key",NULL,"login = '" . esc($_REQUEST['user']) . "'");

// prevent unauthorized or mods editing mods/admins
if( isset($_SESSION['access']) && (($_SESSION['access'] == 2 && $user_result[0]['perm_mod'] != 1 && $user_result[0]['perm_admin'] != 1) || $_SESSION['access'] == 3)) {

	// handle form submit
	if(isset($_POST['email'])) {
		$error = 0;
		$error_msg = array();

		// validate fields
		if(!isset($_POST['email']) || trim($_POST['email']) == "") { $error = 1; $error_msg[] = "email cannot be blank"; }
		elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $error = 1; $error_msg[] = "invalid email"; }

		if($error == 0) {
			$user_update =  "UPDATE users SET";
			if(trim($_POST['password'] != "")) {
				$user_update .= " password = '" . sha1($_POST['password']) . "',";
			}
			$user_update .= " email = '" . esc($_POST['email']) . "',";
			$user_update .= " about = " . ((trim($_POST['about']) != "") ? "'" . esc($_POST['about']) . "'" : "NULL") . ",";
			if(trim($_POST['permission'] == "perm_mod")) {
				$user_update .= " perm_mod = 1,";
				$user_update .= " perm_admin = 0,";
			} elseif(trim($_POST['permission'] == "perm_admin")) {
				$user_update .= " perm_mod = 0,";
				$user_update .= " perm_admin = 1,";
			} else {
				$user_update .= " perm_mod = 0,";
				$user_update .= " perm_admin = 0,";
			}
			$user_update .= " post_count = " . ((trim($_POST['post_count']) != "") ? esc($_POST['post_count']) : "0") . ",";
			$user_update .= " comment_count = " . ((trim($_POST['comment_count']) != "") ? esc($_POST['comment_count']) : "0") . ",";
			$user_update .= " vote_count = " . ((trim($_POST['vote_count']) != "") ? esc($_POST['vote_count']) : "0") . ",";
			$user_update .= " cookie_key = " . ((trim($_POST['cookie_key']) != "") ? "'" . esc($_POST['cookie_key']) . "'" : "NULL");
			
			$user_update .= " WHERE id = " . $user_result[0]['id'];

			// passed check, execute update
			samq_c($user_update);

			$success = "user has been edited";
		}
	}
	
	include('head.php');
	?>
	
	<br />
	
	<div class="content">
	<span class="page_title">edit user</span><br />
	
	<?php
	// echo error message
	if(isset($error) && $error == 1) {
		echo "<br /><div class='error'>";
		foreach ($error_msg as $e) {
			echo $e . "<br />";
		}
		echo "</div><br />";
	}

	// echo success message
	if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/u/" . $_REQUEST['user'] . "'>done</a>"; } else {
	?>
		<form method="post" action="<?php echo SITE_URL; ?>/edit/u/<?php echo $_REQUEST['user']; ?>">
		<?php foreach($user_result as $e) { ?>
			<table class="admin_table" width="500">
                <tr><td><strong>id</strong><br /><input type="text" name="id" style="width:98%;" value="<?php if(isset($e['id'])) echo trim($e['id']); ?>" disabled /></td></tr>
				<tr><td><strong>login</strong><br /><input type="text" name="login" maxlength="12" style="width:98%;" value="<?php if(isset($e['login'])) echo trim($e['login']); ?>" disabled /></td></tr>
                <tr><td><strong>password</strong><br /><input type="text" name="password" maxlength="45" style="width:98%;" /></td></tr>
                <tr><td><strong>email</strong><br /><input type="text" name="email" maxlength="150" style="width:98%;" value="<?php if(isset($e['email'])) echo trim($e['email']); ?>" /></td></tr>
                <tr><td><strong>about</strong><br /><input type="text" name="about" maxlength="255" style="width:98%;" value="<?php if(isset($e['about'])) echo trim($e['about']); ?>" /></td></tr>
                <tr><td><strong>permission</strong><br /><select name="permission"<?php if($_SESSION['access'] != 3) echo " disabled"; ?>><option>user</option><option<?php if(isset($e['perm_mod'])) echo " selected='selected'"; ?> value="perm_mod">moderator</option><option<?php if(isset($e['perm_admin'])) echo " selected='selected'"; ?> value="perm_admin">admin</option></select></td></tr>
                <tr><td><strong>post count</strong><br /><input type="text" name="post_count" style="width:98%;" value="<?php if(isset($e['post_count'])) echo trim($e['post_count']); ?>" /></td></tr>
                <tr><td><strong>comment count</strong><br /><input type="text" name="comment_count" style="width:98%;" value="<?php if(isset($e['comment_count'])) echo trim($e['comment_count']); ?>" /></td></tr>
                <tr><td><strong>vote count</strong><br /><input type="text" name="vote_count" style="width:98%;" value="<?php if(isset($e['vote_count'])) echo trim($e['vote_count']); ?>" /></td></tr>
				<tr><td><strong>cookie key</strong><br /><input type="text" name="cookie_key" style="width:98%;" value="<?php if(isset($e['cookie_key'])) echo trim($e['cookie_key']); ?>" /></td></tr>
				<tr><td><strong>forgot key</strong><br /><input type="text" name="forgot_key" style="width:98%;" value="<?php if(isset($e['forgot_key'])) echo trim($e['forgot_key']); ?>" disabled /></td></tr>
			</table>
		<?php } ?>
			<br />
			<input type="submit" name="submit" value="submit" />
		</form>
	<?php } ?>
	</div>
	
	<br /><br />

<?php
include('foot.php');

} else {
	header("Location: " . SITE_URL);
}
?>
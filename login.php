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

// securimage
include_once CLASSES_PATH . 'securimage/securimage.php';
$securimage = new Securimage();

include(INCLUDES_PATH . 'auth.php');

// check to see if user is logging out
if(isset($_GET['out'])) {
	logout();
	$success = "logout successful";
}

// check to see if login form has been submitted
if(isset($_POST['user'])) {
	$error = 0;
	$error_msg = array();
	
	// validate fields
	if(!isset($_POST['user']) || trim($_POST['user']) == "") { $error = 1; $error_msg[] = "user cannot be blank"; }
	if(!isset($_POST['password']) || trim($_POST['password']) == "") { $error = 1; $error_msg[] = "password cannot be blank"; }
	
	if($error != 1) {
		// check to see if captcha is correct
		if($securimage->check($_POST['visualcode'])) {
			// captcha is correct
			// run information through authenticator
			if(auth($_POST['user'],$_POST['password']))
			{
				if(isset($_POST['rememberme'])) {

					// query cookie key
					$cookie_result = samq("users","cookie_key",NULL,"id = " . esc($_SESSION['user_id']));
					$cookie_key = $cookie_result[0]['cookie_key'];

					// set cookies
					setcookie(COOKIE_PREFIX . "_user", $_SESSION['user'], time()+60*60*24*COOKIE_EXPIRE, "/");
					setcookie(COOKIE_PREFIX . "_chip", $cookie_key, time()+60*60*24*COOKIE_EXPIRE, "/");
				}
				
				// log IP address
				samq_u("users",array("ip"),array(gethostbyaddr($_SERVER['REMOTE_ADDR'])),"id = " . esc($_SESSION['user_id']));
				
				// authentication passed
				header("Location: " . SITE_URL);
				die();
			} else {
				// authentication failed
				$error = 1; $error_msg[] = "incorrect user name or password";
			}
		} else {
			$error = 1;	$error_msg[] = "invalid captcha";
		}
	}
}

include('head.php'); ?>

<br />

<div class="content">
<span class="page_title">login</span><br />

<?php
// echo error message
if(isset($error) && $error == 1) {
	echo "<br /><div class='error'>";
	foreach ($error_msg as $e) echo $e . "<br />";
	echo "</div><br />";
}

// echo success message
if(isset($success)) echo "<br /><div class='success'>" . $success . "</div><br />";
?>

<form method="post" action="<?php echo SITE_URL; ?>/login">
    <table class="form_table" width="300">
		<tr><td><strong>user</strong><br /><input type="text" name="user" class="required" maxlength="45" style="width:98%;" /></td></tr>
	</table>
	<br />
	<table class="form_table" width="300">
		<tr><td><strong>pass</strong><br /><input type="password" name="password" class="required" maxlength="45" style="width:98%;" /></td></tr>
	</table>
    <br />
    <table class="form_table" width="300">
		<tr><td><strong>captcha</strong><br /><img id="captcha" src="<?php echo SITE_URL . "/" . CLASSES_PATH; ?>securimage/securimage_show.php" alt="CAPTCHA Image" style="padding-bottom:4px;" /><br /><input type="text" name="visualcode" class="required" style="width:98%;" /></td>
        </tr>
	</table>
    <br />
    <table class="form_table" width="300">
		<tr><td><input type="checkbox" name="rememberme" /> remember me</td>
        </tr>
	</table>
	<br />
	<input type="submit" name="submit" value="submit" />
</form>
<br />
don't have an account? <a href="<?php echo SITE_URL; ?>/register">register here</a> | <a href="<?php echo SITE_URL; ?>/forgot">forgot your password</a>
</div>

<br /><br />

<?php include('foot.php'); ?>
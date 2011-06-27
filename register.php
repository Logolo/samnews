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

include_once CLASSES_PATH . 'securimage/securimage.php';
$securimage = new Securimage();

include(INCLUDES_PATH . 'auth.php');

// handle form submit
if(isset($_POST['user'])) {
	$error = 0;
	$error_msg = array();
	
	// validate fields
	if(!isset($_POST['user']) || trim($_POST['user']) == "") { $error = 1; $error_msg[] = "user cannot be blank"; }
	elseif(preg_match("/\W/",trim($_POST['user']))) { $error = 1; $error_msg[] = "user can only contain letters and numbers (no spaces)"; }
	elseif(strlen(trim($_POST['user'])) > 12) { $error = 1; $error_msg[] = "user cannot be longer than 12 characters"; }
	
	if(!isset($_POST['password']) || trim($_POST['password']) == "") { $error = 1; $error_msg[] = "password cannot be blank"; }
	
	if(!isset($_POST['password_verify']) || trim($_POST['password_verify']) == "") { $error = 1; $error_msg[] = "password verify cannot be blank"; }
	elseif(trim($_POST['password']) != trim($_POST['password_verify'])) { $error = 1; $error_msg[] = "passwords do not match"; }
	
	if(!isset($_POST['email']) || trim($_POST['email']) == "") { $error = 1; $error_msg[] = "email cannot be blank"; }
	elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $error = 1; $error_msg[] = "invalid email"; }
	
	if(!isset($_POST['email_verify']) || trim($_POST['email_verify']) == "") { $error = 1; $error_msg[] = "email verify cannot be blank"; }
	elseif(trim($_POST['email']) != trim($_POST['email_verify'])) { $error = 1; $error_msg[] = "emails do not match"; }
	
	if(!isset($_POST['visualcode']) || trim($_POST['visualcode']) == "") { $error = 1; $error_msg[] = "captcha cannot be blank";	}

	if($error == 0) {
		// check captcha
		if($securimage->check($_POST['visualcode'])) {
	
			// check for duplicate name
			$dupe_name_check = samq("users","login",NULL,"login='".esc($_POST['user'])."'");
			if(count($dupe_name_check) != 0) { $error = 1; $error_msg[] = "user name already exists"; }
			
			// check for duplicate email
			$dupe_email_check = samq("users","login",NULL,"email='".esc($_POST['email'])."'");
			if(count($dupe_email_check) != 0) { $error = 1; $error_msg[] = "email already in use"; }
			
			if($error == 0) {
				// passed check, execute insert
				samq_i("users",array("login","password","email","cookie_key","created"),array($_POST['user'],sha1($_POST['password']),$_POST['email'],sha1($_POST['user'] . $_POST['password'] . gethostbyaddr($_SERVER['REMOTE_ADDR'])),DATETIME_NOW));
	
				// send registration e-mail
				include(INCLUDES_PATH . 'email_setup.php');
				$mail->AddAddress(trim($_POST['email']));
				$mail->Subject = SITE_NAME . " account";

				$message  = "<html><head><title>" . SITE_NAME . " account</title></head><body>";
				$message .= "<p style='font-family:Verdana;font-size:24px;font-weight:bold;'>" . SITE_NAME . "</p>";
				$message .= "<p style='font-family:Verdana;font-size:12px;'>You have successfully registered for an account at <a href='" . SITE_URL . "' target='_blank'>" . SITE_URL . "</a></p>";
				$message .= "<p style='font-family:Verdana;font-size:12px;'>";
				$message .= "<strong>user:</strong> " . esc($_POST['user']) . "<br /><strong>password:</strong> " . esc($_POST['password']) . "</p></body></html>";
				$mail->MsgHTML($message);

				$altbody = str_replace("</title>","\n\n",$message);
				$altbody = str_replace("</p>","\n\n",$altbody);
				$altbody = str_replace("<br />","\n",$altbody);
				$mail->AltBody = strip_tags($altbody);

				if(!$mail->Send()) {
					echo "Error sending registration email: " . $mail->ErrorInfo;
				}
				
				if(auth($_POST['user'],$_POST['password']))
				{
					// log IP address
					samq_u("users",array("ip"),array(gethostbyaddr($_SERVER['REMOTE_ADDR'])),"id = " . esc($_SESSION['user_id']));

					// set success message
					$success = "account created";
				} else {
					// authentication failed
					$error = 1; $error_msg[] = "authentication failed";
				}
			}
		} else {
			$error = 1; $error_msg[] = "invalid captcha";
		}
	}
}

include('head.php');
?>

<br />

<div class="content">
<span class="page_title">register</span><br />

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
if(isset($success))  { echo "<br /><div class='success'>" . $success . "</div><br /><br />return to the <a href='" . SITE_URL . "'>index</a>"; } else {
?>
    <form method="post" action="<?php echo SITE_URL; ?>/register">
        <table class="form_table" width="300">
            <tr><td><strong>user</strong><br /><input type="text" name="user" style="width:98%;" maxlength="12" value="<?php if(isset($_POST['user'])) echo trim($_POST['user']); ?>" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>password</strong><br /><input type="password" name="password" maxlength="45" style="width:98%;" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>verify password</strong><br /><input type="password" name="password_verify" maxlength="45" style="width:98%;" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>email</strong><br /><input type="text" name="email" style="width:98%;" maxlength="150" value="<?php if(isset($_POST['email'])) echo trim($_POST['email']); ?>" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>verify email</strong><br /><input type="text" name="email_verify" maxlength="150" style="width:98%;" value="<?php if(isset($_POST['email_verify'])) echo trim($_POST['email_verify']); ?>" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>captcha</strong><br /><img id="captcha" src="<?php echo SITE_URL . "/" . CLASSES_PATH; ?>securimage/securimage_show.php" alt="CAPTCHA Image" style="padding-bottom:4px;" /><br /><input type="text" name="visualcode" style="width:98%;" /></td>
            </tr>
        </table>
        <br />
        <input type="submit" name="submit" value="submit" />
    </form>
<?php } ?>
</div>

<br /><br />

<?php include('foot.php'); ?>
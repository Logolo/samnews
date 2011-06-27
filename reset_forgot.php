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

if(!isset($_GET['user'],$_GET['key'])) die("Invalid key");

// check key
$key_check = samq("users","login,email,forgot_key",NULL,"login = '" . esc($_GET['user']) . "' AND forgot_key = '" . esc($_GET['key']) . "'");
if(count($key_check) == 0) {
	die("Invalid key");
}

include_once CLASSES_PATH . 'securimage/securimage.php';
$securimage = new Securimage();

// handle form submit
if(isset($_POST['new_password'])) {
	$error = 0;
	$error_msg = array();
	
	// validate fields
	if(!isset($_POST['new_password']) || trim($_POST['new_password']) == "") { $error = 1; $error_msg[] = "password cannot be blank"; }
	
	if(!isset($_POST['new_password_verify']) || trim($_POST['new_password_verify']) == "") { $error = 1; $error_msg[] = "password verify cannot be blank"; }
	elseif(trim($_POST['new_password']) != trim($_POST['new_password_verify'])) { $error = 1; $error_msg[] = "passwords do not match"; }
	
	if(!isset($_POST['visualcode']) || trim($_POST['visualcode']) == "") { $error = 1; $error_msg[] = "captcha cannot be blank";	}

	if($error == 0) {
		// check captcha
		if($securimage->check($_POST['visualcode'])) {

			// passed check, execute update
			samq_u("users",array("password","forgot_key"),array(sha1($_POST['new_password']),NULL),"login = '" . esc($_GET['user']) . "' AND forgot_key = '" . esc($_GET['key']) . "'");

			// send reset e-mail
			include(INCLUDES_PATH . 'email_setup.php');
            $mail->AddAddress(trim($key_check[0]['email']));
            $mail->Subject = SITE_NAME . " password has been reset";

			$message  = "<html><head><title>" . SITE_NAME . " password reset</title></head><body>";
			$message .= "<p style='font-family:Verdana;font-size:24px;font-weight:bold;'>" . SITE_NAME . "</p>";
			$message .= "<p style='font-family:Verdana;font-size:12px;'>You have successfully reset your password at <a href='" . SITE_URL . "' target='_blank'>" . SITE_URL . "</a></p>";
			$message .= "<p style='font-family:Verdana;font-size:12px;'>";
			$message .= "<strong>user:</strong> " . trim($key_check[0]['login']) . "<br /><strong>new password:</strong> " . esc($_POST['new_password']) . "</p></body></html>";
			$mail->MsgHTML($message);

			$altbody = str_replace("</title>","\n\n",$message);
			$altbody = str_replace("</p>","\n\n",$altbody);
			$altbody = str_replace("<br />","\n",$altbody);
			$mail->AltBody = strip_tags($altbody);

			if(!$mail->Send()) {
				echo "Error sending password reset email: " . $mail->ErrorInfo;
			}

			$success = "password has been reset";
		} else {
			$error = 1; $error_msg[] = "invalid captcha";
		}
	}
}

include('head.php');
?>

<br />

<div class="content">
<span class="page_title">reset password</span><br />

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
if(isset($success))  { echo "<br /><div class='success'>" . $success . "</div><br /><br />you may now <a href='" . SITE_URL . "/login/'>login</a>"; } else {
?>
    <form method="post" action="<?php echo SITE_URL; ?>/reset/forgot/<?php echo $_GET['user']; ?>/<?php echo $_GET['key']; ?>">
        <table class="form_table" width="300">
            <tr><td><strong>new password</strong><br /><input type="password" name="new_password" maxlength="45" style="width:98%;" /></td></tr>
        </table>
        <br />
        <table class="form_table" width="300">
            <tr><td><strong>verify password</strong><br /><input type="password" name="new_password_verify" maxlength="45" style="width:98%;" /></td></tr>
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
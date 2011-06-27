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

// handle form submit
if(isset($_POST['user_send'])) {
	$error = 0;
	$error_msg = array();
	
	// validate fields
	if(!isset($_POST['user_send']) || trim($_POST['user_send']) == "") { $error = 1; $error_msg[] = "user cannot be blank"; }
	elseif(trim($_POST['user_send']) == "[deleted]") { $error = 1; $error_msg[] = "don't even try"; }
	elseif(preg_match("/\W/",trim($_POST['user_send']))) { $error = 1; $error_msg[] = "invalid user"; }
	elseif(strlen(trim($_POST['user_send'])) > 12) { $error = 1; $error_msg[] = "invalid user"; }
	
	if(!isset($_POST['visualcode']) || trim($_POST['visualcode']) == "") { $error = 1; $error_msg[] = "captcha cannot be blank";	}

	if($error == 0) {
		// check captcha
		if($securimage->check($_POST['visualcode'])) {
	
			// check name exist
			$name_check = samq("users","login,email",NULL,"login='".esc($_POST['user_send'])."'");
			if(count($name_check) == 0) { $error = 1; $error_msg[] = "user name doesn't exist"; }
			
			if($error == 0) {
				
				// generate forgot key
				$forgot_key = str_replace('.','',uniqid(rand(),true));

				// passed check, execute update
				samq_u("users",array("forgot_key"),array($forgot_key),"login = '" . $name_check[0]['login'] . "'");

				// send forgot password e-mail
				include(INCLUDES_PATH . 'email_setup.php');
				$mail->AddAddress(trim($name_check[0]['email']));
                $mail->Subject = SITE_NAME . " forgot password";

				$message  = "<html><head><title>" . SITE_NAME . " forgot password</title></head><body>";
				$message .= "<p style='font-family:Verdana;font-size:24px;font-weight:bold;'>" . SITE_NAME . "</p>";
				$message .= "<p style='font-family:Verdana;font-size:12px;'>" . str_replace('.','_',gethostbyaddr($_SERVER['REMOTE_ADDR'])) . " has initiated a password reset for your account.</p>";
				$message .= "<br /><p style='font-family:Verdana;font-size:12px;'>Click the link below to continue:<br /><a href='" . SITE_URL . "/reset/forgot/" . trim($name_check[0]['login']) . "/" . $forgot_key . "' target='_blank'>" . SITE_URL . "/reset/forgot/" . trim($name_check[0]['login']) . "/" . $forgot_key . "</a></p>";
				$message .= "<br /><p style='font-family:Verdana;font-size:12px;'>If you did not make this request, please contact <a href='mailto:" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a> for assistance</p>";
				$message .= "</body></html>";
				$mail->MsgHTML($message);

				$altbody = str_replace("</title>","\n\n",$message);
				$altbody = str_replace("</p>","\n\n",$altbody);
				$altbody = str_replace("<br />","\n",$altbody);
				$mail->AltBody = strip_tags($altbody);

				if(!$mail->Send()) {
					echo "Error sending forgot password email: " . $mail->ErrorInfo;
				}
	
				$success = "an email has been sent to you with further instructions";
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
<span class="page_title">forgot password</span><br />

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
if(isset($success))  { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "'>done</a>"; } else {
?>
    <form method="post" action="<?php echo SITE_URL; ?>/forgot">
        <table class="form_table" width="300">
            <tr><td><strong>enter your user name</strong><br /><input type="text" name="user_send" style="width:98%;" maxlength="12" value="<?php if(isset($_POST['user_send'])) echo trim($_POST['user_send']); ?>" /></td></tr>
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
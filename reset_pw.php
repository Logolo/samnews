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

if(isset($_SESSION['user_id'])) {
	
	// handle form submit
	if(isset($_POST['new_password'])) {
		$error = 0;
		$error_msg = array();
		
		// validate fields
		if(!isset($_POST['old_password']) || trim($_POST['old_password']) == "") { $error = 1; $error_msg[] = "old password cannot be blank"; }
		if(!isset($_POST['old_password_verify']) || trim($_POST['old_password_verify']) == "") { $error = 1; $error_msg[] = "old password verify cannot be blank"; }
		elseif(trim($_POST['old_password']) != trim($_POST['old_password_verify'])) { $error = 1; $error_msg[] = "old passwords do not match"; }

		if(!isset($_POST['new_password']) || trim($_POST['new_password']) == "") { $error = 1; $error_msg[] = "new password cannot be blank"; }
		if(!isset($_POST['new_password_verify']) || trim($_POST['new_password_verify']) == "") { $error = 1; $error_msg[] = "new password verify cannot be blank"; }
		elseif(trim($_POST['new_password']) != trim($_POST['new_password_verify'])) { $error = 1; $error_msg[] = "new passwords do not match"; }

		if($error == 0) {
			// passed check, execute update
			samq_u("users",array("password"),array(sha1($_POST['new_password'])),"id = " . esc($_SESSION['user_id']));

			// get user's email address
			$email_result = samq("users","email",NULL,"id = " . esc($_SESSION['user_id']));
	
			// send reset email
			include(INCLUDES_PATH . 'email_setup.php');
			$mail->AddAddress(trim($email_result[0]['email']));
			$mail->Subject = SITE_NAME . " password has been reset";

			$message  = "<html><head><title>" . SITE_NAME . " password reset</title></head><body>";
			$message .= "<p style='font-family:Verdana;font-size:24px;font-weight:bold;'>" . SITE_NAME . "</p>";
			$message .= "<p style='font-family:Verdana;font-size:12px;'>You have successfully reset your password at <a href='" . SITE_URL . "' target='_blank'>" . SITE_URL . "</a></p>";
			$message .= "<p style='font-family:Verdana;font-size:12px;'>";
			$message .= "<strong>user:</strong> " . esc($_SESSION['user']) . "<br /><strong>new password:</strong> " . esc($_POST['new_password']) . "</p></body></html>";
			$mail->MsgHTML($message);

			$altbody = str_replace("</title>","\n\n",$message);
			$altbody = str_replace("</p>","\n\n",$altbody);
			$altbody = str_replace("<br />","\n",$altbody);
			$mail->AltBody = strip_tags($altbody);

			if(!$mail->Send()) {
				echo "Error sending password reset email: " . $mail->ErrorInfo;
			}
	
			$success = "password has been reset";
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
	if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/u/" . $_SESSION['user'] . "'>done</a>"; } else {
	?>
		<form method="post" action="<?php echo SITE_URL; ?>/reset/pw">
			<table class="form_table" width="300">
				<tr><td><strong>old password</strong><br /><input type="password" name="old_password" maxlength="45" style="width:98%;" /></td></tr>
			</table>
			<br />
			<table class="form_table" width="300">
				<tr><td><strong>verify old password</strong><br /><input type="password" name="old_password_verify" maxlength="45" style="width:98%;" /></td></tr>
			</table>
			<br />
			<table class="form_table" width="300">
				<tr><td><strong>new password</strong><br /><input type="password" name="new_password" maxlength="45" style="width:98%;" /></td></tr>
			</table>
			<br />
			<table class="form_table" width="300">
				<tr><td><strong>verify new password</strong><br /><input type="password" name="new_password_verify" maxlength="45" style="width:98%;" /></td></tr>
			</table>
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
	die();
}
?>
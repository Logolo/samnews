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
	if(isset($_POST['email'])) {
		$error = 0;
		$error_msg = array();
		
		// validate fields
		if(!isset($_POST['email']) || trim($_POST['email']) == "") { $error = 1; $error_msg[] = "email cannot be blank"; }
		elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $error = 1; $error_msg[] = "invalid email"; }
		
		if(!isset($_POST['email_verify']) || trim($_POST['email_verify']) == "") { $error = 1; $error_msg[] = "email verify cannot be blank"; }
		elseif(trim($_POST['email']) != trim($_POST['email_verify'])) { $error = 1; $error_msg[] = "emails do not match"; }

		if($error == 0) {
			// passed check, execute update
			samq_u("users",array("email"),array($_POST['email']),"id = " . esc($_SESSION['user_id']));

			$success = "email has been reset";
		}
	}
	
	include('head.php');
	?>
	
	<br />
	
	<div class="content">
	<span class="page_title">change email</span><br />
	
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
		<form method="post" action="<?php echo SITE_URL; ?>/reset/email">
			<table class="form_table" width="300">
				<tr><td><strong>new email</strong><br /><input type="text" name="email" maxlength="150" style="width:98%;" value="<?php if(isset($_POST['email'])) echo trim($_POST['email']); ?>" /></td></tr>
			</table>
			<br />
			<table class="form_table" width="300">
				<tr><td><strong>verify email</strong><br /><input type="text" name="email_verify" maxlength="150" style="width:98%;" value="<?php if(isset($_POST['email_verify'])) echo trim($_POST['email_verify']); ?>" /></td></tr>
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
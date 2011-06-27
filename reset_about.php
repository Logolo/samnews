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

	// grab existing about
	$about_result = samq("users","about",NULL,"id = " . esc($_SESSION['user_id']));
	if (isset($about_result[0]['about'])) $about = $about_result[0]['about'];

	// handle form submit
	if(isset($_POST['about'])) {
			// grab post
			if(trim($_POST['about']) == "") $about_update = NULL; else $about_update = $_POST['about'];
		
			// passed check, execute update
			samq_u("users",array("about"),array($about_update),"id = " . esc($_SESSION['user_id']));

			$success = "about has been reset";
	}
	
	include('head.php');
	?>

	<script type="text/javascript">
    function textCounter(field, countfield, maxlimit) {
    if(field.value.length > maxlimit)
    field.value = field.value.substring(0, maxlimit);
    else countfield.value = maxlimit - field.value.length; }
    </script>

	<br />
	
	<div class="content">
	<span class="page_title">change about</span><br />
	
	<?php	
	// echo success message
	if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/u/" . $_SESSION['user'] . "'>done</a>"; } else {
	?>
		<form method="post" action="<?php echo SITE_URL; ?>/reset/about">
            <table class="form_table" width="500">
                <tr><td><strong>about</strong><br />
                <div style="padding-bottom:4px;"><textarea name="about" style="width:98%;" rows="4" wrap="physical" onKeyDown="textCounter(this.form.about,this.form.remLen,255);" onKeyUp="textCounter(this.form.about,this.form.remLen,255);"><?php if(isset($about) && $about != "") echo trim(htmlentities($about)); ?></textarea></div>
                <input disabled type="text" name="remLen" size="3" maxlength="3" value="255"> characters left</td>
                </tr>
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
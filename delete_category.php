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

// query user information
$cat_result = samq("category","*",NULL,"id = " . esc($_GET['cat']));

if (count($cat_result) > 0) {
	// prevent unauthorized or mods editing mods/admins
	if( isset($_SESSION['access']) && $_SESSION['access'] == 3 && $cat_result[0]['id'] != 1 ) {
	
		// handle form submit
		if(isset($_POST['delete'])) {
			// reassign posts
			samq_u("post",array("category"),array($_POST['category']),"category = " . $cat_result[0]['id']);

			// delete category
			samq_d("category","id = " . $cat_result[0]['id']);

			$success = "category has been deleted";
		}
		
		include('head.php');
		?>
		
		<br />
		
		<div class="content">
		<span class="page_title">delete category</span><br />

		<?php // echo success message
		if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/ctlist'>done</a>"; } else { ?>
			<!--  jQuery timed button -->
			<script type="text/javascript">
			jQuery(function() {
				$('#delete').timedDisable();
			});
			</script>
			<br />
			<div class="error">are you sure you want to delete "<?php echo $cat_result[0]['name']; ?>"?</div>
            <br />
			<form method="post" action="<?php echo SITE_URL; ?>/delete/ct/<?php echo $cat_result[0]['id']; ?>">
            <table class="admin_table">
                <tr><td>
                    where would you like to move the associated posts?<br />
                    <select name="category">
                    <?php foreach(samq("category","*",NULL,"id != " . esc($_GET['cat']),"name") as $e) {
                        echo "<option value='" . $e['id'] . "'>" . $e['name'] . "</option>";
                    } ?>
                    </select>
                </td></tr>
            </table>
			<br />
					<input type="button" value="cancel" onClick="location.href='<?php echo SITE_URL . "/ctlist"; ?>'" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="delete" id="delete" value="DELETE" />
			</form>
			<br /><br />
		<?php } ?>
		</div>
		
		<br /><br />
	
	<?php
	include('foot.php');
	
	} else {
		header("Location: " . SITE_URL);
		die();
	}
} else { ?>
	Invalid category
<?php } ?>
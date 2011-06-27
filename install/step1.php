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

if(isset($_POST['submit'])) {
	$error = array();
	
	// attempt to connect to database
	@mysql_connect($_POST['DB_HOST'], $_POST['DB_USER'], $_POST['DB_PASSWORD']) or $error[] = "Database connection failed, check your host, user, and password";
	@mysql_select_db($_POST['DB_NAME']) or $error[] = "Database selection failed, check your database name";

    if(count($error) == 0) {

// build settings
$settings = "<?php
define('DB_HOST','" . $_POST['DB_HOST'] . "');
define('DB_USER','" . $_POST['DB_USER'] . "');
define('DB_PASSWORD','" . $_POST['DB_PASSWORD'] . "');
define('DB_NAME','" . $_POST['DB_NAME'] . "');
?>";
        
        // write settings file
        if(file_put_contents("../settings_db.php",$settings)) {
            // move on to step 2
            header("Location: version_check.php");
            die();
        } else $error[] = "Permission problem when writing settings_db.php, address this problem and run install again";

    }

}

include('head.php');
?>

<div class="step_head">Step 1 of <?php echo STEPS; ?> - Database Settings</div>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

	<?php if(isset($error) && count($error) > 0) {
		echo "<div class='error'>";
			foreach($error as $e) echo $e . "<br />";
		echo "</div>";
	} ?>

    <table>
        <tr>
            <td>Host Name</td><td><input name="DB_HOST" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['DB_HOST']; else echo "localhost"; ?>" /></td>
        </tr>
        <tr>
            <td>User Name</td><td><input name="DB_USER" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['DB_USER']; ?>" /></td>
        </tr>
        <tr>
            <td>Password</td><td><input name="DB_PASSWORD" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['DB_PASSWORD']; ?>" /></td>
        </tr>
        <tr>
            <td>Database Name</td><td><input name="DB_NAME" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['DB_NAME']; ?>" /></td>
        </tr>
    </table>
    
    <br /><br />
    
    <input type="submit" name="submit" value="Create DB Settings File" />

</form>

<?php include('foot.php'); ?>
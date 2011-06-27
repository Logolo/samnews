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
include('head.php');

// default SITE_URL
$url = explode("/",$_SERVER['REQUEST_URI']);
array_pop($url);
array_pop($url);
$site_url = ((isset($_SERVER['HTTPS'])) ? "https://" : "http://") . $_SERVER['SERVER_NAME'] . implode("/",$url);

if(isset($_POST['submit'])) {

    $error = array();

    if(trim($_POST['SITE_URL']) != "") { // if user hasn't left SITE_URL blank
        $site_url = rtrim($_POST['SITE_URL'],'/'); // remove trailing slash
    }
    
// build settings
$settings = "<?php
define('SITE_URL', '" . $site_url . "'); // DO NOT INCLUDE A TRAILING SLASH
define('SITE_NAME','" . $_POST['SITE_NAME'] . "');
define('SITE_SLOGAN','" . $_POST['SITE_SLOGAN'] . "');
define('SITE_META_KEYWORDS','" . $_POST['SITE_META_KEYWORDS'] . "');
define('SITE_META_DESCRIPTION','" . $_POST['SITE_META_DESCRIPTION'] . "');
define('SUPPORT_EMAIL','" . $_POST['SUPPORT_EMAIL'] . "');
define('OUTGOING_EMAIL','" . $_POST['OUTGOING_EMAIL'] . "');
define('DATETIME_NOW',date(\"Y-m-d H:i:s\"));
define('TIME_ZONE','EST');
define('INDEX_INTERVAL','8 day'); // time that articles remain on the index page, in SQL syntax
define('INDEX_DISPLAY',35);
define('RSS_DISPLAY',35);
define('SESSION_EXPIRE', '21600'); // in seconds
define('COOKIE_PREFIX','samnews');
define('COOKIE_EXPIRE', '21'); // in days
?>";

	// write settings file
	if(file_put_contents("../settings_user.php",$settings)) {
        echo "<div class='success step_head'>Installation Complete</div>";
		echo "Delete the '/install' folder and visit <a href='" . $_POST['SITE_URL'] . "'>" . $_POST['SITE_URL'] . "</a>";
        
        die();
	}
	else $error[] = "Permission problem when writing settings_user.php, address this problem and run install again";
}
?>

<div class="step_head">Step 3 of <?php echo STEPS; ?> - User Settings</div>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

	<?php if(isset($error) && count($error) > 0) {
		echo "<div class='error'>";
			foreach($error as $e) echo $e . "<br />";
		echo "</div>";
	} ?>

    <table>
        <tr>
            <td>Site URL</td><td><input name="SITE_URL" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SITE_URL']; else echo $site_url; ?>" /></td>
        </tr>
        <tr>
            <td>Site Name</td><td><input name="SITE_NAME" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SITE_NAME']; else echo "SamNews"; ?>" /></td>
        </tr>
        <tr>
            <td>Site Slogan</td><td><input name="SITE_SLOGAN" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SITE_SLOGAN']; else echo "social news application" ?>" /></td>
        </tr>
        <tr>
            <td>Site Meta Keywords</td><td><input name="SITE_META_KEYWORDS" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SITE_META_KEYWORDS']; ?>" /></td>
        </tr>
        <tr>
            <td>Site Meta Description</td><td><input name="SITE_META_DESCRIPTION" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SITE_META_DESCRIPTION']; ?>" /></td>
        </tr>
        <tr>
            <td>Support E-Mail</td><td><input name="SUPPORT_EMAIL" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['SUPPORT_EMAIL']; else echo "support@" . $_SERVER['SERVER_NAME']; ?>" /></td>
        </tr>
        <tr>
            <td>Outgoing E-Mail</td><td><input name="OUTGOING_EMAIL" type="text" value="<?php if(isset($_POST['submit'])) echo $_POST['OUTGOING_EMAIL']; else echo "noreply@" . $_SERVER['SERVER_NAME']; ?>" /></td>
        </tr>
    </table>

    <br /><br />

    <input type="submit" name="submit" value="Create User Settings File" />

</form>

<?php include('foot.php'); ?>
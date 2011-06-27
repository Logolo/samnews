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
include('../settings_db.php');

// attempt to connect to database
@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("<div class='error'>Database connection failed, check your settings_db.php file</div>");
@mysql_select_db(DB_NAME) or die("<div class='error'>Database selection failed, check your settings_db.php file</div>");

// check to see if post table exists
$posttable = mysql_query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'post';") or die("<div class='error'>information_schema SELECT error</div>");
$posttable = mysql_fetch_array($posttable);

if($posttable[0] == 0) {
	// no tables at all, redirect to step 2
	header("Location: step2.php");
	die();
}

$vertable = mysql_query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'version';") or die("<div class='error'>information_schema SELECT error</div>");
$vertable = mysql_fetch_array($vertable);

// version table exists, version 1.1 or higher
if($vertable[0] == 1) {
	$getver = mysql_query("SELECT * FROM version;") or die("<div class='error'>Unknown error</div>");
	$getver = mysql_fetch_array($getver);
	$oldversion = $getver[0];
} else {
	// no version table, so version 1.0
	$oldversion = "1.0";
}

if($oldversion == VERSION) {
    if(file_exists("../settings_user.php")) {
        include('head.php');
        echo "Installed version is already " . VERSION . ", delete the '/install' folder to unlock the application";
    } else {
        // incomplete installation, settings file needs to be created
        header("Location: step3.php");
        die();
    }
} else {
    include('head.php');
    ?>
    <form method="post" action="upgrade.php">
        Your current database version is <?php echo $oldversion; ?>, this installer will upgrade to version <?php echo VERSION; ?>
        <br /><br />
        Back up your existing database before continuing
        <br /><br />
        When you are ready type 'GO' in the box below and click Upgrade
        <br /><br />
        <input type="text" name="go" maxlength="2" />
        <br /><br />
        <input type="submit" name="submit" value="Upgrade" />
    </form>
<?php }

include('foot.php'); ?>
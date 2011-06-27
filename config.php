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

if(is_dir('install')) {
    // redirect to installer
    header("Location: install/");
    die();
}

// debug information, show errors
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);

// version
define('VERSION','1.1');

// database
if(!file_exists('settings_db.php')) die('settings_db.php Missing');
include('settings_db.php');

// user settings
if(!file_exists('settings_user.php')) die('settings_user.php Missing');
include('settings_user.php');

// paths
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = explode('/', $currentFile);
$currentFile = explode(".",$parts[count($parts) - 1]);
define('MYSELF',$currentFile[0]);
define('IMAGES_PATH',SITE_URL.'/images/');
define('INCLUDES_PATH','includes/');
define('CLASSES_PATH',INCLUDES_PATH.'classes/');
define('JQ_PATH',INCLUDES_PATH.'jquery/');

// includes
include(INCLUDES_PATH . 'session.php');
include(INCLUDES_PATH . 'replace_schars.php');
include(INCLUDES_PATH . 'samquery.php');
include(INCLUDES_PATH . 'cookie_check.php');
include(INCLUDES_PATH . 'redirect.php');
include(INCLUDES_PATH . 'error_handle.php');
include(INCLUDES_PATH . 'time_since.php');
include(INCLUDES_PATH . 'last_visit.php');
?>
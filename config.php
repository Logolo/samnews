<?php
// debug information, show errors
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);

// database
define('DB_HOST', 'localhost'); // MySQL hostname
define('DB_USER', 'root'); // MySQL database username
define('DB_PASSWORD', 'password'); // MySQL database password
define('DB_NAME', 'samnews'); // MySQL database name

// paths
$currentFile = $_SERVER["SCRIPT_NAME"];
$parts = explode('/', $currentFile);
$currentFile = explode(".",$parts[count($parts) - 1]);
define('MYSELF',$currentFile[0]);
define('SITE_URL', 'http://yoursite'); // public url to the installation, DO NOT INCLUDE A TRAILING SLASH
define('IMAGES_PATH',SITE_URL.'/images/');
define('INCLUDES_PATH','includes/');
define('CLASSES_PATH',INCLUDES_PATH.'classes/');
define('JS_PATH',INCLUDES_PATH.'js/');
define('JQ_PATH',JS_PATH.'jquery/');

// other constants
define('SAMNEWS_VERSION',"1.0");
define('SITE_NAME','SamNews');
define('SITE_SLOGAN','open-source social news application');
define('SITE_DESCRIPTION',''); // meta description
define('SITE_KEYWORDS',''); // meta keywords
define('DEVELOPER_EMAIL','email@localhost'); // change to your e-mail
define('DATETIME_NOW',date("Y-m-d H:i:s")); // time format
define('TIME_ZONE','EST');
define('RSS_KEY','1234'); // change to something unique - key used with rss generator cron
define('INDEX_INTERVAL','5 day'); // period in which articles remain on the index page, in SQL format
define('INDEX_DISPLAY',35); // number of links displayed on the index
define('RSS_DISPLAY',35); // number of links displayed in rss feeds
define('SESSION_EXPIRE', '21600'); // sessions expire after 6 hours (in seconds)
define('COOKIE_PREFIX','yoursite'); // something unique to your site, used when naming cookies
define('COOKIE_EXPIRE', '21'); // cookie expires after 21 days

// includes
include(INCLUDES_PATH . 'session.php');
include(INCLUDES_PATH . 'replace_schars.php');
include(INCLUDES_PATH . 'samquery.php');
include(INCLUDES_PATH . 'cookie_check.php');
include(INCLUDES_PATH . 'redirect.php');
include(INCLUDES_PATH . 'error_handle.php');
include(INCLUDES_PATH . 'get_host.php');
include(INCLUDES_PATH . 'time_since.php');
include(INCLUDES_PATH . 'last_visit.php');
?>
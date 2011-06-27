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
@mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Database connection failed, check your settings_db.php file");
@mysql_select_db(DB_NAME) or die("Database selection failed, check your settings_db.php file");

// check to see if version table exists
$vertable = mysql_query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'version';") or die("information_schema SELECT error");
$vertable = mysql_fetch_array($vertable);

if($vertable[0] == 1) {
	// database create already ran
	header("Location: step3.php");
	die();
}

if(isset($_POST['submit'])) {
$query = "-- category
CREATE TABLE IF NOT EXISTS `category` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(45) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=7;
INSERT INTO `category` (`id`, `name`) VALUES (1, 'everythingelse'), (2, 'technology'), (3, 'politics'), (4, 'coding'), (5, 'entertainment'), (6, 'oldposts');

-- comment
CREATE TABLE IF NOT EXISTS `comment` ( `id` int(11) NOT NULL AUTO_INCREMENT, `post` int(11) NOT NULL, `thread` int(11) DEFAULT NULL, `text` text NOT NULL, `author` int(11) NOT NULL, `score` int(11) NOT NULL, `ip` varchar(255) DEFAULT NULL, `created` datetime NOT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- post
CREATE TABLE IF NOT EXISTS `post` ( `id` int(11) NOT NULL AUTO_INCREMENT, `title` varchar(160) NOT NULL, `slug` varchar(255) NOT NULL, `url` varchar(600) NOT NULL, `domain` varchar(90) NOT NULL, `author` int(11) NOT NULL, `description` text, `category` int(11) DEFAULT NULL, `score` int(11) NOT NULL, `ip` varchar(255) DEFAULT NULL, `created` datetime NOT NULL, `comment_count` int(11) NOT NULL DEFAULT '0', PRIMARY KEY (`id`), UNIQUE KEY `uc_slug` (`slug`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- users
CREATE TABLE IF NOT EXISTS `users` ( `id` int(11) NOT NULL AUTO_INCREMENT, `login` varchar(12) NOT NULL, `password` varchar(45) NOT NULL, `email` varchar(150) NOT NULL, `about` varchar(300) DEFAULT NULL, `last_visit` datetime DEFAULT NULL, `ip` varchar(255) DEFAULT NULL, `created` datetime NOT NULL, `perm_mod` int(11) DEFAULT NULL, `perm_admin` int(11) DEFAULT NULL, `post_count` int(11) NOT NULL DEFAULT '0', `comment_count` int(11) NOT NULL DEFAULT '0', `vote_count` int(11) NOT NULL DEFAULT '0', `voted_count` int(11) NOT NULL DEFAULT '0', `forgot_key` varchar(150) DEFAULT NULL, `cookie_key` varchar(40) DEFAULT NULL, PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `users` (`login`, `password`, `email`, `about`, `last_visit`, `ip`, `created`, `perm_mod`, `perm_admin`, `post_count`, `comment_count`, `vote_count`, `voted_count`, `forgot_key`, `cookie_key`) VALUES ('[deleted]', 'nopass', '', 'black hole', NULL, NULL, NOW(), NULL, NULL, 0, 0, 0, 0, NULL, '" . sha1($_POST['login'] . $_POST['password'] . gethostbyaddr($_SERVER['REMOTE_ADDR'])) . "'), ('" . mysql_real_escape_string($_POST['login']) . "', '" . sha1($_POST['password']) . "', '" . mysql_real_escape_string($_POST['email']) . "', NULL, NULL, NULL, NOW(), NULL, 1, 0, 0, 0, 0, NULL, '" . sha1($_POST['login'] . $_POST['password'] . gethostbyaddr($_SERVER['REMOTE_ADDR'])) . "');

-- version
CREATE TABLE IF NOT EXISTS `version` ( `version` varchar(5) NOT NULL, PRIMARY KEY (`version`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1; 
INSERT INTO `version` (`version`) VALUES ('1.1'); 

-- vote_comment
CREATE TABLE IF NOT EXISTS `vote_comment` ( `comment` int(11) NOT NULL, `userid` int(11) NOT NULL, `created` datetime NOT NULL, PRIMARY KEY (`comment`,`userid`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- vote_post
CREATE TABLE IF NOT EXISTS `vote_post` ( `post` int(11) NOT NULL, `userid` int(11) NOT NULL, `created` datetime NOT NULL, PRIMARY KEY (`post`,`userid`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    // execute sql create
    foreach(preg_split("/(\r?\n)/", $query) as $sql_line){
        if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
            mysql_query($sql_line) or die(mysql_error());
        }
    }

	// database create already ran
	header("Location: step3.php");
	die();
}

include('head.php');
?>

<div class="step_head">Step 2 of <?php echo STEPS; ?> - Admin Account</div>

<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">

    <table>
        <tr>
            <td>Login</td><td><input name="login" type="text" value="admin" /></td>
        </tr>
        <tr>
            <td>Password</td><td><input name="password" type="text" value="password" /></td>
        </tr>
        <tr>
            <td>E-mail</td><td><input name="email" type="text" /></td>
        </tr>
    </table>
    
    <br /><br />
    
    <input type="submit" name="submit" value="Create DB Tables" />

</form>

<?php include('foot.php'); ?>
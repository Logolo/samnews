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

if(isset($_POST['submit']) && strtoupper($_POST['go']) == "GO") {
    // attempt to connect to database
    @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Database connection failed, check your settings_db.php file");
    @mysql_select_db(DB_NAME) or die("Database selection failed, check your settings_db.php file");

    $query = "-- category
    CREATE TABLE IF NOT EXISTS `category` ( `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(45) NOT NULL, PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=7;
    INSERT INTO `category` (`id`, `name`) VALUES (1, 'everythingelse'), (2, 'technology'), (3, 'politics'), (4, 'coding'), (5, 'entertainment'), (6, 'oldposts');

    -- post
    ALTER TABLE `post` ADD COLUMN `category` int(11);
    UPDATE `post` SET `category` = 6;
    
    -- users
    INSERT INTO `users` (`login`, `password`, `email`, `about`, `last_visit`, `ip`, `created`, `perm_mod`, `perm_admin`, `post_count`, `comment_count`, `vote_count`, `voted_count`, `forgot_key`, `cookie_key`) VALUES ('[deleted]', 'nopass', '', 'black hole', NULL, NULL, NOW(), NULL, NULL, 0, 0, 0, 0, NULL, '" . sha1(date('l jS \of F Y h:i:s A') . gethostbyaddr($_SERVER['REMOTE_ADDR'])) . "');
    
    -- version
    CREATE TABLE IF NOT EXISTS `version` ( `version` varchar(5) NOT NULL, PRIMARY KEY (`version`) ) ENGINE=MyISAM DEFAULT CHARSET=latin1; 
    INSERT INTO `version` (`version`) VALUES ('1.1');";

    // execute sql upgrade
    foreach(preg_split("/(\r?\n)/", $query) as $sql_line){
        if(trim($sql_line) != "" && strpos($sql_line, "--") === false){
            mysql_query($sql_line) or die(mysql_error());
        }
    }

}

header("Location: index.php");
die();
?>
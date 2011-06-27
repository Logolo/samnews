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

function auth($user, $password) {
	$verify = samq_c("SELECT id FROM users WHERE login = '" . esc($user) . "' AND password = '" . sha1($password) . "'",1);

	// verify user and password
	if(count($verify) == 1) {
		// valid
		// query details
		foreach (samq_c("SELECT id, login, perm_mod, perm_admin FROM users WHERE login = '" . esc($user) . "' AND password = '" . sha1($password) . "'",1) as $e) {
			$_SESSION['user'] = $e['login'];
			$_SESSION['user_id'] = $e['id'];
			if($e['perm_mod'] == 1) $_SESSION['access'] = 2;
			elseif($e['perm_admin'] == 1) $_SESSION['access'] = 3;
			else $_SESSION['access'] = 1;
		}
		return true;
	} else {
		// invalid name or password
		return false;
	}	
}
?>
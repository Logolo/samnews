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

// start session
session_start();

function destroy_cookie() {
	// destroy cookie
	if(isset($_COOKIE[COOKIE_PREFIX.'_chip'])){
		setcookie(COOKIE_PREFIX."_user", "", time()-60*60*24*COOKIE_EXPIRE, "/");
		setcookie(COOKIE_PREFIX."_chip", "", time()-60*60*24*COOKIE_EXPIRE, "/");
	}	
}

function destroy_session() {
	// destroy session
	session_unset();
	$_SESSION = array();
	unset($_SESSION['user'],$_SESSION['user_id'],$_SESSION['access'],$_SESSION['last_activity']);
	session_destroy();	
}

function logout() {
	destroy_cookie();
	destroy_session();
}

// check passage of time, destroy session after 2 hours
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_EXPIRE)) {
	destroy_session();
}

// update last activity time stamp
$_SESSION['last_activity'] = time();
?>
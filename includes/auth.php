<?php
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
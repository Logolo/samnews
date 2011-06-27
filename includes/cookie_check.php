<?php
// check for cookies
if(!isset($_SESSION['user'])) {
	// check to see if cookie exists
	if(isset($_COOKIE[COOKIE_PREFIX . '_user'],$_COOKIE[COOKIE_PREFIX . '_chip']) && $_COOKIE[COOKIE_PREFIX . '_user'] != "" && $_COOKIE[COOKIE_PREFIX . '_chip'] != ""){
		// verify cookie key and establish session
		foreach (samq_c("SELECT id, login, perm_mod, perm_admin FROM users WHERE cookie_key = '" . esc($_COOKIE[COOKIE_PREFIX . '_chip']) . "' AND login = '" . esc($_COOKIE[COOKIE_PREFIX . '_user']) . "'",1) as $e) {
			$_SESSION['user'] = $e['login'];
			$_SESSION['user_id'] = $e['id'];
			if($e['perm_mod'] == 1) $_SESSION['access'] = 2;
			elseif($e['perm_admin'] == 1) $_SESSION['access'] = 3;
			else $_SESSION['access'] = 1;
		}
	}
}
?>
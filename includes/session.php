<?php
// start session
session_start();

function destroy_cookie() {
	// destroy cookie
	if(isset($_COOKIE[COOKIE_PREFIX . '_chip'])){
		setcookie(COOKIE_PREFIX . "_user", "", time()-60*60*24*COOKIE_EXPIRE, "/");
		setcookie(COOKIE_PREFIX . "_chip", "", time()-60*60*24*COOKIE_EXPIRE, "/");
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

// check passage of time
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_EXPIRE)) {
	destroy_session();
}

// update last activity time stamp
$_SESSION['last_activity'] = time();
?>
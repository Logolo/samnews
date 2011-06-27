<?php
// if user attempts to visit login, register, or forgot pw page while already logged in, redirect to index
if( (isset($_SESSION['user']) && MYSELF == "login" && !isset($_REQUEST['out'])) || (isset($_SESSION['user']) && MYSELF == "forgot") || (isset($_SESSION['user']) && MYSELF == "register") ) {
	// user is already logged in
	header("Location: " . SITE_URL);
}
?>
<?php
if(isset($_SESSION['user'])) {
	// update last visit
	samq_u("users",array("last_visit"),array(DATETIME_NOW),"id = " . $_SESSION['user_id']);
}
?>
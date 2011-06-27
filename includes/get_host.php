<?php
function get_host($ip) {
	return gethostbyaddr($ip);
}
?>
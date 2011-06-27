<?php
function error($query, $sql_error, $user, $other_error=NULL) {
	// error style
	echo "<style type=\"text/css\">";
	echo ".error_table {";
	echo "	font-family: Verdana, Arial, Helvetica, sans-serif;";	
	echo "	font-size: 12px;";
	echo "	background: #ffffff;";
	echo "	border: 1px solid #bbbbbb;";
	echo "	padding: 4px;";
	echo "}";
	
	echo ".error_table td {";
	echo "	border: 1px solid #ffffff;";
	echo "}";
	
	echo ".error_table_title {";
	echo "	background-color:#FFCCFF;";
	echo "	font-weight:bold;";
	echo "	font-size: 14px;";
	echo "}";
	
	echo ".error_table_head {";
	echo "	font-weight:bold;";
	echo "}";
	echo "</style>";

	echo "<br /><br /><table class='error_table' align='center' width='700'>";
	echo "<tr><td colspan='2' align='center' class='error_table_title'>ERROR</td></tr>";

	if(isset($query) || isset($sql_error)) {
		echo "<tr><td class='error_table_head'>SQL Error</td><td><font style='background:yellow'>" . htmlentities($sql_error) . "</font></td></tr>";
		echo "<tr><td class='error_table_head'>Query</td><td>" . htmlentities($query) . "</td></tr>";
	}
	
	if(isset($other_error)) {
		echo "<tr><td class='error_table_head'>Message</td><td><font style='background:yellow'>" . htmlentities($other_error) . "</font></td></tr>";
	}

	echo "<tr><td class='error_table_head'>URL</td><td>" . $_SERVER['REQUEST_URI'] . "</td></tr>";
	echo "<tr><td width='15%' class='error_table_head'>User</td><td>" . $user . "</td></tr>";
	echo "<tr><td class='error_table_head'>Time</td><td>" . date("m/d/y h:i a",time()) . "</td></tr>";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	echo "<tr><td colspan='2' align='center'>Copy this error (or save the page as a PDF), and e-mail it to <a href='mailto:" . DEVELOPER_EMAIL . "'>" . DEVELOPER_EMAIL . "</a> for assistance.<br />" .
			"Please include a description of what you were trying to do.";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	echo "<tr><td colspan='2'>You may attempt to <a href='javascript: history.go(-1)'>RETURN</a> to where you were</td></tr>";
	echo "</table><br /><br />";

	die();
}
?>
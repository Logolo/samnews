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

function error($query, $sql_error, $user, $other_error=NULL) {

	// error style
	echo "<style type=\"text/css\">";
	echo ".error_table {";
	echo "	font-family: Verdana, Arial, Helvetica, sans-serif;";
	echo "	font-size: 12px;";
	echo "	border: 1px solid #bbbbbb;";
	echo "	background: #ffffff;";
	echo "	padding: 4px;";
	echo "}";

	echo ".error_table td {";
	echo "	border: 1px solid #ffffff;";
	echo "  vertical-align: top;";
	echo "}";

	echo ".error_table_title {";
	echo "	background-color:#FFCCFF;";
	echo "	font-weight:bold;";
	echo "	font-size: 14px;";
	echo "}";

	echo ".error_table_head {";
	echo "	font-weight:bold;";
	echo "}";

	echo "textarea {";
	echo "  width:98%;";
	echo "  height:300px;";
	echo "}";

	echo "</style>";

	echo "<br /><br /><table class='error_table' align='center' width='700'>";
	echo "<tr><td colspan='2' align='center' class='error_table_title'>ERROR</td></tr>";

	if(isset($query) || isset($sql_error)) {
		echo "<tr><td class='error_table_head' nowrap>SQL Error</td><td><font style='background:yellow'>" . htmlentities($sql_error) . "</font></td></tr>";
		echo "<tr><td class='error_table_head'>Query</td><td><textarea>" . htmlentities($query) . "</textarea></td></tr>";
	}

	if(isset($other_error)) {
		echo "<tr><td class='error_table_head'>Message</td><td><font style='background:yellow'>" . htmlentities($other_error) . "</font></td></tr>";
	}

	echo "<tr><td class='error_table_head'>URL</td><td>" . $_SERVER['REQUEST_URI'] . "</td></tr>";
	echo "<tr><td width='15%' class='error_table_head'>User</td><td>" . $user . "</td></tr>";
	echo "<tr><td class='error_table_head'>Time</td><td>" . date("m/d/y h:i a",time()) . "</td></tr>";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	echo "<tr><td colspan='2' align='center'>Copy this error (or save the page as a PDF), and e-mail it to <a href='mailto:" . SUPPORT_EMAIL . "'>" . SUPPORT_EMAIL . "</a> for assistance.<br />" .
			"Please include a description of what you were trying to do.</td></tr>";
	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	echo "<tr><td colspan='2'>You may attempt to <a href='javascript: history.go(-1)'>RETURN</a> to where you were</td></tr>";
	echo "</table><br /><br />";

	die();

}
?>
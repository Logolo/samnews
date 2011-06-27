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

include('config.php');
include('head.php'); ?>

<br />

<div class="content">
<span class="page_title">search</span><br />

<form method="get" action="http://www.google.com/search">
	<input type="hidden" value="Search" />
	<input style="visibility:hidden" type="radio" name="sitesearch" value="<?php echo SITE_URL; ?>" checked="checked" />
    <table class="form_table" width="500">
		<tr><td><strong>keyword</strong><br /><input type="text" name="q" style="width:98%;" /></td></tr>
	</table>
	<br />
	<input type="submit" value="submit" />
</form>

<br /><br />

<?php include('foot.php'); ?>
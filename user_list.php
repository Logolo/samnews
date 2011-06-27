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

// permission check
if (isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) {

	if(isset($_GET['sort'])) {
		switch ($_GET['sort']) {
			case 0:
				$sort = "login ASC";
				break;
			case 1:
				$sort = "last_visit DESC";
				break;
			case 2:
				$sort = "created DESC";
				break;
		}
	} else {
		$sort = "login ASC";
	}
	
	// query users
	$users = samq("users","login,email,voted_count AS user_score,last_visit,created,ip",NULL,NULL,$sort);

	include('head.php'); ?>
	
	<br />
	
	<div class="content">
	<span class="page_title">users</span><br />
    	
    <form method="get" action="<?php echo SITE_URL;?>/ulist">
        <select name="sort" />
            <option value=0>login</option>
            <option value=1<?php if(isset($_GET['sort']) && $_GET['sort'] == 1) echo " selected"; ?>>last visit</option>
            <option value=2<?php if(isset($_GET['sort']) && $_GET['sort'] == 2) echo " selected"; ?>>created</option>
        </select> <input type="submit" value="sort" />
    </form>
    <br />
    
	<table class="form_table" width="75%">
		<tr><td>
		
		<table class="alist">
        	
            <tr>
            	<td width="20%"><em>user</em></td>
            	<td width="25%"><em>email</em></td>
               	<td width="10%"><em>last visit</em></td>
               	<td width="10%"><em>created</em></td>
                <td width="25%"><em>ip</em></td>
				<td width="5%"></td>
                <td width="5%"></td>
            </tr>
        
        	<?php foreach ($users as $e) { ?>
			<tr>
				<td><a href="<?php echo SITE_URL . '/u/' . $e['login']; ?>"><strong><?php echo $e['login']; ?></strong></a> (<?php echo $e['user_score']; ?>)</td>
                <td><?php echo $e['email']; ?></td>
                <td><?php if($e['last_visit'] != "") echo date("m/d/y h:i a",strtotime($e['last_visit'])); ?></td>
                <td><?php echo date("m/d/y h:i a",strtotime($e['created'])); ?></td>
                <td><?php echo $e['ip']; ?></td>
                <td><span class="admin_link"><a href="<?php echo SITE_URL; ?>/edit/u/<?php echo $e['login']; ?>">edit</a></span></td>
                <td><?php if($e['login'] != "[deleted]" && $e['login'] != $_SESSION['user']) { ?><span class="admin_link"><a href="<?php echo SITE_URL; ?>/delete/u/<?php echo $e['login']; ?>">delete</a></span><?php } ?></td>
			</tr>
            <?php } ?>

		</table>
		
		</td></tr>
	</table>
	<br />
	
	</div>
	
	<br /><br />

<?php
	include('foot.php');
} else {
	header("Location: " . SITE_URL);
	die();
} ?>
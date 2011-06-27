<?php include('config.php');

if (isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) {

	if(isset($_REQUEST['sort'])) {
		switch ($_REQUEST['sort']) {
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
            <option value=1<?php if(isset($_REQUEST['sort']) && $_REQUEST['sort'] == 1) echo " selected"; ?>>last visit</option>
            <option value=2<?php if(isset($_REQUEST['sort']) && $_REQUEST['sort'] == 2) echo " selected"; ?>>created</option>
        </select> <input type="submit" value="sort" />
    </form>
    <br />
    
	<table class="form_table" width="75%">
		<tr><td>
		
		<table class="ulist">
        	
            <tr>
            	<td><em>user</em></td>
            	<td><em>email</em></td>
               	<td><em>last visit</em></td>
               	<td><em>created</em></td>
                <td><em>ip</em></td>
				<td></td>
                <td></td>
            </tr>
        
        	<?php foreach ($users as $e) { ?>
			<tr>
				<td><a href="<?php echo SITE_URL . '/u/' . $e['login']; ?>"><strong><?php echo $e['login']; ?></strong></a> (<?php echo $e['user_score']; ?>)</td>
                <td><?php echo $e['email']; ?></td>
                <td><?php if($e['last_visit'] != "") echo date("m/d/y h:i a",strtotime($e['last_visit'])); ?></td>
                <td><?php echo date("m/d/y h:i a",strtotime($e['created'])); ?></td>
                <td><?php echo $e['ip']; ?></td>
                <td><span class="admin_link"><a href="<?php echo SITE_URL; ?>/edit/u/<?php echo $e['login']; ?>">edit</a></span></td>
                <td><span class="admin_link"><a href="<?php echo SITE_URL; ?>/delete/u/<?php echo $e['login']; ?>">delete</a></span></td>
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
} ?>
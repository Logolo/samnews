<?php include('config.php');

$profile = samq_c("SELECT login, about, last_visit, created, perm_mod, perm_admin, post_count, comment_count, vote_count, email, ip, voted_count AS score FROM users WHERE login = '" . $_REQUEST['user'] . "'",1);

include('head.php');

if(count($profile) > 0) { ?>
<br />

<div class="content">

<?php foreach ($profile as $e) { ?>
    <span class="page_title"><?php echo $e['login']; ?></span> <span class="profile_score"><?php echo $e['score']; ?></span><br />
		<?php if(isset($e['about']) && trim($e['about']) != "") { ?>
        <table class="form_table" width="350">
			<tr><td><strong>about</strong><br />
			<?php echo $e['about']; ?></td></tr>
		</table>
		<br />
		<?php } ?>
        <?php if((isset($e['perm_mod']) && $e['perm_mod'] == 1) || (isset($e['perm_admin']) && $e['perm_admin'] == 1)) { ?>
        <table class="form_table" width="350">
            <tr><td><strong>badges</strong><br />
            <?php if($e['perm_mod'] == 1) echo "<img src='" . IMAGES_PATH . "mod_badge.png' title='moderator' alt='moderator' />"; ?>
			<?php if($e['perm_admin'] == 1) echo "<img src='" . IMAGES_PATH . "admin_badge.png' title='administrator' alt='administrator' />"; ?>
			</td></tr>
        </table>
        <?php } ?>
        <br />
        <table class="form_table" width="350">
            <tr><td><strong>stats</strong><br />
            last visit: <?php if(isset($e['last_visit'])) echo time_since(strtotime($e['last_visit'])); else echo "never"; ?><br />
            joined: <?php echo time_since(strtotime($e['created'])) . " (" . date("M j, Y",strtotime($e['created'])) . ")"; ?><br />
            <br />
            <?php if($e['post_count'] != 0) { ?><a href="<?php echo SITE_URL; ?>/submissions/<?php echo $e['login']; ?>"><?php } ?><?php echo $e['post_count']; ?> submissions<?php if($e['post_count'] != 0) { ?></a><?php } ?> | <?php if($e['vote_count'] != 0) { ?><a href="<?php echo SITE_URL; ?>/voted/<?php echo $e['login']; ?>"><?php } ?><?php echo $e['vote_count']; ?> votes cast<?php if($e['vote_count'] != 0) { ?></a><?php } ?> | <?php if($e['comment_count'] != 0) { ?><a href="<?php echo SITE_URL; ?>/u/<?php echo $e['login']; ?>/comments"><?php } ?><?php echo $e['comment_count']; ?> comments<?php if($e['comment_count'] != 0) { ?></a><?php } ?>
            </td></tr>
        </table>
		<?php // if the user is looking at his own profile, show controls
        if(isset($_SESSION['user']) && $_SESSION['user'] == $e['login']) { ?>
        <br />
        <table class="uc_table" width="350">
            <tr><td><strong>user controls</strong><br />
            account email: <?php echo $e['email']; ?><br />
            change: <a href="<?php echo SITE_URL; ?>/reset/about">about</a> | <a href="<?php echo SITE_URL; ?>/reset/email">email</a> | <a href="<?php echo SITE_URL; ?>/reset/pw">password</a></td></tr>
        </table>
		<?php } ?>
        
		<?php // if user is logged in as admin or moderator, moderators cannot pass if they are trying to edit another moderator or admin
		if( isset($_SESSION['access']) && (($_SESSION['access'] == 2 && $e['perm_mod'] != 1 && $e['perm_admin'] != 1) || $_SESSION['access'] == 3)) { ?>
        <br />
        <table class="admin_table" width="350">
            <tr><td><strong>admin controls</strong><br />
            email: <a href="mailto:<?php echo $e['email']; ?>"><?php echo $e['email']; ?></a><br />
            last ip: <?php if(isset($e['ip'])) echo $e['ip']; else echo "none"; ?><br />
            <br />
            <a href="<?php echo SITE_URL; ?>/edit/u/<?php echo $e['login']; ?>">edit user</a> | <a href="<?php echo SITE_URL; ?>/delete/u/<?php echo $e['login']; ?>">delete user</a> | <a href="<?php echo SITE_URL; ?>/ulist">user list</a></td></tr>
        </table>
		<?php } ?>
<?php }?>
</div>

<br /><br />
<? } else { ?>
	<div class="content"><br />doesn't exist, <a href="javascript:history.go(-1);">back</a></div><br /><br />
<?php }
include('foot.php'); ?>
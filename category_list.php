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
if (isset($_SESSION['access']) && $_SESSION['access'] == 3) {

    // handle submit
    if(isset($_POST['submit'])) {
        // determine insert or update
        if(isset($_POST['id'])) {
            // update
            samq_u("category",array("name"),array($_POST['name']),"id=".esc($_POST['id']));
            $success = "category updated";
        } else {
            // insert
            samq_i("category",array("name"),array($_POST['name']));
            $success = "category added";
        }
    }

    // query list
    $cat = samq("category","*,(SELECT COUNT(post.id) FROM post WHERE post.category = category.id) AS post_count",NULL,NULL,"name");
    
    // query category, if editing
    if(isset($_GET['cat'])) $cat_result = samq("category","*",NULL,"id = " . esc($_GET['cat']));

    include('head.php'); ?>

    <br />
	<div class="content">
	<span class="page_title">categories</span><br />
    
    <?php
    // echo success message
	if(isset($success)) { echo "<br /><div class='success'>" . $success . "</div><br /><br /><a href='" . SITE_URL . "/ctlist'>done</a>"; } else { ?>
    
        <?php // category list ?>
        <table class="form_table" width="40%">
            <tr><td>
                <table class="alist">
                    <tr>
                        <td width="10%"><em>id</em></td>
                        <td width="70%"><em>name</em></td>
                        <td width="10%"><em>posts</em></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                    </tr>

                    <?php
                        // list categories
                        if(count($cat) > 0) {
                            foreach($cat as $e) { ?>
                                <tr><td><?php echo $e['id']; ?></td>
                                    <td><?php echo $e['name']; ?></td>
                                    <td><?php echo $e['post_count']; ?></td>
                                    <td><span class="admin_link"><a href="<?php echo SITE_URL; ?>/edit/ct/<?php echo $e['id']; ?>">edit</a></span></td>
                                    <td><?php if($e['id'] != 1) { ?><span class="admin_link"><a href="<?php echo SITE_URL; ?>/delete/ct/<?php echo $e['id']; ?>">delete</a></span><?php } ?></td>
                                </tr>
                            <?php }
                        } else {
                            echo "<tr><td colspan='5'>none</td></tr>";
                        }
                    ?>
                </table>
            </td></tr>
        </table>

        <br />

        <form method="post" action="<?php echo SITE_URL; ?>/ctlist">
            <table class="admin_table" width="300">
                <tr><td><u><?php echo (isset($_GET['cat'])) ? "editing" : "adding"; ?></u></td></tr>
                
                <?php if(isset($_GET['cat'])) { // editing ?>
                    <input type="hidden" name="id" value="<?php echo $_GET['cat']; ?>" />
                    <tr><td><strong>id</strong><input type="text" style="width:98%;" value="<?php echo $_GET['cat']; ?>" disabled /></td></tr>
                <?php } ?>

                <tr><td><strong>name</strong><br /><input type="text" name="name" style="width:98%;" maxlength="45" value="<?php if(isset($_GET['cat'])) echo $cat_result[0]['name']; ?>" /></td></tr>
            </table>
            <br />
            
            <?php if(isset($_GET['cat'])) { ?>
                <input type="button" value="cancel" onClick="location.href='<?php echo SITE_URL . "/ctlist"; ?>'" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php } ?>
            
            <input type="submit" name="submit" value="submit" />
        </form>

    <?php } ?>
    </div>

    <br /><br />

<?php
	include('foot.php');
} else {
	header("Location: " . SITE_URL);
	die();
} ?>
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
include(CLASSES_PATH . 'SimpleHTML.class.php');

if( !isset($_SESSION['access']) || (isset($_SESSION['access']) && $_SESSION['access'] != 3) ) die('Forbidden');

include('head.php'); ?>

<br />

<div class="content">
<span class="page_title">submit bulk (experimental)</span><br />

<?php // FIRST SUBMIT
if(isset($_POST['bulk'])) {
	// break up links into array
	$bulk = $_POST['bulk'];
	$link = explode("\n",$bulk);

	echo "<form action='" . SITE_URL . "/submit/bulk' method='post'>";

	$i = 0;

	// loop through each link
	foreach($link as $e) {

		$i++;

		// get domain (for relative img paths)
		$domain = parse_url(trim($e), PHP_URL_SCHEME) . "://" . parse_url(trim($e), PHP_URL_HOST);

		// grab and implode file data
		$html = file_get_html(trim($e));

		// get page title
		foreach($html->find('title') as $a) {
			$a = trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), " ", $a->plaintext));
			if($a != "") {
				$title=$a;
				break;
			}
		}
		if(!isset($title)) $title = "";

		// loop through each p tag
		foreach($html->find('p') as $b) {
			$b = trim(preg_replace(array('/\s{2,}/', '/[\t\n]/'), " ", $b->plaintext));
			if($b != "" && strlen($b) > 100) {
				$description=$b;
				break;
			} else $description="";
		}

		/*

		// unset previous img_choice
		unset($img_choice);
		$img_choice = array();

		// loop through each img tag
		foreach($html->find('img') as $c) {
			// after 3 potential choices, break loop
			if(count($img_choice) == 3) break;

			// check to see if image is relative or not
			$parsed = parse_url($c->src);
			if(empty($parsed['host'])) $getimg = $domain . $c->src; else $getimg = $c->src;

			// see if URL exists
			if(@file_get_contents($getimg,0,NULL,0,1)) {
				list($w, $h) = getimagesize($getimg);

				// make sure image host isn't restricted
				if(empty($parsed['host']) || !in_array($parsed['host'],array("ad.doubleclick.net","adserver.adtech.de"))) {
					// see if size of image is greater than 100px
					if($w >= 100 && $h >= 100) $img_choice[] = $getimg;
				}
			}
		}
		
		*/

		echo "<br /><br /><table style='width:800px;' class='alist'>";
		echo "<tr><td width='5%'>Count</td><td>" . $i . "</td></tr>";
		echo "<tr><td>URL</td><td><input name='url_" . $i . "' value='" . htmlentities(trim($e)) . "' style='width:98%;' /></td></tr>";
		echo "<tr><td>Title</td><td><input name='title_" . $i . "' value='" . htmlentities($title) . "' style='width:98%;' /></td></tr>";
		
		/*
		echo "<tr><td style='vertical-align:top;'>Image</td><td>";
		if(count($img_choice) > 0) echo "<table><tr>";
		foreach($img_choice as $d) {
			echo "<td><img src='" . htmlentities($d) . "' height='100' /><br /><input type='checkbox' name='img_" . $i . "' value='$d' /> Select</td>";
		}
		if(count($img_choice) > 0) echo "</tr></table>";
		echo "</td></tr>";
		*/

		echo "<tr><td style='vertical-align:top;'>Description:</td><td><textarea name='description_" . $i . "' style='width:98%;' cols='5'>" . htmlentities($description) . "</textarea></td></tr>";
		echo "<tr><td>Category</td><td><select name='category_" . $i . "'>";
		foreach(samq("category","*",NULL,NULL,"name") as $e) echo "<option value='" . $e['id'] . "'>" . $e['name'] . "</option>";
		echo "</select></td></tr>";
		echo "<tr><td>Exclude</td><td><input type='checkbox' name='exc_" . $i . "' value='1' /></td></tr>";
		echo "</table><br />";
		
	    // clean up memory
	    $html->clear();
	    unset($html);
	}
	echo "<br /><br />";
	echo "<input type='hidden' name='count' value='" . $i . "' />";
	echo "<input type='submit' value='submit'></form><br /><br />";
}

// SECOND SUBMIT
if(isset($_POST['count'])) {
	include(INCLUDES_PATH . 'slugify.php');
	$i = 1;
	while($i <= $_POST['count']) {
		$url = $_POST['url_' . $i];
		if(!isset($_POST['exc_' . $i])) {
			$domain = parse_url(trim($url), PHP_URL_HOST);
			$domain = str_replace("www.","",$domain);
			$title = $_POST['title_' . $i];
			$slug = slugify(replace_schars(trim($_POST['title_' . $i])));
			if(trim($_POST['description_' . $i]) == "") $description = NULL; else $description = $_POST['description_' . $i];
			$category = $_POST['category_' . $i];
	
			//insert
			samq_i("post",array("title","slug","url","domain","author","description","category","score","ip","created"),array($title,$slug,$url,$domain,$_SESSION['user_id'],$description,$category,1,gethostbyaddr($_SERVER['REMOTE_ADDR']),DATETIME_NOW));	
	
			// add one to user's score
			samq_c("UPDATE users SET post_count = post_count + 1 WHERE id = " . esc($_SESSION['user_id']));	
	
			echo "<br /><strong>INSERT SUCCESS</strong><br />";
			echo "<u>Count</u> " . $i . "<br />";
			echo "<u>URL</u> " . htmlentities($url) . "<br />";
			echo "<u>Domain</u> " . htmlentities($domain) . "<br />";
			echo "<u>Title</u> " . htmlentities($title) . "<br />";
			echo "<u>Slug</u> " . htmlentities($slug) . "<br />";
			echo "<u>Description</u> " . htmlentities($description) . "<br />";
			echo "<u>Category</u> " . htmlentities($category) . "<br />";
		} else {
			echo "<br /><strong>EXCLUDED</strong><br />";
			echo "<u>Count</u> " . $i . "<br />";
			echo "<u>URL</u> " . $url . "<br />";
		}
		echo "<br /><hr />";
		
		$i++;
	}
}

// PASTE BOX
if(!isset($_POST['bulk']) && !isset($_POST['submit'])) { ?>
<form action="<?php echo SITE_URL; ?>/submit/bulk" method="post">
    <table class="form_table" width="500">
		<tr><td><strong>one url per line</strong><br /><textarea name="bulk" style="width:800px;height:250px;"></textarea></td></tr>
	</table>
	<br />
	<input type="submit" value="submit" />
</form>
<?php } include('foot.php'); ?>
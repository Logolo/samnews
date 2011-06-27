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

$view = samq_c("SELECT post.id, users.id AS author_id, title, slug, url, description, domain, post.comment_count, login,
				users.voted_count AS user_score, post.score AS post_score, post.created, category.name AS cat_name FROM post
				INNER JOIN users ON author = users.id
				INNER JOIN category ON category = category.id
				WHERE slug = '" . esc($_GET['post']) . "'",1);

include(INCLUDES_PATH . 'make_clickable.php');
include('head.php');

if(count($view) > 0) { ?>
    <table class="listing_table">
        <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
    
        <?php
        foreach ($view as $e) {
            // check to see if user is logged in
            if(isset($_SESSION['user'])) {
                // see if user has submitted this entry
                if($_SESSION['user_id'] == $e['author_id']) {
                    $vote_check = "vote";
                } elseif(count(samq("vote_post","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND post = " . $e['id'])) > 0) {
                    $vote_check = "vote";	
                } else {
                    $vote_check = "novote";	
                }
            } else {
                // user is not logged in, flag to remove hyperlink from vote arrow
                $vote_check = "guest";
            } ?>
            <tr class="listing_top_tr">
                <td class="listing_left_spacer1_td"></td>
                <td class="listing_count_td"></td>
                <td class="listing_left_spacer2_td"></td>
                <td class="listing_votes_td">
					<span class="listing_votes_outer" id="listing_votes_outer<?php echo $e['id']; ?>">
						<a href="<?php echo SITE_URL; ?>/v/<?php echo $e['slug']; ?>">
							<span id="listing_votes_inner<?php echo $e['id']; ?>"><?php echo $e['post_score']; ?></span>
						</a>
					</span>
				</td>
                <td class="listing_up_td">
                    <span<?php if($vote_check == "vote") echo " style='display:none;'"; ?> id="vote_arrow<?php echo $e['id']; ?>">
                        <?php if($vote_check != "guest") { ?><a href="#" class="vote_up" id="<?php echo $e['id']; ?>"><?php } ?>
                            <img src="<?php echo IMAGES_PATH; ?>up.gif" alt="vote up" />
                        <?php if($vote_check != "guest") { ?></a><?php } ?>
                    </span>
                    <span<?php if($vote_check != "vote" || $vote_check == "guest") echo " style='display:none;'"; ?> id="voted_arrow<?php echo $e['id']; ?>">
                        <img src="<?php echo IMAGES_PATH; ?>up_voted.gif" alt="voted up" />
                    </span>
                </td>
                <td>
					<span class="listing_title">
						<a href="<?php echo htmlentities($e['url']); ?>" target="_blank"><?php echo $e['title']; ?></a>
					</span>
					<span class="listing_category">
						<?php echo $e['cat_name']; ?>
					</span>
					<?php if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) { ?>
						<span class="admin_link"><a href="<?php echo SITE_URL . "/edit/p/" . $e['id'] ?>">edit</a></span>
						<span class="admin_link"><a href="<?php echo SITE_URL . "/delete/p/" . $e['id'] ?>">delete</a></span>
					<?php } ?>
				</td>
			</tr>
            <tr>
				<td colspan="5"></td>
				<td>
					<span class="listing_details">
						submitted <?php echo time_since(strtotime($e['created'])); ?> by <a href="<?php echo SITE_URL; ?>/u/<?php echo $e['login']; ?>"><?php echo $e['login']; ?></a> (<?php echo $e['user_score'];?>)
						| <span class="listing_domain"><?php echo $e['domain']; ?></span>
						| <a href="#comment_start"><?php if($e['comment_count'] == 0) { echo "no comments"; } else { echo $e['comment_count'] . " comment" . (($e['comment_count'] > 1) ? "s" : ""); } ?></a>
					</span>
				</td>
			</tr>
            <tr>
				<td colspan="5"></td>
				<td style="padding-top:10px;">
					<!-- Facebook -->
					<div style="float:left;">
						<iframe
							src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(SITE_URL); ?>%2Fv%2F<?php echo $e['slug']; ?>&amp;layout=standard&amp;show_faces=true&amp;width=210&amp;action=like&amp;font=verdana&amp;colorscheme=light&amp;height=30"
							scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:210px; height:30px;" allowTransparency="true"></iframe>
					</div>
					
					<!-- Twitter -->
					<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo SITE_NAME; ?>">Tweet</a>
					<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
				</td>
			</tr>

			<?php // detect embeds
			
			// YOUTUBE
			if($e['domain'] == "youtube.com") {
				preg_match('/[\\?\\&]v=([^\\?\\&]+)/',$e['url'],$matches);
				if(count($matches) > 1) {
					$embed = true;
					$embed_code =	"<object width='480' height='344'>
										<param name='movie' value='http://www.youtube.com/v/" . $matches[1] . "?fs=1&amp;hl=en_US&amp;color1=FFFFFF&amp;color2=FFFFFF'></param>
										<param name='allowFullScreen' value='true'></param>
										<param name='allowscriptaccess' value='always'></param>
										<embed src='http://www.youtube.com/v/" . $matches[1] . "?fs=1&amp;hl=en_US&amp;color1=FFFFFF&amp;color2=FFFFFF' type='application/x-shockwave-flash'
											allowscriptaccess='always' allowfullscreen='true' width='480' height='344'></embed>
									</object>";
				}
			}
			// LIVELEAK
			elseif($e['domain'] == "liveleak.com") {
				preg_match('/[\\?\\&]i=([^\\?\\&]+)/',$e['url'],$matches);
				if(count($matches) > 1) {
					$embed = true;
					$embed_code =	"<object width='450' height='370'>
										<param name='movie' value='http://www.liveleak.com/e/" . $matches[1] . "'></param>
										<param name='wmode' value='transparent'></param>
										<param name='allowscriptaccess' value='always'></param>
										<embed src='http://www.liveleak.com/e/" . $matches[1] . "' type='application/x-shockwave-flash'
											wmode='transparent' allowscriptaccess='always' width='450' height='370'></embed>
									</object>";
				}
			}		
			// GAMETRAILERS
			elseif($e['domain'] == "gametrailers.com") {
				preg_match('/video\/(.+?)\/([0-9]{1,9})/',$e['url'],$matches);
				if(count($matches) > 2) {
					$embed = true;
					$embed_code =	"<embed width='640' height='391' src='http://media.mtvnservices.com/mgid:moses:video:gametrailers.com:" . $matches[2] . "' quality='high'
									bgcolor='000000' name='efp' align='middle' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'
									flashvars='autoPlay=false' allowfullscreen='true'></embed>";
				}
			}
			else $embed = false;

			if(isset($e['description']) || $embed == true) { ?>
                <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
                <tr><td colspan="5"></td><td>
                <?php if($embed) echo $embed_code . "<br /><br />"; ?>

                <?php // DESCRIPTION
                if(isset($e['description'])) { ?>
                <div class="view_description"><?php echo make_clickable(nl2br($e['description'])); ?></div>
                <?php }
                    } ?>
				</td></tr>
	        <?php } ?>
			<tr class="listing_spacer_tr"><td colspan="6"></td></tr>
            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
        
			<?php // comment form
            if(isset($_SESSION['user'])) { ?>
            <script type="text/javascript">
            jQuery(function() {
                <!-- jQuery autoresize comment textarea -->
                $("#comment_input").elastic();
    
                <?php if($_SESSION['user'] == 2 || $_SESSION['access'] == 3) { ?>
                <!-- jQuery inline edit -->
                $("a.edit_link").click(function(){
                    this_id = this.id;
                    comment_text = $("#comment_text" + this_id).text();
                    $("#reply_link_div" + this_id).after('<div id="reply_div' + this_id + '" class="reply_div">'+
														 '	<form method="post" action="<?php echo SITE_URL . "/comment"; ?>">'+
														 '		<input type="hidden" name="post_id" value="<?php echo $view[0]['id']; ?>" />'+
														 '		<input type="hidden" name="post_slug" value="<?php echo $view[0]['slug']; ?>" />'+
														 '		<input type="hidden" name="edit" value="' + this_id + '" />'+
														 '		<strong>edit</strong>'+
														 '		<div style="padding-bottom:4px;">'+
														 '			<textarea name="comment_input" id="comment_input' + this_id + '" rows="1" style="width:100%;" /></textarea>'+
														 '		</div>'+
														 '		<input type="submit" name="submit" value="submit" />&nbsp;&nbsp;&nbsp;'+
														 '		<span class="admin_link"><input type="checkbox" name="delete" />&nbsp;delete</span>'+
														 '		&nbsp;&nbsp;&nbsp;<a href="#" class="reply_cancel_link" id="' + this_id + '">cancel</a></form>'+
														 '</div>');
                    $("#comment_input" + this_id).val(comment_text);
                    $("#comment_input" + this_id).elastic();
                    $("#reply_link_div" + this_id).hide();
					$("#edit_link_div" + this_id).hide();
                    return false;
                });
                <?php } ?>
    
                <!-- jQuery inline replies -->
                $("a.reply_link").click(function(){
                    this_id = this.id;
                    thread_id = $("input[name=thread" + this_id + "]").attr("id");
                    $("#reply_link_div" + this_id).after('<div id="reply_div' + this_id + '" class="reply_div"><form method="post" action="<?php echo SITE_URL . "/comment"; ?>">'+
                                                         '<input type="hidden" name="post_id" value="<?php echo $view[0]['id']; ?>" />'+
                                                         '<input type="hidden" name="post_slug" value="<?php echo $view[0]['slug']; ?>" />'+
                                                         '<input type="hidden" name="comment_thread" value="' + thread_id + '" />'+
                                                         '<strong>reply</strong><div style="padding-bottom:4px;">'+
                                                         '<textarea name="comment_input" id="comment_input' + this_id + '" rows="1" style="width:100%;" /></textarea>'+
                                                         '</div><input type="submit" name="submit" value="submit" />&nbsp;&nbsp;&nbsp;<a href="#" class="reply_cancel_link" id="' + this_id + '">cancel</a></form></div>');
                    $("#comment_input" + this_id).elastic();
                    $("#reply_link_div" + this_id).hide();
                    return false;
                });
    
                <!-- jQuery reply cancel -->
                $('a.reply_cancel_link').live('click', function() {
                    this_id = this.id;
                    $('#reply_div' + this_id).remove();
                    $("#reply_link_div" + this_id).show();
					<?php if($_SESSION['user'] == 2 || $_SESSION['access'] == 3) { ?>
					$("#edit_link_div" + this_id).show();
					<?php } ?>
                    return false;
                });
            });
            </script>
            <?php } ?>
    
            <tr><td colspan="5"></td>
            <td>
                <?php if(isset($_SESSION['user'])) { ?>
                    <form method="post" action="<?php echo SITE_URL . "/comment"; ?>">
                    <input type="hidden" name="post_id" value="<?php echo $view[0]['id']; ?>" />
                    <input type="hidden" name="post_slug" value="<?php echo $_GET['post']; ?>" />
                    <strong>comment</strong><div style="padding-bottom:4px;"><textarea name="comment_input" id="comment_input" rows="1" style="width:600px;" /></textarea></div><input type="submit" name="submit" value="submit" />
                    </form>
                <?php } else { ?>
                    you must <a href="<?php echo SITE_URL; ?>/login" style="text-decoration:underline;">login</a> to comment on an article | don't have an account? <a href="<?php echo SITE_URL; ?>/register" style="text-decoration:underline;">register here</a>
                <?php } ?>
            </td></tr>
            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
    
            <?php // comments list ?>
    
            <tr><td colspan="5"></td>
            <td>
                <div class="comments">
                    <a name="comment_start"></a>
                    <?php // query outer comments
                    $outer_comment = samq_c("SELECT comment.id,post,perm_mod,perm_admin,login,thread,text,author,users.voted_count AS user_score,comment.score,comment.created FROM comment INNER JOIN users ON author = users.id WHERE post = " . $view[0]['id'] . " AND thread IS NULL ORDER BY comment.score DESC, comment.created DESC",1);
    
                    foreach($outer_comment as $oc) {
                    // check to see if user is logged in
                    if(isset($_SESSION['user'])) {
                        // see if user has submitted this entry
                        if($_SESSION['user_id'] == $oc['author']) {
                            $voteoc_check = "vote";
                        } elseif(count(samq("vote_comment","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND comment = " . $oc['id'])) > 0) {
                            $voteoc_check = "vote";	
                        } else {
                            $voteoc_check = "novote";	
                        }
                    } else {
                        // user is not logged in, flag to remove hyperlink from vote arrow
                        $voteoc_check = "guest";
                    } ?>
                        <a name="comment<?php echo $oc['id']; ?>"></a>
                        <table class="comment_table comment_outer<?php if(isset($_SESSION['user']) && $_SESSION['user_id'] == $oc['author']) echo " my_comment" ?>">
                                <tr class="comment_head_tr"><td class="comment_votes_td"><span class="comment_votes_outer" id="comment_votes_outer<?php echo $oc['id']; ?>"><a href="#comment<?php echo $oc['id']; ?>"><span id="comment_votes_inner<?php echo $oc['id']; ?>"><?php echo $oc['score']; ?></span></a></span></td>
                                <td class="comment_up_td">
                                <span<?php if($voteoc_check == "vote") echo " style='display:none;'"; ?> id="comment_vote_arrow<?php echo $oc['id']; ?>">
                                    <?php if($voteoc_check != "guest") { ?><a href="#" class="vote_comment_up" id="<?php echo $oc['id']; ?>"><?php } ?>
                                        <img src="<?php echo IMAGES_PATH; ?>up.gif" alt="vote up" />
                                    <?php if($voteoc_check != "guest") { ?></a><?php } ?>
                                </span>
                                <span<?php if($voteoc_check != "vote" || $voteoc_check == "guest") echo " style='display:none;'"; ?> id="comment_voted_arrow<?php echo $oc['id']; ?>">
                                    <img src="<?php echo IMAGES_PATH; ?>up_voted.gif" alt="voted up" />
                                </span>
                            </td>
                            <td><span class="comment_author"><a href="<?php echo SITE_URL; ?>/u/<?php echo $oc['login']; ?>"><?php echo $oc['login'] ?></a></span>(<?php echo $oc['user_score']; ?>)<?php if($oc['perm_mod'] == 1) echo "(<span class='letter_mod'>m</span>)"; if($oc['perm_admin'] == 1) echo "(<span class='letter_admin'>a</span>)"; ?><?php if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) { ?> <div id="edit_link_div<?php echo $oc['id']; ?>" style="display:inline;"><span class="admin_link"><a href="#" class="edit_link" id="<?php echo $oc['id']; ?>">edit</a></span></div><?php } ?></td><td class="comment_date_td"><?php echo time_since(strtotime($oc['created'])); ?></td></tr>
                            <tr class="comment_text_outer_tr"><td colspan="4" class="comment_text_td"><div class="comment_text" id="comment_text<?php echo $oc['id']; ?>"><?php echo make_clickable(nl2br($oc['text'])); ?></div>
                            <?php if(isset($_SESSION['user'])) { ?>
                                <input type="hidden" name="thread<?php echo $oc['id']; ?>" id="<?php echo $oc['id']; ?>" />
                                <div id="reply_link_div<?php echo $oc['id']; ?>"><a href="#" id="<?php echo $oc['id']; ?>" class="reply_link" style="float:right;">reply</a></div>
                            <?php } ?>
                            </td></tr>
                        </table>
                        <br />
    
                        <?php // query inner comments
                        $inner_comment = samq("comment","comment.id,post,perm_mod,perm_admin,login,thread,text,author,users.voted_count AS user_score,comment.score,comment.created","INNER JOIN users ON author = users.id","post = " . $view[0]['id'] . " AND thread = " . $oc['id'],"comment.created ASC");
                        foreach($inner_comment as $ic) {
                        // check to see if user is logged in
                        if(isset($_SESSION['user'])) {
                            // see if user has submitted this entry
                            if($_SESSION['user_id'] == $ic['author']) {
                                $voteic_check = "vote";
                            } elseif(count(samq("vote_comment","userid",NULL,"userid = " . esc($_SESSION['user_id']) . " AND comment = " . $ic['id'])) > 0) {
                                $voteic_check = "vote";	
                            } else {
                                $voteic_check = "novote";
                            }
                        } else {
                            // user is not logged in, flag to remove hyperlink from vote arrow
                            $voteic_check = "guest";
                        } ?>
                            <a name="comment<?php echo $ic['id']; ?>"></a>
                            <table class="comment_table comment_inner<?php if(isset($_SESSION['user']) && $_SESSION['user_id'] == $ic['author']) echo " my_comment" ?>">
                                <tr class="comment_head_tr"><td class="comment_votes_td"><span class="comment_votes_outer" id="comment_votes_outer<?php echo $ic['id']; ?>"><a href="#comment<?php echo $ic['id']; ?>"><span id="comment_votes_inner<?php echo $ic['id']; ?>"><?php echo $ic['score']; ?></span></a></span></td>
                                <td class="comment_up_td">
                                <span<?php if($voteic_check == "vote") echo " style='display:none;'"; ?> id="comment_vote_arrow<?php echo $ic['id']; ?>">
                                    <?php if($voteic_check != "guest") { ?><a href="#" class="vote_comment_up" id="<?php echo $ic['id']; ?>"><?php } ?>
                                        <img src="<?php echo IMAGES_PATH; ?>up.gif" alt="vote up" />
                                    <?php if($voteic_check != "guest") { ?></a><?php } ?>
                                </span>
                                <span<?php if($voteic_check != "vote" || $voteic_check == "guest") echo " style='display:none;'"; ?> id="comment_voted_arrow<?php echo $ic['id']; ?>">
                                    <img src="<?php echo IMAGES_PATH; ?>up_voted.gif" alt="voted up" />
                                </span>
                                </td>
                                <td><span class="comment_author"><a href="<?php echo SITE_URL; ?>/u/<?php echo $ic['login']; ?>"><?php echo $ic['login'] ?></a></span>(<?php echo $ic['user_score']; ?>)<?php if($ic['perm_mod'] == 1) echo "(<span class='letter_mod'>m</span>)"; if($ic['perm_admin'] == 1) echo "(<span class='letter_admin'>a</span>)"; ?><?php if(isset($_SESSION['access']) && ($_SESSION['access'] == 2 || $_SESSION['access'] == 3)) { ?> <div id="edit_link_div<?php echo $ic['id']; ?>" style="display:inline;"><span class="admin_link"><a href="#" class="edit_link" id="<?php echo $ic['id'] ?>">edit</a></span></div><?php } ?></td><td class="comment_date_td"><?php echo time_since(strtotime($ic['created'])); ?></td></tr>
                                <tr class="comment_text_inner_tr"><td colspan="4" class="comment_text_td"><div class="comment_text" id="comment_text<?php echo $ic['id']; ?>"><?php echo make_clickable(nl2br($ic['text'])); ?></div>
                                <?php if(isset($_SESSION['user'])) { ?>
                                    <input type="hidden" name="thread<?php echo $ic['id']; ?>" id="<?php echo $oc['id']; ?>" />
                                    <div id="reply_link_div<?php echo $ic['id']; ?>"><a href="#" id="<?php echo $ic['id']; ?>" class="reply_link" style="float:right;">reply</a></div>
                                <?php } ?>
                                </td></tr>
                            </table>
                            <br />
                        <?php } ?>
                    <?php } ?>
                </div>
            </td></tr>
            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
            <tr class="listing_spacer_tr"><td colspan="6"></td></tr>
    </table>
    
<?php } else { ?>
	<div class="content"><br />doesn't exist, <a href="javascript:history.go(-1);">back</a></div><br /><br />
<?php }
include('foot.php'); ?>
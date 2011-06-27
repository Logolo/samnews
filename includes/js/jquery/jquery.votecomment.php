<script type="text/javascript">
	$(function(){	   
		$("a.vote_comment_up").click(function(){
			the_id = this.id;
		  
			// fadeout the vote-count
			$("span#comment_votes_outer"+the_id).fadeOut("fast");
			// fade out the vote-arrow
			$("span#comment_vote_arrow"+the_id).fadeOut("fast");
	
			//the main ajax request
			$.ajax({
				type: "POST",
				data: {id:the_id},
				url: "<?php echo SITE_URL; ?>/vote_comment.php",
				success: function(msg)
				{
					$("span#comment_votes_inner"+the_id).html(msg);
	
					//fadein the vote count
					$("span#comment_votes_outer"+the_id).fadeIn();
					// fade in the voted-arrow
					$("span#comment_voted_arrow"+the_id).fadeIn();
				}
			});
			
			return false;
		});
	});
</script>
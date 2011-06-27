$(function(){	   
	$("a.vote_up").click(function(){
		the_id = this.id;
	  
		// fadeout the vote-count
		$("span#listing_votes_outer"+the_id).fadeOut("fast");
		// fade out the vote-arrow
		$("span#vote_arrow"+the_id).fadeOut("fast");

		//the main ajax request
		$.ajax({
			type: "POST",
			data: {id:the_id},
			url: "<?php echo SITE_URL; ?>vote.php",
			success: function(msg)
			{
				$("span#listing_votes_inner"+the_id).html(msg);

				//fadein the vote count
				$("span#listing_votes_outer"+the_id).fadeIn();
				// fade in the voted-arrow
				$("span#voted_arrow"+the_id).fadeIn();
			}
		});
	});
});
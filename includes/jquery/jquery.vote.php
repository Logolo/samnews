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
?>

<script type="text/javascript">
	jQuery(function(){	   
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
				url: "<?php echo SITE_URL; ?>/vote.php",
				success: function(msg)
				{
					$("span#listing_votes_inner"+the_id).html(msg);
	
					//fadein the vote count
					$("span#listing_votes_outer"+the_id).fadeIn();
					// fade in the voted-arrow
					$("span#voted_arrow"+the_id).fadeIn();
				}
			});
			
			return false;
		});
	});
</script>
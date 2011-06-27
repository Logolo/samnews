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

function slugify($str)
{
	$delimiter = "_";
	$count_delimiter = "-";
	
	setlocale(LC_ALL, 'en_US.UTF8');
	$str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$str = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $str);
	$str = strtolower(trim($str, '-'));
	$str = preg_replace("/[\/_|+ -]+/", $delimiter, $str);

	// check to see if slug already exists in post table
	$check_dupes = samq("post","slug",NULL,"trim(slug) = '" . $str . "'");

	if(count($check_dupes) > 0) {
		// if slug does exist, find the highest
		$find_highest = samq_c("SELECT slug FROM post WHERE trim(slug) LIKE '" . $str . $count_delimiter . "%' ORDER BY slug DESC LIMIT 0,1",1);

		// if there is a highest, capture count and increase by 1
		if(count($find_highest) != 0) {
			$explode_highest = explode($count_delimiter, $find_highest[0]['slug']);
			$str = $str . $count_delimiter . (string)($explode_highest[1] + 1);
		} else {
			// if there is no highest, this will be the second
			$str = $str . $count_delimiter . "2";
		}
	}
	return $str;
}
?>
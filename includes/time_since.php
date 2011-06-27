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

function time_since($original)
{
    $iTimeElapsed = time() - $original;

    if($iTimeElapsed < (60)) {
        $iNum = intval($iTimeElapsed); $sUnit = "second";
    } else if($iTimeElapsed < (60*60)) {
        $iNum = intval($iTimeElapsed / 60); $sUnit = "minute";
    } else if($iTimeElapsed < (24*60*60)) {
        $iNum = intval($iTimeElapsed / (60*60)); $sUnit = "hour";
    } else if($iTimeElapsed < (30*24*60*60)) {
        $iNum = intval($iTimeElapsed / (24*60*60)); $sUnit = "day";
    } else if($iTimeElapsed < (365*24*60*60)) {
        $iNum = intval($iTimeElapsed / (30*24*60*60)); $sUnit = "month";
    } else {
        $iNum = intval($iTimeElapsed / (365*24*60*60)); $sUnit = "year";
    }

    return $iNum . " " . $sUnit . (($iNum != 1) ? "s ago" : " ago");
}
?>
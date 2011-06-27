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

// replace special characters
function replace_schars($str)
{
     $badwordchars=array(
         "\xe2\x80\x98", // left single quote
         "\xe2\x80\x99", // right single quote
         "\xe2\x80\x9c", // left double quote
         "\xe2\x80\x9d", // right double quote
		 "\xe2\x80\x93", // long dash
		 "\xe2\x80\x94", // long dash
         "\xe2\x80\xa6" // elipses
     );
     $fixedwordchars=array(
         "'",
         "'",
         '"',
         '"',
		 '-',
         '-',
         '...'
     );
     return (str_replace($badwordchars,$fixedwordchars, $str));
}
?>
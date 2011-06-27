<?php
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
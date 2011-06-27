<?php
function neat_trim($str, $n, $delim='-') {
	$str = str_replace("\n","",$str);
	$str = str_replace("\r","",$str);
	$str = strip_tags($str);
	$len = strlen($str);
	if ($len > $n) {
		preg_match('/(.{' . $n . '}.*?)\b/', $str, $matches);
		return rtrim($matches[1]) . $delim;
	} else {
		return $str;
	}
}
?>
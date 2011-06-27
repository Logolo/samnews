<?php
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
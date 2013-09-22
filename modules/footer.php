<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
 
if($GLOBALS['showLoadTime']==TRUE) 
{
	$end = number_format((microtime(true) - $GLOBALS['start']),2);
	echo "Page loaded in ", $end, " seconds. <br/>";
}
echo "&copy <a href='http://forums.nomsoftware.com/'>Nomsoft</a> 2011-2012<br>All rights reserved"
#echo $GLOBALS['footer_text'];
?>
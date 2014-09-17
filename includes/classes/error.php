<?php
/*

-----------To be removed.----------

function //buildError($error,$num)
{
	if ($GLOBALS['useDebug'] == false)
		log_error($error,$num);
	else 
		errors($error,$num);
}

function errors($error,$num) 
{
	log_error(strip_tags($error),$num);
	die("<center><b>Website error</b>  <br/>
		The website encountered an error. <br/><br/>
		<b>Error message: </b>".$error."  <br/>
		<b>Error number: </b>".$num."</center>");
}

function log_error($error,$num) 
{
    error_log("*[".date("d M Y H:i")."] ".$error, 3, "error.log");
}

function loadCustomErrors() 
{
    set_error_handler("customError");
}

function customError($errno, $errstr)
{
    if ($errno!=8 && $errno!=2048 && $GLOBALS['useDebug']==true)
          error_log("*[".date("d M Y H:i")."]<i>".$errstr."</i>", 3, "error.log");
}
*/
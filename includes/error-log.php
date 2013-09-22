<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */

?>
<?php 
define('INIT_SITE', TRUE);
require('configuration.php'); 

if($GLOBALS['useDebug']==false)
	exit();
?>

<h2>Error log</h2>

<a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=clear" title="Clear the entire log">Clear log</a>
<hr/>

<?php
if (isset($_GET['action']) && $_GET['action']=='clear') 
{
	$errFile = '../error.log';
	$fh = fopen($errFile, 'w') or die("can't open file");
	$stringData = "";
	fwrite($fh, $stringData);
	fclose($fh);
  ?>
  	<meta http-equiv="Refresh" content="0; url=<?php echo $_SERVER['PHP_SELF']; ?>">
  <?php
}
if(!$file = file_get_contents('../error.log')) {
  echo 'The script could not get any contents from the error.log file.';
}

echo str_replace('*','<br/>',$file);

?>
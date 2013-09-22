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
 require('includes/classes/template_parse.php'); 
 
 connect::selectDB('webdb');

 $getTemplate = mysql_query("SELECT path FROM template WHERE applied='1' ORDER BY id ASC LIMIT 1");
 $row = mysql_fetch_assoc($getTemplate);
 
 $template['path']=$row['path'];
 
 
 if(!file_exists("styles/".$template['path']."/style.css") || !file_exists("styles/".$template['path']."/template.html")) 
 {
	 buildError("<b>Template Error: </b>The active template does not exist or missing files.",NULL);
	 exit_page();
 }
 
 ?>
<link rel="stylesheet" href="styles/<?php echo $template['path']; ?>/style.css" />
<link rel="stylesheet" href="styles/global/style.css" />
<?php
	plugins::load('styles');
?>
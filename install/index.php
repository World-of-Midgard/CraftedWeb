<?php
/*
             _____           ____
            |   __|_____ _ _|    \ ___ _ _ ___
            |   __|     | | |  |  | -_| | |_ -|
            |_____|_|_|_|___|____/|___|\_/|___|
     Copyright (C) 2013 EmuDevs <http://www.emudevs.com/>
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>CraftedWeb Installation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="install.css" type="text/css" media="screen" />
</head>
<body>
 <center>
<div id="main_box">
	<h1>CraftedWeb Installer</h1>

	<div id="content">
    	<p id="steps"><b>Introduction</b> &raquo; MySQL Info &raquo; Configure &raquo; Database &raquo; Realm Info &raquo; Finished<p>
        <hr/>
        <p>Welcome to CraftedWeb!</p><BR>
		 <p>To install, just follow the onscreen instructions and enter your information correctly.</p> 
		 <p>You will need a MySQL User login and Database along with your server information before you continue.<p>
		 <p>Please CHMOD 777 <i>'includes/configuration.php'</i> AND <i>'install/sql/CraftedWeb_Base.sql'</i> ahead of time.</p>
		 <p>When ready, start the installation process</p>
        <p><input type="submit" value="Start the installation" onclick="window.location='install.php?st=1'"></p>
	</div>
</div>
&copy 2011-2012 <a href="http://nomsoftware.com/">Nomsoft</a>

</center>
</body>
</html>
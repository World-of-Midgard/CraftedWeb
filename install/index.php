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
	<title>CW Installer</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="install.css" type="text/css" media="screen" />
</head>
<body>
 <center>
<div id="main_box">
	<h1>Follow the steps carefully!</h1>

	<div id="content">
    	<p id="steps"><b>Introduction</b> &raquo; MySQL Info &raquo; Configure &raquo; Database &raquo; Realm Info &raquo; Finished<p>
        <hr/>
        <p><h2>CraftedWeb</h2>A project by <a href="http://emudevs.com/">EmuDevs</a></p><br>
		 <p>To install, follow the onscreen instructions and enter your information <b>correctly.</b>
           <p>You will need a MySQL User login and Database along with your server information before you continue.<p>
            <ul>
            <b>Please CHMOD 777 the following:</b>
            <li><i>includes/configuration.php</i></li>
            <li><i>install/sql/CraftedWeb_Base.sql</i></li>
            <li><i>install/sql/updates/*.sql</i></li>
            </ul><br>
		 <p>When ready, start the installation process</p>
        <p><input type="submit" value="Start the installation" onclick="window.location='install.php?st=1'"></p>
	</div>
</div>
&copy 2013 <a href="http://nomsoftware.com/">EmuDevs.com</a>
</center>
</body>
</html>
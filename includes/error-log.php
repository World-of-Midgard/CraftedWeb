<?php
/*
           ___           __ _           _ __    __     _
          / __\ __ __ _ / _| |_ ___  __| / / /\ \ \___| |__
         / / | '__/ _` | |_| __/ _ \/ _` \ \/  \/ / _ \ '_ \
        / /__| | | (_| |  _| ||  __/ (_| |\  /\  /  __/ |_) |
        \____/_|  \__,_|_|  \__\___|\__,_| \/  \/ \___|_.__/
                          --[ Build 1.5 ]--
                    - coded and revised by Faded -

    CraftedWeb is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This is distributed in the hope that it will be useful, but
    WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    included license for more details.

    Support/FAQ #EmuDevs - http://emudevs.com
*/

    define('INIT_SITE', true);
    require('configuration.php');
    if($GLOBALS['useDebug'] == false)
        exit();
    ?>
    <h2>Error log</h2>
    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?action=clear" title="Clear the entire log">Clear log</a>
    <hr/>
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'clear')
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
    if(!$file = file_get_contents('../error.log'))
        echo 'The script could not get any contents from the error.log file.';
    echo str_replace('*','<br/>',$file);